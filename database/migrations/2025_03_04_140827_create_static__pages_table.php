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
        Schema::create('static__pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content'); // For storing large HTML content
            $table->string('slug')->unique(); // For creating routes based on records
            $table->boolean('display_in_footer')->default(false); // Bool value for displaying in footer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('static__pages');
    }
};
