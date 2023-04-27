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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('item_id');
            $table->string('media_path')->nullable();
            $table->string('media_sequence')->nullable();
            $table->string('media_type')->nullable();
            $table->string('media_title')->nullable();
            $table->unsignedBigInteger('media_location_id')->index();
            $table->foreign('media_location_id')->references('id')->on('media_locations');
            $table->unsignedBigInteger('media_tabel_id')->index();
            $table->foreign('media_tabel_id')->references('id')->on('tabels');
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
        Schema::dropIfExists('media');
    }
};
