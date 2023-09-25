<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanCart extends Command
{
  protected $signature = 'cart:clean';
  protected $description = 'Delete old cart items';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    // Calculate the date one day ago
    $oneDayAgo = Carbon::now()->subDay();

    // Delete rows older than a day
    Cart::where('created_at', '<', $oneDayAgo)->delete();

    $this->info('Old cart items have been deleted.');
  }
}
