<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        ];
    }

    /**
     * The user's link-in-bio profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * The user's links.
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class)->orderBy('position')->orderBy('created_at');
    }

    /**
     * Return the profile, creating a default one if it does not exist yet.
     */
    public function getOrCreateProfile(): Profile
    {
        if ($this->profile === null) {
            $this->profile()->create();
        }

        return $this->profile;
    }

    /**
     * The public URL to the user's link-in-bio page.
     */
    public function publicUrl(): string
    {
        return url('/'.$this->username);
    }
}