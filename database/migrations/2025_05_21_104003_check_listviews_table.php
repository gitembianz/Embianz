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
        Schema::create('listviews', function (Blueprint $table) {
                $table->id();
                if (!Schema::hasColumn('listviews', 'user_id')) {
                  Schema::table('listviews', function (Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                  });
                }
                if (!Schema::hasColumn('listviews', 'table')) {
                  Schema::table('listviews', function (Blueprint $table) {
                    $table->string('table')->nullable();
                  });
                }
                if (!Schema::hasColumn('listviews', 'name')) {
                  Schema::table('listviews', function (Blueprint $table) {
                    $table->string('name')->nullable();
                  });
                }
                if (!Schema::hasColumn('listviews', 'columns')) {
                  Schema::table('listviews', function (Blueprint $table) {
                    $table->json('columns')->nullable();
                  });
                }
                if (!Schema::hasColumn('listviews', 'filters')) {
                  Schema::table('listviews', function (Blueprint $table) {
                    $table->json('filters')->nullable();
                  });
                }
                 if (!Schema::hasColumn('listviews', 'sorts')) {
                  Schema::table('listviews', function (Blueprint $table) {
                    $table->json('sorts')->nullable();
                  });
                }
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listviews');
    }
};
