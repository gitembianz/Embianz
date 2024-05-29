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
    Schema::create('media', function (Blueprint $table) {
      $table->id();
      $table->longText('path')->nullable();
      $table->string('sequence')->nullable();
      $table->string('type')->nullable();
      $table->string('extension')->nullable();
      $table->string('name')->nullable();
      $table->string('width')->nullable();
      $table->string('height')->nullable();
      $table->string('size')->nullable();
      $table->string('createdby')->nullable();
      $table->string('lastmodifiedby')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('media');
  }
};
