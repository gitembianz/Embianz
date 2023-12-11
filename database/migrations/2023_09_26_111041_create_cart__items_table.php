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
    Schema::create('cart__items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('cart_id');
      $table->foreign('cart_id')->references('id')->on('carts');
      $table->unsignedBigInteger('product_id');
      $table->foreign('product_id')->references('id')->on('products');
      $table->decimal('price', 10, 2)->default(0);
      $table->integer('quantity')->default(0);
      $table->decimal('delivery_price', 10, 2)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cart__items');
  }
};
