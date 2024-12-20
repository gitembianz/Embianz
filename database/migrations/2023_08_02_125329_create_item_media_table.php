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
    Schema::create('item_media', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('media_id');
      $table->morphs('mediable'); // This creates columns: mediable_id and mediable_type
      $table->timestamps();

      // Foreign key constraint for media_id column
      $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('item_media');
  }
};
