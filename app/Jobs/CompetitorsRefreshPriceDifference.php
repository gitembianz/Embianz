<?php

namespace App\Jobs;

use App\Models\AllJob;
use Illuminate\Bus\Queueable;
use App\Models\PricelistEntries;
use App\Models\CompetitorProducts;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CompetitorsRefreshPriceDifference implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   */
  protected int $allJobId;

  public function __construct(int $allJobId)
  {
    $this->allJobId = $allJobId;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    AllJob::where('id', $this->allJobId)->update(['status' => 'processing']);

    try {
      CompetitorProducts::chunk(500, function ($entries) {
        foreach ($entries as $competitor) {
          if (!is_null($competitor->product_id)) {
            $productprice = PricelistEntries::where('product_id', $competitor->product_id)
              ->latest('created_at')
              ->value('value') ?? 0;

            $competitorPrice = $competitor->price ?? 0;
            $differenceValue = $competitorPrice - $productprice;
            $differencePercentage = $productprice > 0
              ? round(($differenceValue / $competitorPrice) * 100, 2)
              : 0;
            $competitor->update([
              'internal_price' => $productprice,
              'difference_value' => $differenceValue,
              'difference_percent' => $differencePercentage,
              'last_modified_by' => 'system',
              'updated_at' => now(config('app.timezone')),
            ]);
          }
        }
      });

      AllJob::where('id', $this->allJobId)->update([
        'status' => 'finished',
        'finished_at' => now(config('app.timezone')),
      ]);
    } catch (\Throwable $e) {
      AllJob::where('id', $this->allJobId)->update([
        'status' => 'failed',
        'error' => $e->getMessage(),
      ]);

      throw $e;
    }
  }
}
