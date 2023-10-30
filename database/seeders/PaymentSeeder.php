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
            ['name' => 'card', 'active' => true],
            ['name' => 'cash on delivery', 'active' => true],
            ['name' => 'invoice', 'active' => true],
            // Add more status values for carts
        ]);
    }
}
