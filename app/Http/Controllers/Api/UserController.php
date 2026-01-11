<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * API Controller for User resources.
 *
 * Demonstrates:
 * - API Resource responses
 * - Resource Collections with pagination
 * - Form Request validation
 * - Query scopes filtering
 * - Eager loading relationships
 */
class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * GET /api/users
     * GET /api/users?filter=premium
     * GET /api/users?filter=admin
     */
    public function index(Request $request): UserCollection
    {
        $query = User::query()
            ->with(['roles'])
            ->withCount(['posts', 'orders']);

        // Filter by type
        if ($request->has('filter')) {
            match ($request->filter) {
                'premium' => $query->premium(),
                'admin' => $query->admins(),
                'verified' => $query->verified(),
                'suspended' => $query->suspended(),
                'active' => $query->active(),
                default => null,
            };
        }

        $perPage = $request->input('per_page', 15);

        return new UserCollection($query->paginate($perPage));
    }

    /**
     * Store a newly created user.
     *
     * POST /api/users
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_premium' => $request->is_premium ?? false,
        ]);

        // Sync roles if provided
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        $user->load('roles');

        return new UserResource($user);
    }

    /**
     * Display the specified user.
     *
     * GET /api/users/{user}
     */
    public function show(User $user): UserResource
    {
        $user->load(['posts', 'orders', 'roles', 'phone', 'address']);
        $user->loadCount(['posts', 'orders']);

        return new UserResource($user);
    }

    /**
     * Update the specified user.
     *
     * PUT/PATCH /api/users/{user}
     */
    public function update(Request $request, User $user): UserResource
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,'.$user->id],
            'is_premium' => ['sometimes', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->update($validated);

        // Sync roles if provided
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        $user->load('roles');

        return new UserResource($user);
    }

    /**
     * Remove the specified user.
     *
     * DELETE /api/users/{user}
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
