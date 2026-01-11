<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for creating a new post.
 *
 * Demonstrates:
 * - Validation rules with array syntax
 * - Custom error messages
 * - Custom attribute names
 * - Authorization logic
 */
class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or check user permissions
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
            'published_at' => ['nullable', 'date', 'after_or_equal:today'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'A post title is required.',
            'title.max' => 'The post title cannot exceed 255 characters.',
            'body.required' => 'Post content is required.',
            'body.min' => 'Post content must be at least 10 characters.',
            'published_at.after_or_equal' => 'The publish date cannot be in the past.',
            'tags.*.exists' => 'One or more selected tags are invalid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'post title',
            'body' => 'post content',
            'published_at' => 'publish date',
        ];
    }
}
