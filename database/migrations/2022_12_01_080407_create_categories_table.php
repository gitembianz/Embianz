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
    Schema::create('categories', function (Blueprint $table) {
      $table->id();
      $table->string('name')->nullable();
      $table->string('accepted_items')->nullable()->default(
        'default'
      );
      $table->boolean('active');
      $table->boolean('has_parent')->nullable()->default(
        false
      );
      $table->boolean('preload_image')->nullable()->default(
        true
      );
      $table->boolean('display_variant_price')->default(false)->nullable();

      $table->longText('long_description')->nullable();
      $table->string('short_description')->nullable();
      $table->string('meta_description')->nullable();
      $table->integer('sequence')->nullable();
      $table->integer('slider_sequence')->nullable()->default(
        '0'
      );
      $table->string('start_date')->nullable();
      $table->string('end_date')->nullable();
      $table->boolean('store_tab');
      $table->string('seo_title')->nullable();
      $table->string('seo_id')->unique()->nullable();
      $table->string('createdby')->nullable();
      $table->string('lastmodifiedby')->nullable();
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
    Schema::dropIfExists('categories');
  }
};