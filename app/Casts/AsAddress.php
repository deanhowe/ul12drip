<?php

namespace App\Casts;

use App\ValueObjects\Address;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Custom cast for the Address Value Object.
 *
 * Maps multiple database columns to a single Address object.
 */
class AsAddress implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Address
    {
        return new Address(
            $attributes['address_line_one'] ?? '',
            $attributes['address_line_two'] ?? '',
            $attributes['address_city'] ?? '',
            $attributes['address_state'] ?? '',
            $attributes['address_postal_code'] ?? '',
            $attributes['address_country'] ?? ''
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, string>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof Address) {
            throw new InvalidArgumentException('The given value is not an Address instance.');
        }

        return [
            'address_line_one' => $value->lineOne,
            'address_line_two' => $value->lineTwo,
            'address_city' => $value->city,
            'address_state' => $value->state,
            'address_postal_code' => $value->postalCode,
            'address_country' => $value->country,
        ];
    }
}
