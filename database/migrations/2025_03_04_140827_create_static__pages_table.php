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
            $table->string('name')->nullable();
            $table->longText('content')->nullable();
            $table->string('route')->unique()->nullable();
            $table->integer('sequence')->nullable();
            $table->string('description')->nullable();
            $table->boolean('display_in_footer')->default(false);
            $table->string('created_by')->nullable();
            $table->string('last_modified_by')->nullable();
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
