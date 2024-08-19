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
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('photo_id')->nullable();
            $table->enum('photo_type', ['avatar', 'lead_image', 'regular', 'misc']); // Type of the photo
            $table->string('file_name'); // Original file name
            $table->string('file_path'); // Full file path
            $table->string('extension', 10); // File extension
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
