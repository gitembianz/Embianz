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
    Schema::create('pricelist_entries', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('product_id')->index();
      $table->foreign('product_id')->references('id')->on('products');
      $table->unsignedBigInteger('pricelist_id')->index();
      $table->foreign('pricelist_id')->references('id')->on('price_lists');
      $table->decimal('value', 10, 2)->nullable();
      $table->decimal('value_no_vat', 10, 2)->nullable();
      $table->integer('discount')->default(
        '0'
      );
      $table->decimal('vat', 5, 2)->nullable();
      $table->decimal('price', 10, 4)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pricelist_entries');
  }
};
