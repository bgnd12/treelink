<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LinkPage extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'username', 'is_default'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'page_id');
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'page_id')->orderBy('position');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'page_id')->orderBy('position');
    }

    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class, 'page_id');
    }

    public function publicUrl(): string
    {
        return url('/'.$this->username);
    }
}
