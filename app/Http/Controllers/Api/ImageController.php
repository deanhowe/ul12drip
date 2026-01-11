<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');

            return response()->json([
                'message' => 'Image uploaded successfully.',
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
            ], 201);
        }

        return response()->json(['message' => 'Image upload failed.'], 400);
    }
}
