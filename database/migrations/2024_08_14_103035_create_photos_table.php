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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('race_number');
            $table->decimal('price', 8, 2);
            $table->enum('stock_status', ['in_stock', 'out_of_stock']);
            $table->boolean('downloadable')->default(false);
            $table->date('update_date')->nullable();
            $table->date('published_date')->nullable();
            //$table->enum('photo_type', ['regular', 'lead_image']);
            $table->unsignedBigInteger('event_id')->nullable(); // Ensure this is unsignedBigInteger
            //$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable(); // Ensure this is unsignedBigInteger
            //$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
