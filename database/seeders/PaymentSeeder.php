<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payments')->insert([
            ['name' => 'cash', 'description' => 'Plata cash la livrare', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ordin', 'description' => 'Ordin de plata', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'card', 'description' => 'Plata cu cardul', 'active' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
