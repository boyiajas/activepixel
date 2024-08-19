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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Payment method name (e.g., PayPal, Credit Card)
            $table->string('slug')->unique(); // Unique identifier (e.g., 'paypal', 'credit_card')
            $table->string('description')->nullable(); // Description of the payment method
            $table->boolean('is_active')->default(true); // Whether the payment method is active
            $table->json('settings')->nullable(); // Store specific settings for each payment method
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
