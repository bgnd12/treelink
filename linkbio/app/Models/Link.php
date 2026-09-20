<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'url',
        'icon',
        'position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class);
    }

    public function getClicksCountAttribute(): int
    {
        return $this->clicks()->count();
    }

    public const AVAILABLE_ICONS = [
        'link', 'instagram', 'tiktok', 'youtube', 'whatsapp', 'github',
        'twitter', 'facebook', 'linkedin', 'globe', 'mail', 'spotify',
        'shop', 'calendar', 'music', 'camera', 'file', 'map-pin',
    ];
}
