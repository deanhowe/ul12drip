<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for subscription billing plans.
 *
 * Creates the plans table to store subscription plan information
 * that integrates with Laravel Cashier and Stripe.
 *
 * Each plan corresponds to a Stripe Price object and contains
 * metadata for display and subscription management.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            // Plan identification
            $table->string('name');                          // Display name (e.g., "Pro Monthly")
            $table->string('slug')->unique();                // URL-friendly identifier (e.g., "pro-monthly")
            $table->string('stripe_price_id')->unique();     // Stripe Price ID (e.g., "price_1234...")

            // Pricing information (stored in cents for precision)
            $table->unsignedInteger('price');                // Price in cents (e.g., 999 = $9.99)
            $table->string('interval');                      // Billing interval: day, week, month, year
            $table->unsignedTinyInteger('interval_count')->default(1); // Number of intervals between billings

            // Plan details
            $table->text('description')->nullable();         // Plan description for display
            $table->json('features')->nullable();            // Array of feature strings

            // Plan status and display
            $table->boolean('is_active')->default(true);     // Whether plan is available for purchase
            $table->boolean('is_featured')->default(false);  // Highlight this plan in UI
            $table->unsignedInteger('sort_order')->default(0); // Display order

            $table->timestamps();

            // Indexes for common queries
            $table->index(['is_active', 'sort_order']);
            $table->index('interval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
