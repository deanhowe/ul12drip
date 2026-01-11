<?php

use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ExhaustiveFeatureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/demonstrate-core', [ExhaustiveFeatureController::class, 'demonstrateCoreFeatures'])->name('api.demonstrate-core');
Route::post('/exhaustive-validate', [ExhaustiveFeatureController::class, 'validateRequest'])->name('api.exhaustive-validate');

Route::post('/images', [ImageController::class, 'store'])->name('api.images.store');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
| Demonstrates:
| - API Resource Routes
| - Rate Limiting
| - Route Groups
| - Authentication middleware
|
*/

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
|
| These routes are publicly accessible without authentication.
| Rate limited to 60 requests per minute.
|
*/
Route::middleware(['throttle:api'])->group(function () {
    // Public post listing and viewing
    Route::get('posts', [PostController::class, 'index'])->name('api.posts.index');
    Route::get('posts/{post}', [PostController::class, 'show'])->name('api.posts.show');

    // Public product listing and viewing
    Route::get('products', [ProductController::class, 'index'])->name('api.products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('api.products.show');
});

/*
|--------------------------------------------------------------------------
| Authenticated API Routes
|--------------------------------------------------------------------------
|
| These routes require authentication via Sanctum.
| Rate limited to 60 requests per minute.
|
*/
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Current user
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');

    // Post management (create, update, delete)
    Route::post('posts', [PostController::class, 'store'])->name('api.posts.store');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('api.posts.update');
    Route::patch('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('api.posts.destroy');

    // Product management (create, update, delete)
    Route::post('products', [ProductController::class, 'store'])->name('api.products.store');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('api.products.update');
    Route::patch('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('api.products.destroy');

    // User management (admin only in real app)
    Route::apiResource('users', UserController::class)->names([
        'index' => 'api.users.index',
        'store' => 'api.users.store',
        'show' => 'api.users.show',
        'update' => 'api.users.update',
        'destroy' => 'api.users.destroy',
    ]);
});

/*
|--------------------------------------------------------------------------
| Rate Limited Examples
|--------------------------------------------------------------------------
|
| Demonstrates different rate limiting strategies.
|
*/

// Strict rate limit for sensitive operations (10 per minute)
Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
    Route::post('users/{user}/suspend', function (Request $request, \App\Models\User $user) {
        $user->update(['suspended_at' => now()]);

        return response()->json(['message' => 'User suspended']);
    })->name('api.users.suspend');
});

// Higher rate limit for read-heavy operations (120 per minute)
Route::middleware(['throttle:120,1'])->group(function () {
    Route::get('search', function (Request $request) {
        $query = $request->input('q');

        return response()->json([
            'posts' => \App\Models\Post::where('title', 'like', "%{$query}%")->limit(10)->get(),
            'products' => \App\Models\Product::where('name', 'like', "%{$query}%")->limit(10)->get(),
        ]);
    })->name('api.search');
});
