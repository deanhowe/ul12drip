<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'street',
        'city',
        'state',
        'postal_code',
        'country',
        'type',
    ];

    /**
     * Get the parent addressable model (User, Supplier, etc.).
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
