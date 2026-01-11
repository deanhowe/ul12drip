<?php

namespace Tests\Feature;

use App\Models\ExhaustiveFeature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExhaustiveFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the exhaustive features table exists and has all expected columns.
     */
    public function test_exhaustive_features_table_has_all_columns(): void
    {
        $columns = Schema::getColumnListing('exhaustive_features');

        $this->assertContains('id', $columns);
        $this->assertContains('string_col', $columns);
        $this->assertContains('integer_col', $columns);
        $this->assertContains('boolean_col', $columns);
        $this->assertContains('decimal_col', $columns);
        $this->assertContains('json_col', $columns);
        $this->assertContains('uuid_col', $columns);
        $this->assertContains('ulid_col', $columns);
        $this->assertContains('virtual_col', $columns);
        $this->assertContains('stored_col', $columns);
    }

    /**
     * Test that we can create an exhaustive feature model.
     */
    public function test_can_create_exhaustive_feature_model(): void
    {
        $user = User::factory()->create();

        $feature = ExhaustiveFeature::create([
            'user_id' => $user->id,
            'string_col' => 'Test String',
            'integer_col' => 123,
            'boolean_col' => true,
            'decimal_col' => 99.99,
            'unsigned_decimal_col' => 50.00,
            'json_col' => ['foo' => 'bar'],
            'uuid_col' => (string) \Illuminate\Support\Str::uuid(),
            'ulid_col' => (string) \Illuminate\Support\Str::ulid(),
            'date_col' => now()->toDateString(),
            'date_time_col' => now(),
            'time_col' => '12:00:00',
            'timestamp_col' => now(),
            'year_col' => 2026,
            'enum_col' => 'active',
            'ip_address_col' => '127.0.0.1',
            'mac_address_col' => '00:00:00:00:00:00',
            'char_col' => 'ABCD',
            'imageable_type' => 'App\Models\User',
            'imageable_id' => $user->id,
            'taggable_uuid_type' => 'App\Models\User',
            'taggable_uuid_id' => (string) \Illuminate\Support\Str::uuid(),
            'taggable_ulid_type' => 'App\Models\User',
            'taggable_ulid_id' => (string) \Illuminate\Support\Str::ulid(),
        ]);

        $this->assertDatabaseHas('exhaustive_features', [
            'id' => $feature->id,
            'string_col' => 'Test String',
        ]);
    }

    /**
     * Test core framework features demonstration.
     */
    public function test_demonstrate_core_features_works(): void
    {
        $response = $this->getJson('/api/demonstrate-core');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'benchmark',
            'hashing',
            'encryption',
            'session',
            'cache',
            'cookie',
            'transaction',
        ]);
        $this->assertTrue($response->json('hashing'));
        $this->assertEquals('Secret message', $response->json('encryption'));
    }

    /**
     * Test the exhaustive folio page loads.
     */
    public function test_exhaustive_folio_page_loads(): void
    {
        $response = $this->get('/exhaustive');

        $response->assertStatus(200);
        $response->assertSee('Exhaustive Database Schema');
        $response->assertSee('Exhaustive Validation Rules');
    }
}
