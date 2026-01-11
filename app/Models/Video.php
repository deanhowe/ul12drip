<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Video model with polymorphic traits.
 *
 * Demonstrates:
 * - Commentable trait (polymorphic comments)
 * - Taggable trait (polymorphic many-to-many tags)
 * - Duration formatting helpers
 */
class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use Commentable;

    use HasFactory;
    use Taggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'url',
        'description',
        'duration',
    ];

    /**
     * Scope to get recent videos.
     */
    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to get short videos (under 5 minutes).
     */
    public function scopeShort(Builder $query): Builder
    {
        return $query->where('duration', '<', 300);
    }

    /**
     * Scope to get long videos (over 30 minutes).
     */
    public function scopeLong(Builder $query): Builder
    {
        return $query->where('duration', '>=', 1800);
    }

    /**
     * Get the formatted duration (e.g., "5:30" or "1:30:45").
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration === null) {
            return '0:00';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Check if the video is short (under 5 minutes).
     */
    public function isShort(): bool
    {
        return $this->duration !== null && $this->duration < 300;
    }
}
