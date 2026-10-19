<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'google_id',
        'password',
        'is_admin',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class)->where('page_id', $this->currentPageId());
    }

    public function pages(): HasMany
    {
        return $this->hasMany(LinkPage::class);
    }

    public function currentPage(): LinkPage
    {
        $pageId = Session::get('active_link_page_id');
        $page = $pageId ? $this->pages()->whereKey($pageId)->first() : null;

        if ($page) {
            return $page;
        }

        $page = $this->pages()->where('is_default', true)->first();

        return $page ?: $this->pages()->create([
            'name' => $this->name,
            'username' => $this->username,
            'is_default' => true,
        ]);
    }

    public function currentPageId(): int
    {
        return $this->currentPage()->id;
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class)->where('page_id', $this->currentPageId())->orderBy('position');
    }

    public function shortLinks(): HasMany
    {
        return $this->hasMany(ShortLink::class)->latest();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->where('page_id', $this->currentPageId())->orderBy('position');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id')->latest();
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id')->latest();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class)->latest();
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class)->latest();
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class)
            ->withPivot('last_read_at')
            ->withTimestamps()
            ->orderByDesc('conversations.updated_at');
    }

    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class)->where('page_id', $this->currentPageId());
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'user_follows', 'followed_id', 'follower_id')->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'user_follows', 'follower_id', 'followed_id')->withTimestamps();
    }

    /**
     * Get (or lazily create) the profile belonging to the user.
     */
    public function getOrCreateProfile(): Profile
    {
        return Profile::firstOrCreate(['page_id' => $this->currentPageId()], [
            'user_id' => $this->id,
            'bio' => null,
            'theme' => 'aurora',
            'button_style' => 'rounded',
            'background_type' => 'gradient',
            'background_value' => 'from-brand-500 to-indigo-600',
        ]);
    }

    public function publicUrl(): string
    {
        return $this->currentPage()->publicUrl();
    }
}
