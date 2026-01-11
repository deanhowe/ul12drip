<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for Address model (polymorphic one-to-one).
 */
class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'street' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,

            // Full formatted address
            'formatted' => implode(', ', array_filter([
                $this->street,
                $this->city,
                $this->state,
                $this->postal_code,
                $this->country,
            ])),

            // Polymorphic info
            'addressable_type' => $this->addressable_type,
            'addressable_id' => $this->addressable_id,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
