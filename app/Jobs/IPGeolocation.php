<?php

namespace App\Jobs;

use App\Models\AllJob;
use App\Models\ScheduleJob;
use App\Models\UserSessions;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IPGeolocation implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected int $allJobId;
  protected ?AllJob $jobRecord = null;

  public function __construct(int $allJobId)
  {
    $this->allJobId = $allJobId;
  }

  public function handle()
  {
    $messages = [];
    $messages[] = "Starting IP Geolocation job with AllJob ID: {$this->allJobId}";

    $lastRun = AllJob::where('type', 'session_geolocation')
      ->where('status', 'finished')
      ->latest('finished_at')
      ->value('finished_at');

    $query = UserSessions::whereNull('status');
    if ($lastRun) {
      $query->where('created_at', '>', $lastRun);
    }

    $total = $query->count();
    $messages[] = "Found {$total} sessions to process.";

    $chunkSize = 100;
    $maxRequests = 15;
    $processedRequests = 0;

    $query->orderBy('id', 'desc')->chunk($chunkSize, function ($sessions) use (&$processedRequests, $maxRequests, &$messages) {

      if ($processedRequests >= $maxRequests) {
        $messages[] = "Reached API limit (15/min). Stopping.";
        return false;
      }

      $batch = $sessions->map(fn($s) => ['query' => $s->ip_address])->toArray();

      $response = Http::timeout(10)->post('http://ip-api.com/batch', $batch);

      if ($response->failed()) {
        if ($response->status() == 429) {
          $ttl = (int) $response->header('X-Ttl', 60);
          $messages[] = "Rate limit reached. Sleeping {$ttl} seconds...";
          sleep($ttl);
        } else {
          $messages[] = "HTTP error: " . $response->status() . " - " . $response->body();
        }
        return false;
      }

      $remaining = (int) $response->header('X-Rl', 0);
      $ttl = (int) $response->header('X-Ttl', 60);
      $results = $response->json();

      foreach ($results as $i => $geo) {
        $session = $sessions[$i] ?? null;
        if (!$session) continue;

        if (($geo['status'] ?? '') === 'success') {
          $session->update([
            'status' => 'success',
            'country' => $geo['country'] ?? null,
            'countryCode' => $geo['countryCode'] ?? null,
            'region' => $geo['region'] ?? null,
            'regionName' => $geo['regionName'] ?? null,
            'city' => $geo['city'] ?? null,
            'zip' => $geo['zip'] ?? null,
            'lat' => $geo['lat'] ?? null,
            'lon' => $geo['lon'] ?? null,
            'timezone' => $geo['timezone'] ?? null,
            'isp' => $geo['isp'] ?? null,
            'org' => $geo['org'] ?? null,
            'as' => $geo['as'] ?? null,
          ]);
        } else {
          $session->update(['status' => 'failed']);
        }
      }

      $processedRequests++;
      $messages[] = "Processed request {$processedRequests}/{$maxRequests} | Remaining: {$remaining}, TTL: {$ttl}s";

      if ($remaining <= 0) {
        $messages[] = "API limit reached (X-Rl=0). Sleeping {$ttl} seconds...";
        sleep($ttl);
      } else {
        sleep(4);
      }

      if ($processedRequests >= $maxRequests) {
        $messages[] = "Stopped: reached 15 requests/min limit.";
        return false;
      }
    });

    $this->finish('finished', $messages);
  }

  protected function finish(string $status, array $messages)
  {
    $log = implode("\n", $messages);

    $this->jobRecord = AllJob::find($this->allJobId);
    $this->jobRecord?->update([
      'status' => $status,
      'finished_at' => now(),
      'log' => ($this->jobRecord->log ?? '') . "\n" . $log,
    ]);

    ScheduleJob::create([
      'name' => 'IP Geolocation Update',
      'type' => 'ip_geolocation',
      'status' => $status,
      'job_id' => $this->allJobId,
      'details' => $log,
      'finished_at' => now(),
    ]);
  }
}
