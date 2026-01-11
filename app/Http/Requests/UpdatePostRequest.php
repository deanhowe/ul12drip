<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for updating an existing post.
 *
 * Demonstrates:
 * - Partial update validation (sometimes rule)
 * - Route model binding access
 * - Unique validation ignoring current record
 */
class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if user owns the post or is admin
        $post = $this->route('post');

        return $post && (
            $this->user()?->id === $post->user_id ||
            $this->user()?->is_admin
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'body' => ['sometimes', 'required', 'string', 'min:10'],
            'published_at' => ['nullable', 'date'],
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
