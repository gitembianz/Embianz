<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('store__settings')->insert([
            ['parameter' => 'slider_category', 'value' => '1'],
            ['parameter' => 'delivery_price', 'value' => '20'],
            ['parameter' => 'limit_category', 'type' => '5'],
            // Add more status values for carts
        ]);
    }
}
