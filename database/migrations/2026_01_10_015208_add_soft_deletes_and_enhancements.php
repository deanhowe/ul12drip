<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add soft deletes and enhancements to users table
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('suspended_at')->nullable()->after('remember_token');
            $table->boolean('is_premium')->default(false)->after('suspended_at');
            $table->boolean('is_admin')->default(false)->after('is_premium');
        });

        // Add soft deletes and published_at to posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('published_at')->nullable()->after('body');
        });

        // Add soft deletes to products table
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
        });

        // Add soft deletes and status_changed_at to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('status_changed_at')->nullable()->after('status');
        });

        // Make comments polymorphic (add commentable columns, add user_id)
        Schema::table('comments', function (Blueprint $table) {
            // Add polymorphic columns
            $table->string('commentable_type')->after('id');
            $table->unsignedBigInteger('commentable_id')->after('commentable_type');
            $table->index(['commentable_type', 'commentable_id']);

            // Add user_id for comment author
            $table->foreignId('user_id')->nullable()->after('commentable_id')->constrained()->nullOnDelete();

            // Make post_id nullable (for backward compatibility during migration)
            $table->unsignedBigInteger('post_id')->nullable()->change();
        });

        // Migrate existing comments to polymorphic structure
        DB::table('comments')->whereNotNull('post_id')->update([
            'commentable_type' => 'App\\Models\\Post',
            'commentable_id' => DB::raw('post_id'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['commentable_type', 'commentable_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['commentable_type', 'commentable_id', 'user_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('status_changed_at');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('sale_price');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('published_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['suspended_at', 'is_premium', 'is_admin']);
        });
    }
};
