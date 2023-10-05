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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->string('session_id');
      $table->unique(['session_id']);
      $table->integer('quantity_amount')->default(0);
      $table->integer('sum_amount')->default(0);
      $table->unsignedBigInteger('currency_id')->index()->nullable();
      $table->foreign('currency_id')->references('id')->on('currencies');
      $table->text('status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
