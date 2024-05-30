<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TextLabelSeeder extends Seeder
{
    public static function labels()
    {
        $currentTime = now();
        return [
            ['parameter' => 'support_title', 'value' => 'La noi te bucuri de:', 'description' => 'Support title - h2', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'support_livechat_title', 'value' => 'Live Chat', 'description' => 'Support livechat title - h3', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'support_livechat_description', 'value' => 'Operatorii noștri sunt gata să-ți răspundă la orice întrebare și să te asiste în găsirea soluțiilor potrivite nevoilor tale.', 'description' => 'Support livechat description - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'support_delivery_title', 'value' => 'Livrări de încredere', 'description' => 'Support delivery title - h3', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'support_delivery_description', 'value' => 'Lucrăm cu firme de curierat de top pentru livrări rapide și sigure.', 'description' => 'Support Delivery description - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'support_secure_title', 'value' => 'Comenzi si plăți 100% sigure', 'description' => 'Support secure title - h3', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'support_secure_description', 'value' => 'Tranzacții sigure și comenzi protejate - angajamentul nostru pentru tine.', 'description' => 'Support secure description - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'support_faq_title', 'value' => 'Întrebări frecvente (FAQ)', 'description' => 'Support faq title - h3', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'support_faq_description', 'value' => 'Poți găsi răspunsuri la cele mai comune întrebări ale clienților <a href="{{ url("/faq") }}">aici', 'description' => 'Support faq description - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_description', 'value' => 'Acest site web utilizează cookie-uri pentru a îmbunătăți experiența dvs. de navigare și pentru a vă oferi cel mai bun serviciu posibil pe platforma noastră.', 'description' => 'Cookie description - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_policy', 'value' => 'Vedeți Politica de Cookies', 'description' => 'Cookie policy - a', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_esential_title', 'value' => 'Cookie-uri Esențiale (Necesare)', 'description' => 'Cookie-uri Esențiale title - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_esential_description', 'value' => 'Acestea sunt cookie-uri esențiale care asigură funcționarea corectă a site-ului web și păstrarea preferințelor dvs. (de ex., limbă, regiune).', 'description' => 'Cookie-uri Esențiale descriotion - p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_analitics_title', 'value' => 'Cookie-uri Analitice', 'description' => 'Cookie-uri Analitice title - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_analitics_description', 'value' => 'Aceste cookie-uri includ cookie-uri de performanță și cookie-uri de analiză a vizitatorilor.', 'description' => 'Cookie-uri Analitice description - p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_marketing_title', 'value' => 'Cookie-uri de Marketing title - span', 'description' => 'Cookie-uri de Marketing title - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_marketing_description', 'value' => 'Aceste cookie-uri sunt utilizate în scopuri de marketing.', 'description' => 'Cookie-uri de Marketing description - p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_accept_button', 'value' => 'Acceptă', 'description' => 'Cookie accept - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'cookie_advanced_button', 'value' => 'Avansat', 'description' => 'Cookie advanced - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'newsletter_title', 'value' => 'Bun venit în Comunitatea Noastră!', 'description' => 'Newsletter title - h3', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'newsletter_description', 'value' => 'Mulțumim mult că te-ai abonat la newsletter-ul nostru! Apreciem interesul tău și suntem
				încântați să facem parte din călătoria ta online. Te vom ține la curent cu cele mai recente știri, oferte speciale
				și actualizări. Dacă dorești să ne contactezi sau ai întrebări, nu ezita să ne scrii!', 'description' => 'Newsletter description - p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'newsletter_close_button', 'value' => 'Inchide', 'description' => 'Newsletter close - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'newsletter_subscribe_title', 'value' => 'Abonează-te la newsletter-ul nostru', 'description' => 'Newsletter subscribe - h2', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'newsletter_subscribe_input', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => '', 'value' => '', 'description' => '', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
        ];
    }

    public function run(): void
    {
        DB::table('text_labels')->insert(self::labels());
    }
}
