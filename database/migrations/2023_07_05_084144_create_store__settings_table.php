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
    Schema::create('store__settings', function (Blueprint $table) {
      $table->id();
      $table->string('parameter');
      $table->longText('value');
      $table->longText('description')->nullable();
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
    Schema::dropIfExists('store__settings');
  }
};
