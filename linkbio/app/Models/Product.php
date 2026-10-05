<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'price',
        'description',
        'category',
        'stock',
        'image_path',
        'position',
        'is_active',
    ];

    protected $appends = [
        'image_url',
        'price_label',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'position' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    public function getPriceLabelAttribute(): ?string
    {
        if ($this->price === null || $this->price === '') {
            return null;
        }

        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }
}
