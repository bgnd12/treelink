<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color',
        'size',
        'sku',
        'price',
        'stock',
        'image_path',
        'position',
        'is_active',
    ];

    protected $appends = [
        'image_url',
        'label',
        'price_label',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'position' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return url('storage/'.$this->image_path);
    }

    /**
     * Human friendly name built from colour and size, e.g. "Merah / L".
     */
    public function getLabelAttribute(): string
    {
        $parts = array_filter([$this->color, $this->size], fn ($value) => filled($value));

        return $parts ? implode(' / ', $parts) : 'Varian '.$this->id;
    }

    public function getPriceLabelAttribute(): ?string
    {
        if ($this->price === null || $this->price === '') {
            return null;
        }

        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }

    /**
     * Effective selling price, falling back to the parent product price.
     */
    public function effectivePrice(): float
    {
        if ($this->price !== null) {
            return (float) $this->price;
        }

        return (float) ($this->product?->price ?? 0);
    }
}
