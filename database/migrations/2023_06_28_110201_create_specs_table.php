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
    Schema::create('specs', function (Blueprint $table) {
      $table->id();
      $table->string('name')->nullable();
      $table->string('um')->nullable();
      $table->string('sequence')->nullable();
      $table->boolean('mark_as_filter')->nullable()->default(
        false
      );
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
    Schema::dropIfExists('specs');
  }
};
