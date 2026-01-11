<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExhaustiveValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * This request serves as an exhaustive demonstration of ALL available
     * Laravel validation rules.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Booleans
            'accepted_field' => 'accepted',
            'accepted_if_field' => 'accepted_if:other_field,value',
            'boolean_field' => 'boolean',
            'declined_field' => 'declined',
            'declined_if_field' => 'declined_if:other_field,value',

            // Strings
            'active_url_field' => 'active_url',
            'alpha_field' => 'alpha',
            'alpha_dash_field' => 'alpha_dash',
            'alpha_num_field' => 'alpha_num',
            'ascii_field' => 'ascii',
            'confirmed_field' => 'confirmed',
            'current_password_field' => 'current_password',
            'different_field' => 'different:other_field',
            'doesnt_start_with_field' => 'doesnt_start_with:a,b,c',
            'doesnt_end_with_field' => 'doesnt_end_with:x,y,z',
            'email_field' => 'email:rfc,dns',
            'ends_with_field' => 'ends_with:suffix',
            'enum_field' => [\Illuminate\Validation\Rules\Rule::enum(\App\Enums\OrderStatus::class)],
            'hex_color_field' => 'hex_color',
            'in_field' => 'in:first,second',
            'ip_field' => 'ip',
            'ipv4_field' => 'ipv4',
            'ipv6_field' => 'ipv6',
            'json_field' => 'json',
            'lowercase_field' => 'lowercase',
            'mac_address_field' => 'mac_address',
            'max_field' => 'max:255',
            'min_field' => 'min:1',
            'not_in_field' => 'not_in:third,fourth',
            'not_regex_field' => 'not_regex:/^.+$/i',
            'regex_field' => 'regex:/^[a-z]+$/i',
            'same_field' => 'same:other_field',
            'size_field' => 'size:10',
            'starts_with_field' => 'starts_with:prefix',
            'string_field' => 'string',
            'uppercase_field' => 'uppercase',
            'url_field' => 'url',
            'ulid_field' => 'ulid',
            'uuid_field' => 'uuid',

            // Numbers
            'between_field' => 'between:1,10',
            'decimal_field' => 'decimal:2',
            'digits_field' => 'digits:5',
            'digits_between_field' => 'digits_between:1,5',
            'gt_field' => 'gt:other_field',
            'gte_field' => 'gte:other_field',
            'integer_field' => 'integer',
            'lt_field' => 'lt:other_field',
            'lte_field' => 'lte:other_field',
            'max_digits_field' => 'max_digits:10',
            'min_digits_field' => 'min_digits:1',
            'multiple_of_field' => 'multiple_of:2',
            'numeric_field' => 'numeric',

            // Arrays
            'array_field' => 'array:key1,key2',
            'contains_field' => 'contains:value1,value2',
            'doesnt_contain_field' => 'doesnt_contain:value3',
            'distinct_field' => 'distinct:strict',
            'in_array_field' => 'in_array:other_array.*',
            'list_field' => 'list',
            'required_array_keys_field' => 'required_array_keys:foo,bar',

            // Dates
            'after_field' => 'after:tomorrow',
            'after_or_equal_field' => 'after_or_equal:today',
            'before_field' => 'before:tomorrow',
            'before_or_equal_field' => 'before_or_equal:today',
            'date_field' => 'date',
            'date_equals_field' => 'date_equals:2026-01-10',
            'date_format_field' => 'date_format:Y-m-d',
            'timezone_field' => 'timezone:Africa,America,Antarctica,Asia,Atlantic,Australia,Europe,Indian,Pacific',

            // Files
            'dimensions_field' => 'dimensions:min_width=100,min_height=200',
            'file_field' => 'file',
            'image_field' => 'image',
            'mimetypes_field' => 'mimetypes:video/avi,video/mpeg,video/quicktime',
            'mimes_field' => 'mimes:jpg,bmp,png',
            'extensions_field' => 'extensions:jpg,png',

            // Database
            'exists_field' => 'exists:users,id',
            'unique_field' => 'unique:users,email',

            // Utilities
            'any_of_field' => 'any_of:field1,field2',
            'nullable_field' => 'nullable',
            'present_field' => 'present',
            'required_field' => 'required',
            'required_if_field' => 'required_if:other_field,value',
            'required_unless_field' => 'required_unless:other_field,value',
            'required_with_field' => 'required_with:foo,bar',
            'required_with_all_field' => 'required_with_all:foo,bar',
            'required_without_field' => 'required_without:foo,bar',
            'required_without_all_field' => 'required_without_all:foo,bar',
            'prohibited_field' => 'prohibited',
            'prohibited_if_field' => 'prohibited_if:other_field,value',
            'prohibited_unless_field' => 'prohibited_unless:other_field,value',
            'prohibits_field' => 'prohibits:other_field',
            'missing_field' => 'missing',
            'missing_if_field' => 'missing_if:other_field,value',
            'missing_unless_field' => 'missing_unless:other_field,value',
            'missing_with_field' => 'missing_with:foo',
            'missing_with_all_field' => 'missing_with_all:foo,bar',
        ];
    }
}
