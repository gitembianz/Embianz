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
        'symbol' => 'lei',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'EUR',
        'symbol' => '€',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'USD',
        'symbol' => '$',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'MDL',
        'symbol' => 'lei',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'GBP',
        'symbol' => '£',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
    ];
    DB::table('currencies')->insert($currencies);
  }
}
