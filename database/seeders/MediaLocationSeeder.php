<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTime = now();

        DB::table('media_locations')->insert([
            ['location' => 'main', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['location' => 'search', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            ['location' => 'details', 'created_at' => $currentTime, 'updated_at' => $currentTime],
            // Add more status values for carts with timestamps
        ]);
    }
}
