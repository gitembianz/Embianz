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
            ['name' => 'culoare', 'created_at' => now(config('app.timezone')), 'updated_at' => now(config('app.timezone'))],
            ['name' => 'marime', 'created_at' => now(config('app.timezone')), 'updated_at' => now(config('app.timezone'))],
            ['name' => 'material', 'created_at' => now(config('app.timezone')), 'updated_at' => now(config('app.timezone'))],
        ]);
    }
}
