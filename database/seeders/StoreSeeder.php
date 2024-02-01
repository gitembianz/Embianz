<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTime = now();

        DB::table('store__settings')->insert([
            ['parameter' => 'delivery_price', 'value' => '20', 'description' => 'Delivery Price', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'limit_category', 'value' => '5', 'description' => 'Limit Category', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'site_name', 'value' => 'Noren', 'description' => 'Site Name', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'default_country', 'value' => 'rou', 'description' => 'Default shipping country', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],

            // Add more parameter values for store settings with timestamps
        ]);
    }
}
