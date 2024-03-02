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
    Schema::create('vouchers', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code')->unique();
      $table->decimal('percent', 5, 2)->nullable();
      $table->decimal('value', 10, 2)->nullable();
      $table->unsignedBigInteger('status_id')->index()->nullable();
      $table->foreign('status_id')->references('id')->on('statuses');
      $table->boolean('single_use')->default(true);
      $table->string('start_date')->nullable();
      $table->string('end_date')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('vouchers');
  }
};
