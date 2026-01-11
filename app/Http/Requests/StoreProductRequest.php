<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for creating a new product.
 *
 * Demonstrates:
 * - Unique validation for SKU
 * - Price comparison validation (sale_price < price)
 * - Boolean validation
 * - Category relationship validation
 */
class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0.01', 'decimal:0,2'],
            'sale_price' => ['nullable', 'numeric', 'min:0.01', 'decimal:0,2', 'lt:price'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'stock' => ['required', 'integer', 'min:0'],
            'active' => ['sometimes', 'boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
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
            'name.required' => 'A product name is required.',
            'price.required' => 'A product price is required.',
            'price.min' => 'The price must be at least $0.01.',
            'sale_price.lt' => 'The sale price must be less than the regular price.',
            'sku.required' => 'A SKU is required.',
            'sku.unique' => 'This SKU is already in use.',
            'stock.min' => 'Stock cannot be negative.',
            'categories.*.exists' => 'One or more selected categories are invalid.',
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
            'sku' => 'SKU',
            'sale_price' => 'sale price',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure active defaults to true if not provided
        if (! $this->has('active')) {
            $this->merge(['active' => true]);
        }
    }
}
