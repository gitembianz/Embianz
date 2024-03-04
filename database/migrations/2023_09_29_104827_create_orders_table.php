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
      $table->string('order_number')->nullable();
      $table->string('name')->nullable();
      $table->string('session_id')->nullable();
      $table->unsignedBigInteger('account_id')->index()->nullable();
      $table->foreign('account_id')->references('id')->on('accounts');
      $table->unsignedBigInteger('cart_id')->index()->nullable();
      $table->foreign('cart_id')->references('id')->on('carts');
      $table->integer('quantity_amount')->default(0);
      $table->decimal('sum_amount', 10, 2)->default(0);
      $table->unsignedBigInteger('currency_id')->index()->nullable();
      $table->foreign('currency_id')->references('id')->on('currencies');
      $table->unsignedBigInteger('status_id')->index()->nullable();
      $table->foreign('status_id')->references('id')->on('statuses');
      $table->unsignedBigInteger('payment_id')->index()->nullable();
      $table->foreign('payment_id')->references('id')->on('payments');
      $table->unsignedBigInteger('voucher_id')->index()->nullable();
      $table->foreign('voucher_id')->references('id')->on('vouchers');
      $table->decimal('voucher_value', 10, 2)->default(0);
      $table->decimal('delivery_price', 5, 2)->default(0);
      $table->decimal('final_amount', 10, 2)->default(0);
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
