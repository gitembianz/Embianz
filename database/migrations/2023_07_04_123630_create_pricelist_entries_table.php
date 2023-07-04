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
      $table->string('value');
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
