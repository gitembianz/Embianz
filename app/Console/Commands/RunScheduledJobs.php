<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AllJob;
use Illuminate\Support\Carbon;

class RunScheduledJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can call this command via: php artisan jobs:run-scheduled
     */
    protected $signature = 'jobs:run-scheduled';

    /**
     * The console command description.
     */
    protected $description = 'Run dynamically scheduled jobs from AllJob table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jobs = AllJob::where('is_recurring', true)
            ->where('active', true)
            ->where('next_run_at', '<=', Carbon::now())
            ->get();

        foreach ($jobs as $job) {
            if (class_exists($job->name)) {
                dispatch(new $job->name());
                $this->info("✅ Executed: {$job->name}");

                // Update next run time
                $interval = (int) filter_var($job->recurrence_rule, FILTER_SANITIZE_NUMBER_INT) ?: 5;
                $job->update([
                    'next_run_at' => Carbon::now()->addMinutes($interval),
                    'status' => 'running',
                ]);
            } else {
                $this->error("❌ Class not found: {$job->name}");
                $job->update(['status' => 'error']);
            }
        }
    }
}
