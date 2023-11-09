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
            ['name' => 'Plata cash la livrare', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ordin de plata', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            // Add more status values for carts with timestamps
        ]);
    }
}
