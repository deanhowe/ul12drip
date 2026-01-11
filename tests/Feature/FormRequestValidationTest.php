<?php

namespace Tests\Feature;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * Tests for form request validation.
 *
 * Demonstrates:
 * - Validation rules testing
 * - Custom error messages
 * - Conditional validation
 * - Enum validation
 */
class FormRequestValidationTest extends TestCase
{
    use RefreshDatabase;

    // StorePostRequest Tests

    public function test_store_post_request_requires_title(): void
    {
        $request = new StorePostRequest;
        $validator = Validator::make(
            ['body' => 'This is the post body content.'],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_store_post_request_requires_body(): void
    {
        $request = new StorePostRequest;
        $validator = Validator::make(
            ['title' => 'Test Post Title'],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('body', $validator->errors()->toArray());
    }

    public function test_store_post_request_body_must_be_at_least_10_characters(): void
    {
        $request = new StorePostRequest;
        $validator = Validator::make(
            ['title' => 'Test Post', 'body' => 'Short'],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('body', $validator->errors()->toArray());
    }

    public function test_store_post_request_title_max_255_characters(): void
    {
        $request = new StorePostRequest;
        $validator = Validator::make(
            ['title' => str_repeat('a', 256), 'body' => 'This is valid body content.'],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_store_post_request_published_at_must_be_valid_date(): void
    {
        $request = new StorePostRequest;
        $validator = Validator::make(
            ['title' => 'Test Post', 'body' => 'This is valid body content.', 'published_at' => 'not-a-date'],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('published_at', $validator->errors()->toArray());
    }

    public function test_store_post_request_tags_must_exist(): void
    {
        $request = new StorePostRequest;
        $validator = Validator::make(
            ['title' => 'Test Post', 'body' => 'This is valid body content.', 'tags' => [999]],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('tags.0', $validator->errors()->toArray());
    }

    public function test_store_post_request_passes_with_valid_data(): void
    {
        $tag = Tag::factory()->create();

        $request = new StorePostRequest;
        $validator = Validator::make(
            [
                'title' => 'Valid Post Title',
                'body' => 'This is a valid post body with more than 10 characters.',
                'published_at' => now()->addDay()->toDateString(),
                'tags' => [$tag->id],
            ],
            $request->rules()
        );

        $this->assertFalse($validator->fails());
    }

    // StoreOrderRequest Tests

    public function test_store_order_request_requires_user_id(): void
    {
        $request = new StoreOrderRequest;
        $validator = Validator::make(
            ['total' => 100.00, 'items' => [['product_id' => 1, 'quantity' => 1]]],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('user_id', $validator->errors()->toArray());
    }

    public function test_store_order_request_user_must_exist(): void
    {
        $request = new StoreOrderRequest;
        $validator = Validator::make(
            ['user_id' => 999, 'total' => 100.00, 'items' => [['product_id' => 1, 'quantity' => 1]]],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('user_id', $validator->errors()->toArray());
    }

    public function test_store_order_request_total_must_be_non_negative(): void
    {
        $user = User::factory()->create();

        $request = new StoreOrderRequest;
        $validator = Validator::make(
            ['user_id' => $user->id, 'total' => -10.00, 'items' => [['product_id' => 1, 'quantity' => 1]]],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('total', $validator->errors()->toArray());
    }

    public function test_store_order_request_requires_at_least_one_item(): void
    {
        $user = User::factory()->create();

        $request = new StoreOrderRequest;
        $validator = Validator::make(
            ['user_id' => $user->id, 'total' => 100.00, 'items' => []],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('items', $validator->errors()->toArray());
    }

    public function test_store_order_request_item_quantity_must_be_at_least_one(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $request = new StoreOrderRequest;
        $validator = Validator::make(
            ['user_id' => $user->id, 'total' => 100.00, 'items' => [['product_id' => $product->id, 'quantity' => 0]]],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('items.0.quantity', $validator->errors()->toArray());
    }

    public function test_store_order_request_passes_with_valid_data(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $request = new StoreOrderRequest;
        $validator = Validator::make(
            [
                'user_id' => $user->id,
                'total' => 99.99,
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 2],
                ],
            ],
            $request->rules()
        );

        $this->assertFalse($validator->fails());
    }

    // StoreProductRequest Tests

    public function test_store_product_request_requires_name(): void
    {
        $request = new StoreProductRequest;
        $validator = Validator::make(
            ['price' => 19.99, 'sku' => 'TEST-001', 'stock' => 10],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    public function test_store_product_request_requires_price(): void
    {
        $request = new StoreProductRequest;
        $validator = Validator::make(
            ['name' => 'Test Product', 'sku' => 'TEST-001', 'stock' => 10],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('price', $validator->errors()->toArray());
    }

    public function test_store_product_request_price_must_be_positive(): void
    {
        $request = new StoreProductRequest;
        $validator = Validator::make(
            ['name' => 'Test Product', 'price' => 0, 'sku' => 'TEST-001', 'stock' => 10],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('price', $validator->errors()->toArray());
    }

    public function test_store_product_request_sale_price_must_be_less_than_price(): void
    {
        $request = new StoreProductRequest;
        $validator = Validator::make(
            ['name' => 'Test Product', 'price' => 19.99, 'sale_price' => 29.99, 'sku' => 'TEST-001', 'stock' => 10],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('sale_price', $validator->errors()->toArray());
    }

    public function test_store_product_request_sku_must_be_unique(): void
    {
        Product::factory()->create(['sku' => 'EXISTING-SKU']);

        $request = new StoreProductRequest;
        $validator = Validator::make(
            ['name' => 'Test Product', 'price' => 19.99, 'sku' => 'EXISTING-SKU', 'stock' => 10],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('sku', $validator->errors()->toArray());
    }

    public function test_store_product_request_stock_cannot_be_negative(): void
    {
        $request = new StoreProductRequest;
        $validator = Validator::make(
            ['name' => 'Test Product', 'price' => 19.99, 'sku' => 'TEST-001', 'stock' => -5],
            $request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('stock', $validator->errors()->toArray());
    }

    public function test_store_product_request_passes_with_valid_data(): void
    {
        $request = new StoreProductRequest;
        $validator = Validator::make(
            [
                'name' => 'Valid Product',
                'price' => 29.99,
                'sale_price' => 19.99,
                'sku' => 'VALID-SKU-001',
                'stock' => 100,
                'active' => true,
            ],
            $request->rules()
        );

        $this->assertFalse($validator->fails());
    }

    // Custom Error Messages Tests

    public function test_store_post_request_has_custom_error_messages(): void
    {
        $request = new StorePostRequest;
        $messages = $request->messages();

        $this->assertArrayHasKey('title.required', $messages);
        $this->assertArrayHasKey('body.required', $messages);
        $this->assertArrayHasKey('body.min', $messages);
    }

    public function test_store_order_request_has_custom_error_messages(): void
    {
        $request = new StoreOrderRequest;
        $messages = $request->messages();

        $this->assertArrayHasKey('user_id.required', $messages);
        $this->assertArrayHasKey('items.required', $messages);
    }

    public function test_store_product_request_has_custom_error_messages(): void
    {
        $request = new StoreProductRequest;
        $messages = $request->messages();

        $this->assertArrayHasKey('name.required', $messages);
        $this->assertArrayHasKey('sku.unique', $messages);
        $this->assertArrayHasKey('sale_price.lt', $messages);
    }
}
