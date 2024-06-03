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
            ['parameter' => 'newsletter_subscribe_title', 'value' => 'Abonează-te la newsletter-ul nostru', 'description' => 'Newsletter subscribe title - label', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'newsletter_subscribe_input', 'value' => 'Sunt de acord cu <a href="' . route('terms') . '">Termenii și condițiile</a> abonării la newsletter privind stocarea și prelucrarea datelor cu caracter personal.', 'description' => 'Newsletter subscribe input - h2', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'newsletter_subscribe_placeholder', 'value' => 'Introduceți adresa dvs. de email', 'description' => 'Newsletter subscribe - placeholder', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'newsletter_subscribe_button', 'value' => 'Trimite', 'description' => 'Newsletter subscribe - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'footer_copyright', 'value' => 'Copyright ©️ 2024 <a href="' . url('/') . '">noren</a> | Powered by <a href="https://eztemcorp.com">Eztem Corp</a>', 'description' => 'Footer copyright - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'alert_title', 'value' => 'Ceva nu a mers bine!', 'description' => 'General alert - h3', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'alert_button_close', 'value' => 'Inchide', 'description' => 'General alert close  - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'add_to_cart_button', 'value' => 'Adaugă în coș', 'description' => 'Add to cart - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'add_to_cart_button_indisponibil', 'value' => 'Indisponibil', 'description' => 'Add to cart indisponibil - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'product_status_stock', 'value' => 'Stock limitat!!', 'description' => 'Product status for Stock limitat - p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'product_status_indisponible', 'value' => 'Produs indisponibil!', 'description' => 'Product status indisponible - p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'product_status_coming_soon', 'value' => 'În curând!', 'description' => 'Product status for coming soon - p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'support_button', 'value' => 'Support', 'description' => 'Support - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'breadcrumbs_home_page', 'value' => 'Acasă', 'description' => 'Breadcrumbs home - a', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'breadcrumbs_allproducts', 'value' => 'Toate produsele', 'description' => 'Breadcrumbs - all products - a', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'placeholder_search', 'value' => 'Caută...', 'description' => 'General placeholder for search - input', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'remove_all_filters', 'value' => 'Elimină toate filtrele', 'description' => 'Remove all filters - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'message_no_elements', 'value' => 'Nu au fost elemente gasite', 'description' => 'Message no found - p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'breadcrumbs_search', 'value' => 'Cautare', 'description' => 'Breadcrumbs home - a', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'search_title', 'value' => 'Rezultatele căutării:', 'description' => 'Search title - h2', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'search_product_element', 'value' => 'Produse', 'description' => 'Search product element - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'search_category_element', 'value' => 'Categorii', 'description' => 'Search category element - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'display_filters_results', 'value' => 'Afișează rezultate:', 'description' => 'Display filters results - button', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_title', 'value' => 'Ordonează după:', 'description' => 'Produst sort title - div', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_popularity', 'value' => 'Cele mai populare', 'description' => 'Sort popularity - h4', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_price_as', 'value' => 'Preț crescător', 'description' => 'sort by price asc - h4', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_price_ds', 'value' => 'Preț descrescător', 'description' => 'sort by price - desc -h4', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_quantity_as', 'value' => 'Disponibilitate (stoc crescator)', 'description' => 'sort by quantity - h4', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_quantity_ds', 'value' => 'Disponibilitate (stoc descrescator)', 'description' => 'sort by quantity - h4', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_name_az', 'value' => 'Alfabetic, A-Z', 'description' => 'sort by name - h4', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_name_za', 'value' => 'Alfabetic, Z-A', 'description' => 'sort by name - h4', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_date_as', 'value' => 'Dată, de la nou la vechi', 'description' => 'sort by date - h4', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'sort_date_ds', 'value' => 'Dată, de la vechi la nou', 'description' => 'sort by date - h4', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'wishlist_title', 'value' => 'Vizualizare produse favorite', 'description' => 'Wishlist title - a', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'wishlist_empty', 'value' => 'Nu sunt produse adăugate în lista de favorite', 'description' => 'Wishlist empty - span', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'wishlist_add_to_cart', 'value' => 'Produsul a fost adăugat în coș!', 'description' => 'Wishlist add to cart - p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'wishlist_page_title', 'value' => 'Produse favorite', 'description' => 'Wishlist page title - h1', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['parameter' => 'wishlist_page_description', 'value' => 'Vezi produsele alese mai jos', 'description' => 'Wishlist Page description -p', 'createdby' => 'admin', 'lastmodifiedby' => 'admin', 'created_at' => $currentTime, 'updated_at' => $currentTime],
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