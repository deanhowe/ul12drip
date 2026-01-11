<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'imageable_type',
        'imageable_id',
        'url',
        'alt',
        'order',
    ];

    /**
     * Get the parent imageable model (User, Post, Product, etc.).
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
