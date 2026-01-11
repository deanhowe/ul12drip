<?php

namespace App\ValueObjects;

use App\Casts\AsAddress;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Address Value Object.
 *
 * Demonstrates:
 * - Value Object pattern
 * - Eloquent Castable interface
 * - Arrayable and JsonSerializable for model serialization
 */
class Address implements Arrayable, Castable, JsonSerializable
{
    /**
     * Create a new Address instance.
     */
    public function __construct(
        public string $lineOne,
        public string $lineTwo,
        public string $city,
        public string $state,
        public string $postalCode,
        public string $country
    ) {}

    /**
     * Get the name of the caster class to use when casting from / to this cast target.
     *
     * @param  array<string, mixed>  $arguments
     */
    public static function castUsing(array $arguments): string
    {
        return AsAddress::class;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'line_one' => $this->lineOne,
            'line_two' => $this->lineTwo,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
        ];
    }

    /**
     * Convert the object to a string for display.
     */
    public function __toString(): string
    {
        $parts = array_filter([
            $this->lineOne,
            $this->lineTwo,
            $this->city,
            $this->state,
            $this->postalCode,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Specify data which should be serialized to JSON.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
