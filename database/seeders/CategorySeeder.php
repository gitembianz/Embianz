<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Toate produsele',
            'has_parrent' => false,
            'store_tab' => false,
            'active' => true,
            'long_description' => 'Explorează colecția noastră de produse și găsește accesoriile perfecte pentru a-ți completa stilul!',
            'meta_description' => 'Explorează colecția noastră de produse și găsește accesoriile perfecte pentru a-ți completa stilul!',
            'short_description' => 'Explorează colecția noastră de produse și găsește accesoriile perfecte pentru a-ți completa stilul!',
            'sequence' => 1,
            'slider_sequence' => 0,
            'start_date' => now(),
            'end_date' => '2030-01-01',
            'createdby' => 'admin',
            'lastmodifiedby' => 'admin',
            'seo_title' => 'Toate produsele',
            'seo_id' => null
        ]);
    }
}
