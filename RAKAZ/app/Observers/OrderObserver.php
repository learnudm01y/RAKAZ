<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "updating" event.
     * This runs before the order is updated
     */
    public function updating(Order $order)
    {
        // Check if status is changing to cancelled
        if ($order->isDirty('status') && $order->status === 'cancelled') {
            $originalStatus = $order->getOriginal('status');

            // Only restore stock if the order wasn't already cancelled
            if ($originalStatus !== 'cancelled') {
                // Load items with products
                $order->load('items.product');

                foreach ($order->items as $item) {
                    if ($item->product) {
                        // Restore stock
                        $item->product->increaseStock($item->quantity);

                        // Decrease sales count
                        if ($item->product->sales_count >= $item->quantity) {
                            $item->product->decrement('sales_count', $item->quantity);
                        }

                        \Log::info("Stock restored for product {$item->product->id}: +{$item->quantity} (Order #{$order->order_number} cancelled)");
                    }
                }
            }
        }
    }

    /**
     * Handle the Order "deleting" event.
     * Restore stock when order is deleted
     */
    public function deleting(Order $order)
    {
        // Only restore stock if order is not already cancelled
        if ($order->status !== 'cancelled') {
            // Load items with products
            $order->load('items.product');

            foreach ($order->items as $item) {
                if ($item->product) {
                    // Restore stock
                    $item->product->increaseStock($item->quantity);

                    // Decrease sales count
                    if ($item->product->sales_count >= $item->quantity) {
                        $item->product->decrement('sales_count', $item->quantity);
                    }

                    \Log::info("Stock restored for product {$item->product->id}: +{$item->quantity} (Order #{$order->order_number} deleted)");
                }
            }
        }
    }
}
