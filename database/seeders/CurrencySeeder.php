<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run()
  {
    $currentTime = now();

    $currencies = [
      [
        'name' => 'RON',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'EUR',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'USD',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'MDL',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'GBP',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      // Add more currencies as needed with timestamps
    ];

    // Insert the records into the "currencies" table
    DB::table('currencies')->insert($currencies);
  }
}
