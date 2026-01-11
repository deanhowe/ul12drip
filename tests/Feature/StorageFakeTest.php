<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Tests demonstrating Storage::fake() usage.
 *
 * Demonstrates:
 * - Storage::fake() for testing file uploads
 * - UploadedFile::fake() for creating test files
 * - Storage assertions (assertExists, assertMissing)
 * - Testing file operations without touching real filesystem
 */
class StorageFakeTest extends TestCase
{
    /**
     * Test that files can be stored using fake storage.
     */
    public function test_files_can_be_stored(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('avatar.jpg', 100, 100);

        $path = $file->store('avatars', 'local');

        Storage::disk('local')->assertExists($path);
    }

    /**
     * Test that files can be deleted using fake storage.
     */
    public function test_files_can_be_deleted(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('document.pdf', 100);
        $path = $file->store('documents', 'local');

        Storage::disk('local')->assertExists($path);

        Storage::disk('local')->delete($path);

        Storage::disk('local')->assertMissing($path);
    }

    /**
     * Test storing files with custom names.
     */
    public function test_files_can_be_stored_with_custom_name(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('photo.png', 200, 200);

        $file->storeAs('photos', 'custom-name.png', 'local');

        Storage::disk('local')->assertExists('photos/custom-name.png');
    }

    /**
     * Test that fake storage is isolated per test.
     */
    public function test_fake_storage_is_isolated(): void
    {
        Storage::fake('local');

        // This test should not see files from other tests
        Storage::disk('local')->assertMissing('avatars/avatar.jpg');
        Storage::disk('local')->assertMissing('documents/document.pdf');
    }

    /**
     * Test storing multiple files.
     */
    public function test_multiple_files_can_be_stored(): void
    {
        Storage::fake('local');

        $files = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
            UploadedFile::fake()->image('image3.jpg'),
        ];

        $paths = [];
        foreach ($files as $file) {
            $paths[] = $file->store('gallery', 'local');
        }

        foreach ($paths as $path) {
            Storage::disk('local')->assertExists($path);
        }

        $this->assertCount(3, Storage::disk('local')->files('gallery'));
    }

    /**
     * Test file size validation with fake files.
     */
    public function test_file_size_can_be_specified(): void
    {
        Storage::fake('local');

        // Create a 500KB file
        $file = UploadedFile::fake()->create('large-file.zip', 500);

        $this->assertEquals(500 * 1024, $file->getSize());

        $path = $file->store('uploads', 'local');

        Storage::disk('local')->assertExists($path);
    }

    /**
     * Test directory operations with fake storage.
     */
    public function test_directories_can_be_created_and_deleted(): void
    {
        Storage::fake('local');

        Storage::disk('local')->makeDirectory('test-directory');

        $this->assertTrue(Storage::disk('local')->exists('test-directory'));

        Storage::disk('local')->deleteDirectory('test-directory');

        $this->assertFalse(Storage::disk('local')->exists('test-directory'));
    }
}
