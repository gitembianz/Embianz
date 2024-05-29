<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);

        // Seed database with currencies
        $this->call(CurrencySeeder::class);

        // Seed database with category
        $this->call(CategorySeeder::class);

        // // Seed database with payments
        $this->call(PaymentSeeder::class);

        // // Seed database with statuses
        $this->call(StatusSeeder::class);

        // Seed database with stores
        $this->call(StoreSeeder::class);
    }
}
