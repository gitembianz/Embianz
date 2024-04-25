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
            ['parameter' => 'limit_category', 'value' => '6', 'description' => 'Limit Category', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'site_name', 'value' => 'noren.ro', 'description' => 'Site Name', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'default_country', 'value' => 'Romania', 'description' => 'Default shipping country', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'low_stock', 'value' => '10', 'description' => 'Low Stock for display tags on product', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'limit_load', 'value' => '16', 'description' => 'Limit Load products', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'limit_slideritems', 'value' => '10', 'description' => 'Limit products on slider items', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'limit_searchitems', 'value' => '10', 'description' => 'Limit items on search', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'order_prefix', 'value' => 'NRN', 'description' => 'Order prefix', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'stripe_key', 'value' => 'sk_test_51Op9rnBQZaJ5Yyz4MobMR7Ckodq5SsmNtEb5c4ktwtA7pasOXxm7tbcWlPKv3oKeDspQfPeplyPY6qVRrJdxobTN00DP0ZWmNQ', 'description' => 'Stripe Payment key', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'auto_webp', 'value' => 'true', 'description' => 'Salvare automata a imaginilor in webp (valaore- true-false)', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'header_top_text', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'order_default_text_confirmation', 'value' => 'Comanda a fost plasata cu succes!', 'description' => 'Default confrim text', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'default_category', 'value' => '1', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'show_on_breadcrumbs', 'value' => 'true', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'show_on_header', 'value' => 'false', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'order_error_quantity', 'value' => 'Stocul disponibil pentru unul sau mai multe produse a fost modificat!', 'description' => 'Mesaj eroare order product quantity', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'order_error_voucher', 'value' => 'Voucher-ul selectat nu mai este disponibil!', 'description' => 'Eroare la voucher', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'order_error_active', 'value' => 'Unul sau mai multe produse din coș nu mai este disponibil!', 'description' => 'order error la disponibilitate', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'order_error_cart', 'value' => 'Coșul de cumpărături a fost modificat!', 'description' => 'eroare cart', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cash_limit', 'value' => '0', 'description' => 'Limita plata cash', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'time_zone', 'value' => '+0', 'description' => 'Time zone +0,1 or -0,1, current is +3', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'mainpage_metadescription', 'value' => '', 'description' => 'Meta descriere prima pagina', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'mainpage_metatitle', 'value' => '', 'description' => 'Titlu prima pagina', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'send_to_salesforce', 'value' => 'false', 'description' => 'Trimitere request salesforce', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'site_url', 'value' => 'www.noren.ro', 'description' => 'URL-ul siteului', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'facebook_url', 'value' => 'https://www.facebook.com/norenRomania', 'description' => 'link facebook', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'instagram_url', 'value' => 'https://www.instagram.com/norenromania/', 'description' => 'link instagram', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'robots_txt', 'value' => 'User-agent: * <br> Disallow: /embadmin <br> Sitemap: https://embianz.com/sitemap.xml ', 'description' => 'Content for robots.txt (keep in mind to use <br> for a new line)', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);
    }
}
