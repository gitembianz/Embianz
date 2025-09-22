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
    if (!Schema::hasTable('product_reviews')) {
      Schema::create('product_reviews', function (Blueprint $table) {
        $table->id();
        $table->string('acronim')->nullable();
        $table->unsignedBigInteger('product_id')->nullable();
        $table->foreign('product_id')->references('id')->on('products');
        $table->integer('score')->nullable()->default(0);
        $table->longText('comment')->nullable();
        $table->boolean('approved')->nullable()->default(false);
        $table->timestamps();
      });
    }
    // If it exists but column is missing, add the column
    else {
      if (!Schema::hasColumn('product_reviews', 'acronim')) {
        Schema::table('product_reviews', function (Blueprint $table) {
          $table->string('acronim')->nullable()->after('id');
        });
      }
      if (!Schema::hasColumn('product_reviews', 'product_id')) {
        Schema::table('product_reviews', function (Blueprint $table) {
          $table->unsignedBigInteger('product_id')->index()->nullable()->after('acronim');
          $table->foreign('product_id')->references('id')->on('products');
        });
      }
      if (!Schema::hasColumn('product_reviews', 'score')) {
        Schema::table('product_reviews', function (Blueprint $table) {
          $table->integer('score')->nullable()->default(0)->after('product_id');
        });
      }
      if (!Schema::hasColumn('product_reviews', 'comment')) {
        Schema::table('product_reviews', function (Blueprint $table) {
          $table->longText('comment')->nullable()->after('score');
        });
      }
      if (!Schema::hasColumn('product_reviews', 'approved')) {
        Schema::table('product_reviews', function (Blueprint $table) {
          $table->boolean('approved')->nullable()->default(false)->after('comment');
        });
      }
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_reviews');
  }
};
