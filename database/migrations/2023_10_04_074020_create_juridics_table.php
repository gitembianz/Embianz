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
        Schema::create('juridics', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->string('company_name');
            $table->string('registration_code');
            $table->string('registration_number');
            $table->string('bank_name');
            $table->string('account');
            $table->string('address1');
            $table->string('address2')->nullable();
            $table->string('country');
            $table->string('county')->nullable();
            $table->string('city');
            $table->string('zipcode');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juridics');
    }
};
