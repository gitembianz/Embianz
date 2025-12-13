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
        ->where('next_run_at', '<=', Carbon::now(config('app.timezone')))
        ->get();

    \Log::info('Found jobs to run', ['count' => $jobs->count()]);

    foreach ($jobs as $job) {
        \Log::info('Processing job', ['name' => $job->name, 'id' => $job->id]);
        
        if (class_exists($job->name)) {
            try {
                // Create execution record
                $executionJob = AllJob::create([
                    'name' => $job->name,
                    'type' => $job->type ?? 'unknown',
                    'status' => 'running',
                    'started_at' => now(config('app.timezone')),
                ]);
                
                \Log::info('Created execution job', ['execution_id' => $executionJob->id]);
                
                // Dispatch with the execution ID
                dispatch(new $job->name($executionJob->id));
                $this->info("✅ Executed: {$job->name} with ID: {$executionJob->id}");

                // Update next run time
                $interval = (int) filter_var($job->recurrence_rule, FILTER_SANITIZE_NUMBER_INT) ?: 5;
                $job->update([
                    'next_run_at' => Carbon::now(config('app.timezone'))->addMinutes($interval),
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Job dispatch failed', [
                    'job' => $job->name,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $job->update(['status' => 'error']);
            }
        } else {
            $this->error("❌ Class not found: {$job->name}");
            $job->update(['status' => 'error']);
        }
    }

    return 0;
}


}