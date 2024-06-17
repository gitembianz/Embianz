<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('variants')->insert([
            ['name' => 'culoare', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'marime', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'material', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
