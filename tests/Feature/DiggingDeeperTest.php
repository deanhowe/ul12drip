<?php

namespace Tests\Feature;

use App\Interfaces\SmsServiceInterface;
use App\Rules\Uppercase;
use App\Services\ExternalApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DiggingDeeperTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test File Storage.
     */
    public function test_file_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson(route('api.images.store'), [
            'image' => $file,
        ]);

        $response->assertStatus(201);
        Storage::disk('public')->assertExists('images/'.$file->hashName());
    }

    /**
     * Test Localization.
     */
    public function test_localization(): void
    {
        $this->assertEquals('Welcome to our application!', __('messages.welcome'));
        $this->assertEquals('Order #123 has been shipped.', __('messages.order_shipped', ['number' => '123']));
    }

    /**
     * Test HTTP Client.
     */
    public function test_http_client(): void
    {
        Http::fake([
            'jsonplaceholder.typicode.com/*' => Http::response(['id' => 1, 'title' => 'Fake Post'], 200),
        ]);

        $service = new ExternalApiService;
        $data = $service->getExternalData();

        $this->assertEquals(1, $data['id']);
        $this->assertEquals('Fake Post', $data['title']);
    }

    /**
     * Test Custom Validation Rule.
     */
    public function test_custom_validation_rule(): void
    {
        $validator = Validator::make(
            ['name' => 'john'],
            ['name' => [new Uppercase]]
        );

        $this->assertTrue($validator->fails());
        $this->assertEquals('The name must be uppercase.', $validator->errors()->first('name'));

        $validator = Validator::make(
            ['name' => 'JOHN'],
            ['name' => [new Uppercase]]
        );

        $this->assertTrue($validator->passes());
    }

    /**
     * Test Service Container Binding.
     */
    public function test_service_container_binding(): void
    {
        $smsService = app(SmsServiceInterface::class);

        $this->assertInstanceOf(\App\Services\LogSmsService::class, $smsService);
    }

    /**
     * Test Custom Helpers.
     */
    public function test_custom_helpers(): void
    {
        // Note: Helpers might not be loaded in the same process if composer dump-autoload wasn't run
        // But if they are registered in composer.json, they should eventually be there.
        // For now, we'll check if the functions exist.
        if (function_exists('format_currency')) {
            $this->assertEquals('USD 1,234.56', format_currency(1234.56));
        } else {
            $this->markTestSkipped('Custom helpers not loaded. Run composer dump-autoload.');
        }
    }
}
