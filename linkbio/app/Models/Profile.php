<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'bio',
        'avatar_path',
        'theme',
        'button_style',
        'font',
        'background_type',
        'background_value',
        'social_links',
        'featured_link_id',
        'animations_enabled',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'featured_link_id' => 'integer',
            'animations_enabled' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return Storage::disk('public')->url($this->avatar_path);
        }

        $name = urlencode($this->display_name ?: ($this->user->name ?? 'U'));

        return "https://ui-avatars.com/api/?name={$name}&background=7c4dff&color=fff&size=256&bold=true";
    }

    public const AVAILABLE_THEMES = [
        'aurora' => ['label' => 'Aurora', 'from' => 'from-brand-500', 'to' => 'to-indigo-600', 'text' => 'text-white'],
        'sunset' => ['label' => 'Sunset', 'from' => 'from-orange-400', 'to' => 'to-pink-600', 'text' => 'text-white'],
        'forest' => ['label' => 'Forest', 'from' => 'from-emerald-500', 'to' => 'to-teal-700', 'text' => 'text-white'],
        'midnight' => ['label' => 'Midnight', 'from' => 'from-slate-900', 'to' => 'to-slate-700', 'text' => 'text-white'],
        'candy' => ['label' => 'Candy', 'from' => 'from-fuchsia-400', 'to' => 'to-rose-400', 'text' => 'text-white'],
        'minimal' => ['label' => 'Minimal Light', 'from' => 'from-gray-100', 'to' => 'to-gray-200', 'text' => 'text-ink-900'],
    ];

    public const BUTTON_STYLES = ['rounded', 'pill', 'square', 'outline'];

    public const SOCIAL_PLATFORMS = [
        'instagram' => ['label' => 'Instagram', 'icon' => 'instagram'],
        'tiktok' => ['label' => 'TikTok', 'icon' => 'tiktok'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'youtube'],
        'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'whatsapp'],
        'github' => ['label' => 'GitHub', 'icon' => 'github'],
        'twitter' => ['label' => 'X / Twitter', 'icon' => 'twitter'],
        'facebook' => ['label' => 'Facebook', 'icon' => 'facebook'],
        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'linkedin'],
        'website' => ['label' => 'Website', 'icon' => 'globe'],
        'email' => ['label' => 'Email', 'icon' => 'mail'],
    ];

    public function themeConfig(): array
    {
        return self::AVAILABLE_THEMES[$this->theme] ?? self::AVAILABLE_THEMES['aurora'];
    }
}
