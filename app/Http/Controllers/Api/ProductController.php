<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for Product resources.
 *
 * Demonstrates:
 * - API Resource responses
 * - Resource Collections with pagination
 * - Form Request validation
 * - Multiple query scope filters
 * - Eager loading relationships
 */
class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * GET /api/products
     * GET /api/products?filter=on_sale
     * GET /api/products?filter=in_stock
     */
    public function index(Request $request): ProductCollection
    {
        $query = Product::query()
            ->with(['categories', 'tags'])
            ->withCount('comments');

        // Filter by status
        if ($request->has('filter')) {
            match ($request->filter) {
                'active' => $query->active(),
                'inactive' => $query->inactive(),
                'in_stock' => $query->inStock(),
                'out_of_stock' => $query->outOfStock(),
                'on_sale' => $query->onSale(),
                default => null,
            };
        }

        // Sort options
        $sortBy = $request->input('sort', 'created_at');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->input('per_page', 15);

        return new ProductCollection($query->paginate($perPage));
    }

    /**
     * Store a newly created product.
     *
     * POST /api/products
     */
    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'sku' => $request->sku,
            'stock' => $request->stock,
            'active' => $request->active ?? true,
        ]);

        // Sync categories if provided
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        // Sync tags if provided
        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }

        $product->load(['categories', 'tags']);

        return new ProductResource($product);
    }

    /**
     * Display the specified product.
     *
     * GET /api/products/{product}
     */
    public function show(Product $product): ProductResource
    {
        $product->load(['categories', 'tags', 'images', 'comments.user']);
        $product->loadCount('comments');

        return new ProductResource($product);
    }

    /**
     * Update the specified product.
     *
     * PUT/PATCH /api/products/{product}
     */
    public function update(Request $request, Product $product): ProductResource
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0.01'],
            'sale_price' => ['nullable', 'numeric', 'min:0.01', 'lt:price'],
            'sku' => ['sometimes', 'string', 'unique:products,sku,'.$product->id],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'active' => ['sometimes', 'boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'array'],
        ]);

        $product->update($validated);

        // Sync categories if provided
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        // Sync tags if provided
        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }

        $product->load(['categories', 'tags']);

        return new ProductResource($product);
    }

    /**
     * Remove the specified product.
     *
     * DELETE /api/products/{product}
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }
}
