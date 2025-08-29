<?php

use FontLib\Table\Type\name;
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
        if (!Schema::hasTable('order__suppliers')) {
            Schema::create('order__suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('status');
                $table->date('date')->nullable();
                $table->string('currency ')->nullable();
                $table->string('created_by')->nullable();
                $table->string('last_modified_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order__suppliers');
    }
};