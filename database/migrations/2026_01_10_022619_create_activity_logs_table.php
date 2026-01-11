<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     * Uses a separate SQLite database for activity logs.
     */
    protected $connection = 'activity_log';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop if exists to handle re-runs during testing
        Schema::connection('activity_log')->dropIfExists('activity_logs');

        Schema::connection('activity_log')->create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Who performed the action
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type')->nullable(); // For polymorphic user types

            // What was affected (polymorphic)
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');

            // The action performed
            $table->string('event'); // created, updated, deleted, restored, etc.
            $table->string('description')->nullable();

            // Changes made (JSON)
            $table->json('properties')->nullable(); // old/new values
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Context
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();

            // Batch ID for grouping related activities
            $table->uuid('batch_uuid')->nullable();

            $table->timestamps();

            // Indexes for common queries
            $table->index(['subject_type', 'subject_id']);
            $table->index('user_id');
            $table->index('event');
            $table->index('created_at');
            $table->index('batch_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('activity_log')->dropIfExists('activity_logs');
    }
};
