<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('name')->nullable();
      $table->string('sku')->nullable()->unique();
      $table->string('ean')->nullable()->unique();
      $table->boolean('active');
      $table->boolean('is_new')->nullable()->default(
        false
      );
      $table->integer('popularity')->nullable();
      $table->longText('long_description')->nullable();
      $table->string('short_description')->nullable();
      $table->string('meta_description')->nullable();
      $table->integer('quantity')->nullable();
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->string('seo_title')->nullable();
      $table->string('seo_id')->unique()->nullable();
      $table->string('created_by')->nullable();
      $table->string('last_modified_by')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('products');
  }
};
