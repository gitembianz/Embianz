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
            ['parameter' => 'default_country', 'value' => 'Romania', 'description' => 'Default shipping country', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'low_stock', 'value' => '10', 'description' => 'Low Stock for display tags on product', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'limit_load', 'value' => '16', 'description' => 'Limit Load products', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'limit_slideritems', 'value' => '10', 'description' => 'Limit products on slider items', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'limit_searchitems', 'value' => '10', 'description' => 'Limit items on search', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'order_prefix', 'value' => 'NRN', 'description' => 'Order prefix', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'stripe_key', 'value' => 'sk_test_51Op9rnBQZaJ5Yyz4MobMR7Ckodq5SsmNtEb5c4ktwtA7pasOXxm7tbcWlPKv3oKeDspQfPeplyPY6qVRrJdxobTN00DP0ZWmNQ', 'description' => 'Stripe Payment key', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'auto_webp', 'value' => 'true', 'description' => 'Salvare automata a imaginilor in webp (valaore- true-false)', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);
    }
}
