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
    $currencies = [
      [
        'name' => 'RON',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
      ],
      [
        'name' => 'EUR',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
      ],
      [
        'name' => 'USD',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
      ],
      [
        'name' => 'MDL',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
      ],
      [
        'name' => 'GBP',
        'createdby' => 'admin',
        'lastmodifiedby' => 'admin',
      ],
      // Add more currencies as needed
    ];

    // Insert the records into the "currencies" table
    DB::table('currencies')->insert($currencies);
  }
}
