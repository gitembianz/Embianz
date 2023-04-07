<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('short_description')->nullable(); // using 'text' instead of 'string' to allow for longer descriptions
            $table->text('long_description')->nullable(); // using 'text' instead of 'string' to allow for longer descriptions
            $table->integer('quantity')->nullable(); // assuming this is a whole number
            $table->enum('product_status', ['active', 'inactive','low stock'])->nullable(); // using an enum to restrict the value to a specific set of options
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('created_by')->nullable();
            $table->string('last_modified_by')->nullable();
            $table->timestamps();
            $table->string('seo_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
