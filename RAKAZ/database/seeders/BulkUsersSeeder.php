<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BulkUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 5000;

        $existing = (int) DB::table('users')->count();
        $start = $existing + 1;

        $now = now();
        $passwordHash = Hash::make('password123');

        $batch = [];
        $batchSize = 1000;

        for ($i = 0; $i < $count; $i++) {
            $n = $start + $i;

            $batch[] = [
                'name' => "User {$n}",
                'email' => sprintf('user%05d@rakaz.test', $n),
                'phone' => null,
                'password' => $passwordHash,
                'role' => 'user',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) >= $batchSize) {
                DB::table('users')->insert($batch);
                $batch = [];
            }
        }

        if (count($batch) > 0) {
            DB::table('users')->insert($batch);
        }

        $this->command?->info("BulkUsersSeeder: created {$count} users. Existing before: {$existing}.");
        $this->command?->info('Default password for generated users: password123');
    }
}
