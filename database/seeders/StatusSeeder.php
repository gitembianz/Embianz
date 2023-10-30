<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('statuses')->insert([
            ['name' => 'New', 'type' => 'cart'],
            ['name' => 'Checkout', 'type' => 'cart'],
            ['name' => 'Closed', 'type' => 'cart'],
            // Add more status values for carts
        ]);

        DB::table('statuses')->insert([
            ['name' => 'New', 'type' => 'order'],
            ['name' => 'Processing', 'type' => 'order'],
            ['name' => 'Pending Fulfillment', 'type' => 'order'],
            ['name' => 'Shipped', 'type' => 'order'],
            ['name' => 'Delivered', 'type' => 'order'],
            ['name' => 'Cancelled', 'type' => 'order'],
            ['name' => 'Refunded', 'type' => 'order'],
            ['name' => 'On Hold', 'type' => 'order'],
            ['name' => 'Closed', 'type' => 'order'],
            // Add more status values for orders
        ]);
    }
}
