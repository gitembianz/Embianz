<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $currentTime = now();

        DB::table('statuses')->insert([
            ['name' => 'New', 'type' => 'cart', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'Checkout', 'type' => 'cart', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'Closed', 'type' => 'cart', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            // Add more status values for carts with timestamps
        ]);

        DB::table('statuses')->insert([
            ['name' => 'New', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'Processing', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'Delivered', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'Cancelled', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'On Hold', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'Closed', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            // Add more status values for orders with timestamps
        ]);

        DB::table('statuses')->insert([
            ['name' => 'New', 'type' => 'voucher', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'used', 'type' => 'voucher', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            // Add more status values for vouchers with timestamps
        ]);
    }
}
