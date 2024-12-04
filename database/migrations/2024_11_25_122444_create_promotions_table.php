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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->longText('details')->nullable();
            $table->unsignedBigInteger('voucher_id')->index()->nullable();
            $table->foreign('voucher_id')->references('id')->on('vouchers');
            $table->string('voucher_code')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active');
            $table->integer('cooldown_timer')->nullable();
            $table->integer('cart_amount')->nullable();
            $table->integer('cookie_time')->nullable()->default(30);
            $table->string('cookieid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
