<?php

namespace Tests\Feature;

use App\Collections\ProductCollection;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdvancedEloquentTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_scope_published_on_post(): void
    {
        // Post in the past (published)
        Post::factory()->create(['published_at' => now()->subDay()]);
        // Post in the future (scheduled)
        Post::factory()->create(['published_at' => now()->addDay()]);
        // Post with null (draft)
        Post::factory()->create(['published_at' => null]);

        $this->assertEquals(1, Post::count());
        $this->assertEquals(3, Post::withoutGlobalScopes()->count());
    }

    public function test_model_pruning_on_activity_log(): void
    {
        $log = new ActivityLog;

        // Use reflection or just check the query
        $query = $log->prunable();

        $this->assertStringContainsString('"created_at" <=', $query->toSql());
    }

    public function test_advanced_casting_as_array_object_on_product(): void
    {
        $product = Product::factory()->create([
            'metadata' => ['color' => 'red', 'size' => 'large'],
        ]);

        $product->refresh();

        $this->assertInstanceOf(\ArrayObject::class, $product->metadata);
        $this->assertEquals('red', $product->metadata['color']);

        // Test mutation
        $product->metadata['color'] = 'blue';
        $product->save();

        $this->assertEquals('blue', Product::find($product->id)->metadata['color']);
    }

    public function test_encrypted_casting_on_supplier(): void
    {
        $supplier = Supplier::factory()->create([
            'tax_id' => '123-456-789',
        ]);

        $supplier->refresh();

        $this->assertEquals('123-456-789', $supplier->tax_id);

        // Verify it is encrypted in database
        $raw = DB::table('suppliers')->where('id', $supplier->id)->first();
        $this->assertNotEquals('123-456-789', $raw->tax_id);
    }

    public function test_subquery_select_on_user(): void
    {
        $user = User::factory()->create();
        Order::factory()->create(['user_id' => $user->id, 'created_at' => now()->subDays(5)]);
        $lastOrder = Order::factory()->create(['user_id' => $user->id, 'created_at' => now()->subDay()]);

        $userWithLastOrder = User::withLastOrderAt()->find($user->id);

        $this->assertNotNull($userWithLastOrder->last_order_at);
        $this->assertEquals($lastOrder->created_at->toDateTimeString(), $userWithLastOrder->last_order_at->toDateTimeString());
    }

    public function test_custom_collection_on_product(): void
    {
        Product::factory()->create(['price' => 10, 'stock' => 5]);
        Product::factory()->create(['price' => 20, 'stock' => 2]);

        $products = Product::all();

        $this->assertInstanceOf(ProductCollection::class, $products);
        // (10 * 5) + (20 * 2) = 50 + 40 = 90
        $this->assertEquals(90, $products->totalValue());
    }
}
