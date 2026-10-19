<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'page_id',
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

    public function page(): BelongsTo
    {
        return $this->belongsTo(LinkPage::class, 'page_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (! $product->page_id && $product->user) {
                $product->page_id = $product->user->currentPageId();
            }
        });
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('position')->orderBy('id');
    }

    public function activeVariants(): HasMany
    {
        return $this->variants()->where('is_active', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function visibleReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_visible', true)->latest();
    }

    public function productViews(): HasMany
    {
        return $this->hasMany(ProductView::class);
    }

    public function hasVariants(): bool
    {
        if ($this->relationLoaded('variants')) {
            return $this->variants->isNotEmpty();
        }

        return $this->variants()->exists();
    }

    /**
     * Whether the product (or all of its variants) is sold out.
     */
    public function isSoldOut(): bool
    {
        if ($this->hasVariants()) {
            $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();

            return $variants->every(fn (ProductVariant $variant) => $variant->stock !== null && $variant->stock < 1);
        }

        return $this->stock === 0;
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

    public function getAverageRatingAttribute(): float
    {
        $value = $this->attributes['reviews_avg_rating'] ?? null;

        if ($value === null && ! $this->relationLoaded('reviews')) {
            $value = $this->visibleReviews()->avg('rating');
        }

        return round((float) $value, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        if (array_key_exists('reviews_count', $this->attributes)) {
            return (int) $this->attributes['reviews_count'];
        }

        if ($this->relationLoaded('reviews')) {
            return $this->reviews->where('is_visible', true)->count();
        }

        return $this->visibleReviews()->count();
    }
}
