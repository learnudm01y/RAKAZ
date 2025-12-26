<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulkContactMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 5000;

        $existing = (int) DB::table('contact_messages')->count();
        $start = $existing + 1;

        $batch = [];
        $batchSize = 1000;

        for ($i = 0; $i < $count; $i++) {
            $n = $start + $i;

            // Spread created_at over the last ~60 days to look realistic
            $minutesAgo = ($i % (60 * 24 * 60));
            $createdAt = now()->subMinutes($minutesAgo);

            // Status distribution: mostly new/read, few replied/archived
            $mod = $i % 100;
            if ($mod < 55) {
                $status = 'new';
            } elseif ($mod < 90) {
                $status = 'read';
            } elseif ($mod < 98) {
                $status = 'replied';
            } else {
                $status = 'archived';
            }

            $readAt = null;
            if ($status === 'read' || $status === 'replied' || $status === 'archived') {
                $readAt = (clone $createdAt)->addMinutes(5 + ($i % 240));
            }

            $batch[] = [
                'first_name' => 'Customer',
                'last_name' => (string) $n,
                'email' => sprintf('customer%05d@mail.test', $n),
                'phone' => null,
                'subject' => "Support message #{$n}",
                'message' => "Hello, this is a seeded contact message number #{$n}.\n\nI am contacting support regarding my order / product question. Please review and respond when possible.\n\nThanks.",
                'status' => $status,
                'read_at' => $readAt,
                'created_at' => $createdAt,
                'updated_at' => $readAt ?? $createdAt,
            ];

            if (count($batch) >= $batchSize) {
                DB::table('contact_messages')->insert($batch);
                $batch = [];
            }
        }

        if (count($batch) > 0) {
            DB::table('contact_messages')->insert($batch);
        }

        $this->command?->info("BulkContactMessagesSeeder: created {$count} contact messages. Existing before: {$existing}.");
    }
}
