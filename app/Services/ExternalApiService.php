<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternalApiService
{
    /**
     * Fetch dummy data from an external API.
     */
    public function getExternalData(): array
    {
        // Using JSONPlaceholder as a dummy API
        $response = Http::get('https://jsonplaceholder.typicode.com/posts/1');

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    /**
     * Post data to an external API.
     */
    public function postExternalData(array $data): array
    {
        $response = Http::post('https://jsonplaceholder.typicode.com/posts', $data);

        return $response->json();
    }
}
