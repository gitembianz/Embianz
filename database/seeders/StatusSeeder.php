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
            ['name' => 'new', 'type' => 'cart', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'checkout', 'type' => 'cart', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'checkoutdetails', 'type' => 'cart', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'check_payment', 'type' => 'cart', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'closed', 'type' => 'cart', 'created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        DB::table('statuses')->insert([
            ['name' => 'check_payment', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'processing', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'delivered', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'cancelled', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'hold', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'closed', 'type' => 'order', 'created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        DB::table('statuses')->insert([
            ['name' => 'active', 'type' => 'voucher', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['name' => 'closed', 'type' => 'voucher', 'created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);
    }
}
