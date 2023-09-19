<?php

namespace App\Console\Commands;

use App\Models\Wishlist;
use Illuminate\Console\Command;
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
    Wishlist::where('created_at', '<', $oneDayAgo)->delete();

    $this->info('Old wishlist items have been deleted.');
  }
}
