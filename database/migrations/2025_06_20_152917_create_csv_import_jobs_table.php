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
    Schema::create('csv_import_jobs', function (Blueprint $table) {
      $table->id();
      $table->string('queue');
      $table->string('name')->nullable();
      $table->string('type')->nullable();
      $table->json('meta')->nullable();
      $table->enum('status', ['pending', 'processing', 'finished', 'failed'])->default('pending');
      $table->timestamp('started_at')->nullable();
      $table->timestamp('finished_at')->nullable();
      $table->text('errors')->nullable();
      $table->string('error_file')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('csv_import_jobs');
  }
};
