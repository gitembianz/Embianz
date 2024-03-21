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
            ['parameter' => 'header_top_text', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'confirm_order_text', 'value' => 'Comanda a fost plasata cu succes!
Vă mulțumim pentru plata efectuată!  Am primit-o și în prezent procesăm comanda dumneavoastră. Echipa noastră lucrează cu dedicație pentru a pregăti produsul dumneavoastră pentru expediere. 

Odată ce comanda dumneavoastră este în drum spre dumneavoastră, vă vom trimite un e-mail de confirmare cu informații despre urmărire. Acest lucru vă va permite să urmăriți coletul și să știți când să vă așteptați la sosirea sa. 

Dacă aveți întrebări sau aveți nevoie de asistență, vă rugăm să nu ezitați să contactați echipa noastră de suport pentru clienți. Suntem aici pentru a vă ajuta și pentru a vă asigura satisfacția. 

Apreciem afacerea dumneavoastră și sperăm că achiziția dumneavoastră vă aduce fericire. Vă mulțumim că ați ales produsele noastre și așteptăm cu nerăbdare să vă mai servim în viitor. 
Cu cele mai bune urări!', 'description' => 'Default confrim text', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],


        ]);
    }
}
