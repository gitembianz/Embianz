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
        'simbol' => 'lei',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'EUR',
        'simbol' => '€',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'USD',
        'simbol' => '$',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'MDL',
        'simbol' => 'lei',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
      [
        'name' => 'GBP',
        'simbol' => '£',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
        'created_at' => $currentTime,
        'updated_at' => $currentTime,
      ],
    ];
    DB::table('currencies')->insert($currencies);
  }
}
