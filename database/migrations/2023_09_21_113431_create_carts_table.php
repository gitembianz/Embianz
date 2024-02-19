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
    Schema::create('carts', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('session_id');
      $table->integer('quantity_amount')->default(0);
      $table->decimal('sum_amount', 10, 2)->default(0);
      $table->unsignedBigInteger('currency_id')->index()->nullable();
      $table->foreign('currency_id')->references('id')->on('currencies');
      $table->unsignedBigInteger('status_id')->index()->nullable();
      $table->foreign('status_id')->references('id')->on('statuses');
      $table->decimal('final_amount', 10, 2)->default(0);
      $table->unsignedBigInteger('order_id')->index()->nullable();
      $table->decimal('delivery_price', 5, 2)->nullable();
      $table->unsignedBigInteger('voucher_id')->index()->nullable();
      $table->foreign('voucher_id')->references('id')->on('vouchers');
      $table->decimal('voucher_value', 10, 2)->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('carts');
  }
};
