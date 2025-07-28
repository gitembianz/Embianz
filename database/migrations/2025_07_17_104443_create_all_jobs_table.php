<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('all_jobs', function (Blueprint $table) {
      $table->id();

      $table->string('name'); // Job class, e.g. App\Jobs\ProcessCsvChunkJob
      $table->string('type')->nullable(); // Custom type/category, e.g. 'csv_import', 'email', 'price_update'
      $table->string('status')->default('queued'); // queued, processing, completed, failed
      $table->string('related_table')->nullable(); // e.g. products, users
      $table->unsignedBigInteger('related_id')->nullable(); // optional ID in the related table
      $table->json('payload')->nullable(); // Parameters or input data
      $table->json('meta')->nullable(); // Extra metadata (like row counts, filenames, user_id, etc.)
      $table->text('error')->nullable(); // Exception message if failed
      $table->integer('progress')->nullable(); // Optional: 0-100 progress %
      $table->timestamp('started_at')->nullable(); // When processing started
      $table->timestamp('finished_at')->nullable(); // When job completed or failed
      $table->timestamps(); // created_at = queued time
      // 🔁 Recurrence fields
      $table->boolean('is_recurring')->default(false);
      $table->string('recurrence_rule')->nullable(); // e.g. "* * * * *", "daily", "weekly", etc.
      $table->timestamp('next_run_at')->nullable();  // next scheduled time
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('all_jobs');
  }
};
