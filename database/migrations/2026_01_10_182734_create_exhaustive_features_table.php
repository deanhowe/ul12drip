<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration serves as an exhaustive demonstration of ALL available
     * Laravel schema builder column types and modifiers.
     */
    public function up(): void
    {
        Schema::create('exhaustive_features', function (Blueprint $blueprint) {
            // Numeric Types
            $blueprint->id();
            // Note: Increments are typically primary keys, so we use them sparingly here
            $blueprint->bigInteger('big_integer_col')->nullable();
            $blueprint->decimal('decimal_col', 8, 2)->nullable();
            $blueprint->double('double_col')->nullable();
            $blueprint->float('float_col')->nullable();
            $blueprint->integer('integer_col')->nullable();
            $blueprint->mediumInteger('medium_integer_col')->nullable();
            $blueprint->smallInteger('small_integer_col')->nullable();
            $blueprint->tinyInteger('tiny_integer_col')->nullable();
            $blueprint->unsignedBigInteger('unsigned_big_integer_col')->nullable();
            $blueprint->decimal('unsigned_decimal_col', 8, 2)->unsigned()->nullable();
            $blueprint->unsignedInteger('unsigned_integer_col')->nullable();
            $blueprint->unsignedMediumInteger('unsigned_medium_integer_col')->nullable();
            $blueprint->unsignedSmallInteger('unsigned_small_integer_col')->nullable();
            $blueprint->unsignedTinyInteger('unsigned_tiny_integer_col')->nullable();

            // String & Text Types
            $blueprint->char('char_col', length: 4)->nullable();
            $blueprint->longText('long_text_col')->nullable();
            $blueprint->mediumText('medium_text_col')->nullable();
            $blueprint->string('string_col', length: 100)->nullable();
            $blueprint->text('text_col')->nullable();
            $blueprint->text('base64_col')->nullable();
            $blueprint->tinyText('tiny_text_col')->nullable();

            // Date & Time Types
            $blueprint->date('date_col')->nullable();
            $blueprint->dateTime('date_time_col')->nullable();
            $blueprint->dateTimeTz('date_time_tz_col')->nullable();
            $blueprint->time('time_col')->nullable();
            $blueprint->timeTz('time_tz_col')->nullable();
            $blueprint->timestamp('timestamp_col')->nullable();
            $blueprint->timestampTz('timestamp_tz_col')->nullable();
            $blueprint->timestamps();
            // $blueprint->timestampsTz(); // Would create duplicate columns
            $blueprint->softDeletes();
            // $blueprint->softDeletesTz(); // Would create duplicate columns
            $blueprint->year('year_col')->nullable();

            // Specialty Types
            $blueprint->boolean('boolean_col')->nullable();
            $blueprint->enum('enum_col', ['active', 'inactive'])->nullable();
            if (DB::getDriverName() !== 'sqlite') {
                $blueprint->set('set_col', ['a', 'b', 'c'])->nullable();
            }
            $blueprint->json('json_col')->nullable();
            $blueprint->jsonb('jsonb_col')->nullable();
            $blueprint->ipAddress('ip_address_col')->nullable();
            $blueprint->macAddress('mac_address_col')->nullable();
            $blueprint->uuid('uuid_col')->nullable();
            $blueprint->ulid('ulid_col')->nullable();
            $blueprint->rememberToken();
            if (DB::getDriverName() !== 'sqlite') {
                $blueprint->vector('vector_col', dimensions: 3)->nullable();
            }

            // Relationship Types
            $blueprint->foreignId('user_id')->constrained();
            $blueprint->nullableMorphs('imageable');
            $blueprint->nullableMorphs('commentable');
            $blueprint->nullableMorphs('taggable_uuid');
            $blueprint->nullableMorphs('taggable_ulid');

            // Column Modifiers
            $blueprint->string('nullable_col')->nullable();
            $blueprint->string('default_col')->default('default_value');
            $blueprint->integer('unsigned_col')->unsigned()->nullable();
            $blueprint->string('comment_col')->comment('This is a comment')->nullable();
            $blueprint->string('charset_col')->charset('utf8mb4')->nullable();
            if (DB::getDriverName() !== 'sqlite') {
                $blueprint->string('collation_col')->collation('utf8mb4_unicode_ci')->nullable();
            } else {
                $blueprint->string('collation_col')->nullable();
            }
            $blueprint->timestamp('use_current_col')->useCurrent();
            $blueprint->timestamp('use_current_on_update_col')->nullable()->useCurrentOnUpdate();

            // Generated Columns (Modifiers)
            $blueprint->integer('val1')->default(1);
            $blueprint->integer('val2')->default(2);
            $blueprint->integer('virtual_col')->virtualAs('val1 + val2')->nullable();
            $blueprint->integer('stored_col')->storedAs('val1 * val2')->nullable();

            // Indexes
            $blueprint->index(['string_col', 'integer_col'], 'composite_index');
            $blueprint->unique('uuid_col', 'unique_uuid');
            if (DB::getDriverName() !== 'sqlite') {
                $blueprint->fullText('text_col');
            }
        });

        // Separate tables to demonstrate increment types (SQLite primary key limitation)
        Schema::create('exhaustive_increments', function (Blueprint $table) {
            $table->tinyIncrements('tiny_id');
        });
        Schema::create('exhaustive_medium_increments', function (Blueprint $table) {
            $table->mediumIncrements('medium_id');
        });
        Schema::create('exhaustive_small_increments', function (Blueprint $table) {
            $table->smallIncrements('small_id');
        });
        Schema::create('exhaustive_big_increments', function (Blueprint $table) {
            $table->bigIncrements('big_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhaustive_features');
    }
};
