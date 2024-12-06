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
        Schema::create('user_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id')->index()->nullable();
            $table->foreign('session_id')->references('id')->on('user_sessions')->onDelete('cascade');
            $table->unsignedBigInteger('promotion_id')->index()->nullable();
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->string('promotion_type')->nullable();
            $table->string('promotion_cookieid')->nullable();
            $table->dateTime('promotion_start_date')->nullable();
            $table->dateTime('promotion_expiration_date')->nullable();
            $table->integer('promotion_cooldown_timer')->nullable();
            $table->integer('promotion_cart_amount')->nullable();
            $table->integer('promotion_value')->nullable();
            $table->integer('promotion_percent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_promotions');
    }
};
