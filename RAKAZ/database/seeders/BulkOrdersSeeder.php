<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulkOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $ordersToCreate = 5000;
        $batchSize = 500;

        $productRows = DB::table('products')
            ->select(['id', 'name', 'sku', 'main_image'])
            ->where('is_active', true)
            ->limit(5000)
            ->get();

        if ($productRows->isEmpty()) {
            $this->command?->warn('BulkOrdersSeeder: no active products found; skipping.');
            return;
        }

        $users = DB::table('users')->select(['id'])->limit(3000)->pluck('id');

        $existing = (int) DB::table('orders')->count();
        $start = $existing + 1;

        $now = now();
        $created = 0;

        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $statusWeights = [35, 18, 18, 14, 10, 5];

        $countries = ['UAE', 'Saudi Arabia', 'Kuwait', 'Bahrain', 'Qatar', 'Oman'];

        while ($created < $ordersToCreate) {
            $take = min($batchSize, $ordersToCreate - $created);

            $ordersBatch = [];
            $orderNumbers = [];

            for ($i = 0; $i < $take; $i++) {
                $n = $start + $created + $i;
                $minutesAgo = ($n % (60 * 24 * 90)); // spread over ~90 days
                $createdAt = $now->copy()->subMinutes($minutesAgo);

                $status = $this->pickWeighted($statuses, $statusWeights);

                $confirmedAt = null;
                $shippedAt = null;
                $deliveredAt = null;

                if (in_array($status, ['confirmed', 'processing', 'shipped', 'delivered'], true)) {
                    $confirmedAt = $createdAt->copy()->addHours(2);
                }
                if (in_array($status, ['shipped', 'delivered'], true)) {
                    $shippedAt = ($confirmedAt ?? $createdAt)->copy()->addDays(1);
                }
                if ($status === 'delivered') {
                    $deliveredAt = ($shippedAt ?? $createdAt)->copy()->addDays(2);
                }

                $paymentMethod = 'cash';
                $paymentStatus = $status === 'delivered' ? 'paid' : 'pending';

                $itemsCount = 1 + ($n % 3); // 1..3 items
                $subtotal = 0.0;

                // We'll compute totals after inserting items, but need a rough subtotal now.
                // Keep deterministic-ish values.
                for ($k = 0; $k < $itemsCount; $k++) {
                    $base = 79 + (($n + $k) % 420);
                    $price = $base + (((($n + $k) % 2) === 0) ? 0.99 : 0.00);
                    $qty = 1 + (($n + $k) % 2);
                    $subtotal += $price * $qty;
                }

                $shippingCost = (($n % 10) < 7) ? 0.0 : ((($n % 10) < 9) ? 20.0 : 50.0);
                $tax = round(($subtotal + $shippingCost) * 0.05, 2);

                $discount = 0.0;
                if (($n % 20) === 0) {
                    $discount = round(min(60.0, $subtotal * 0.08), 2);
                }

                $total = round(max(0, $subtotal + $shippingCost + $tax - $discount), 2);

                $country = $countries[$n % count($countries)];

                $orderNumber = 'ORD-' . $createdAt->format('YmdHis') . '-' . str_pad((string) $n, 6, '0', STR_PAD_LEFT);
                $orderNumbers[] = $orderNumber;

                $userId = null;
                if (!$users->isEmpty() && ($n % 4) !== 0) {
                    $userId = $users[$n % $users->count()] ?? null;
                }

                $ordersBatch[] = [
                    'order_number' => $orderNumber,
                    'user_id' => $userId,
                    'customer_name' => 'Customer ' . $n,
                    'customer_email' => sprintf('customer%05d@orders.test', $n),
                    'customer_phone' => '+9715' . str_pad((string) (($n * 37) % 10000000), 7, '0', STR_PAD_LEFT),
                    'shipping_address' => 'Street ' . (($n % 120) + 1) . ', Building ' . (($n % 80) + 1),
                    'shipping_city' => ($country === 'UAE') ? 'Dubai' : 'City ' . (($n % 30) + 1),
                    'shipping_state' => null,
                    'shipping_postal_code' => null,
                    'shipping_country' => $country,
                    'billing_address' => null,
                    'billing_city' => null,
                    'billing_state' => null,
                    'billing_postal_code' => null,
                    'billing_country' => null,
                    'subtotal' => round($subtotal, 2),
                    'shipping_cost' => $shippingCost,
                    'tax' => $tax,
                    'discount' => $discount,
                    'total' => $total,
                    'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus,
                    'status' => $status,
                    'notes' => null,
                    'confirmed_at' => $confirmedAt,
                    'shipped_at' => $shippedAt,
                    'delivered_at' => $deliveredAt,
                    'created_at' => $createdAt,
                    'updated_at' => $deliveredAt ?? $shippedAt ?? $confirmedAt ?? $createdAt,
                ];
            }

            DB::table('orders')->insert($ordersBatch);

            // Resolve order IDs by order_number for this batch
            $insertedOrders = DB::table('orders')
                ->select(['id', 'order_number', 'created_at'])
                ->whereIn('order_number', $orderNumbers)
                ->get()
                ->keyBy('order_number');

            $itemsBatch = [];
            foreach ($orderNumbers as $idx => $orderNumber) {
                $order = $insertedOrders[$orderNumber] ?? null;
                if (!$order) continue;

                $n = $start + $created + $idx;
                $itemsCount = 1 + ($n % 3);

                for ($k = 0; $k < $itemsCount; $k++) {
                    $product = $productRows[($n + $k) % $productRows->count()];

                    $name = $product->name;
                    if (is_string($name)) {
                        $productName = $name;
                    } else {
                        // JSON column: may be decoded as object/array depending on driver
                        $arr = json_decode(json_encode($name), true);
                        $productName = $arr['ar'] ?? $arr['en'] ?? 'Product';
                    }

                    $base = 79 + (($n + $k) % 420);
                    $price = $base + (((($n + $k) % 2) === 0) ? 0.99 : 0.00);
                    $qty = 1 + (($n + $k) % 2);
                    $sub = round($price * $qty, 2);

                    $itemsBatch[] = [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $productName,
                        'product_sku' => $product->sku,
                        'product_image' => $product->main_image,
                        'size' => null,
                        'shoe_size' => null,
                        'color' => null,
                        'quantity' => $qty,
                        'price' => round($price, 2),
                        'subtotal' => $sub,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->created_at,
                    ];
                }
            }

            // Insert items in chunks to avoid huge single queries
            $chunk = 2000;
            for ($off = 0; $off < count($itemsBatch); $off += $chunk) {
                DB::table('order_items')->insert(array_slice($itemsBatch, $off, $chunk));
            }

            $created += $take;
            $this->command?->info("BulkOrdersSeeder: inserted {$created}/{$ordersToCreate} orders...");
        }

        $this->command?->info("BulkOrdersSeeder: finished inserting {$ordersToCreate} orders.");
    }

    private function pickWeighted(array $values, array $weights): string
    {
        $total = array_sum($weights);
        $r = mt_rand(1, max(1, $total));
        $running = 0;
        foreach ($values as $i => $v) {
            $running += (int) ($weights[$i] ?? 1);
            if ($r <= $running) return (string) $v;
        }
        return (string) $values[0];
    }
}
