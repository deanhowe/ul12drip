<?php

namespace App\Traits;

use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait for models that can have addresses.
 *
 * Apply this trait to any model that should support polymorphic addresses.
 *
 * Usage:
 *   class User extends Model
 *   {
 *       use HasAddresses;
 *   }
 *
 *   $user->addresses;
 *   $user->primaryAddress;
 *   $user->billingAddress;
 *   $user->shippingAddress;
 */
trait HasAddresses
{
    /**
     * Get all of the model's addresses.
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get the primary address.
     */
    public function getPrimaryAddressAttribute(): ?Address
    {
        return $this->addresses()->where('type', 'primary')->first()
            ?? $this->addresses()->first();
    }

    /**
     * Get the billing address.
     */
    public function getBillingAddressAttribute(): ?Address
    {
        return $this->addresses()->where('type', 'billing')->first();
    }

    /**
     * Get the shipping address.
     */
    public function getShippingAddressAttribute(): ?Address
    {
        return $this->addresses()->where('type', 'shipping')->first();
    }

    /**
     * Add an address to the model.
     *
     * @param  array<string, mixed>  $data
     */
    public function addAddress(array $data): Address
    {
        return $this->addresses()->create($data);
    }

    /**
     * Check if the model has any addresses.
     */
    public function hasAddresses(): bool
    {
        return $this->addresses()->exists();
    }

    /**
     * Get addresses by type.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Address>
     */
    public function getAddressesByType(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return $this->addresses()->where('type', $type)->get();
    }
}
