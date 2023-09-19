<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanWishlist extends Command
{
  protected $signature = 'wishlist:clean';
  protected $description = 'Delete old wishlist items';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    // Calculate the date one day ago
    $oneDayAgo = Carbon::now()->subDay();

    // Delete rows older than a day
    DB::table('wishlist')->where('created_at', '<', $oneDayAgo)->delete();

    $this->info('Old wishlist items have been deleted.');
  }
}
