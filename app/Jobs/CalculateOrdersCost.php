<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\AllJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculateOrdersCost implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  protected int $allJobId;
  /**
   * Create a new job instance.
   */
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

      Order::with('orders.product.costs')->chunk(500, function ($entries) {
        foreach ($entries as $order) {
          $cost = 0;
          foreach ($order->orders as $item) {
            if ($item->product->costs->isEmpty()) {
              $cost = 0;
              break;
            }
            if (!$item->product->costs->last()->cost) {
              $possiblecost = $item->product->costs->where('cost', '!=', null)->last();
              if ($possiblecost) {
                $cost += $possiblecost->cost * $item->quantity;
              } else {
                $cost = 0;
                break;
              }
            } else {
              $cost += optional($item->product->costs->last())->cost  * $item->quantity;
            }
          }
          if ($cost > 0) {
            $order->update(['avg_cost' => $cost]);
          }
        }
      });


      AllJob::where('id', $this->allJobId)->update([
        'status' => 'finished',
        'finished_at' => now(),
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
