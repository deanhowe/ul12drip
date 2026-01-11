<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExhaustiveFeature extends Model
{
    /** @use HasFactory<\Database\Factories\ExhaustiveFeatureFactory> */
    use HasFactory;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'json_col' => 'array',
            'jsonb_col' => 'array',
            'date_col' => 'date',
            'date_time_col' => 'datetime',
            'date_time_tz_col' => 'datetime',
            'timestamp_col' => 'datetime',
            'timestamp_tz_col' => 'datetime',
            'boolean_col' => 'boolean',
            'base64_col' => \App\Casts\Base64Cast::class,
        ];
    }
}
