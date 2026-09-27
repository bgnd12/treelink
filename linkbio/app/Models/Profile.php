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
        'button_color',
        'button_radius',
        'button_border_width',
        'button_shadow',
        'font',
        'background_type',
        'background_value',
        'background_image_path',
        'header_layout',
        'animation_enabled',
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
            'animation_enabled' => 'boolean',
            'button_shadow' => 'boolean',
            'button_radius' => 'integer',
            'button_border_width' => 'integer',
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

    public function getBackgroundImageUrlAttribute(): ?string
    {
        if (! $this->background_image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->background_image_path);
    }

    /**
     * Border radius for the public page link buttons, in pixels.
     */
    public function buttonRadiusPx(): int
    {
        if ($this->button_radius !== null) {
            return (int) $this->button_radius;
        }

        return match ($this->button_style) {
            'pill' => 999,
            'square' => 6,
            default => 14,
        };
    }

    /**
     * Readable text colour for the link buttons, derived from the button colour.
     */
    public function buttonTextColor(): string
    {
        $hex = ltrim((string) ($this->button_color ?: ($this->themeConfig()['accent'] ?? '#ffffff')), '#');

        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return '#ffffff';
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return ((0.299 * $r + 0.587 * $g + 0.114 * $b) / 255) > 0.6 ? '#111827' : '#ffffff';
    }

    public function fontFamily(): string
    {
        return self::FONT_FAMILIES[$this->font] ?? self::FONT_FAMILIES['sans'];
    }

    public const AVAILABLE_THEMES = [
        'aurora' => ['label' => 'Aurora', 'from' => 'from-brand-500', 'to' => 'to-indigo-600', 'text' => 'text-white', 'accent' => '#ffffff', 'pattern_bg' => '#3f1c99'],
        'sunset' => ['label' => 'Sunset', 'from' => 'from-orange-400', 'to' => 'to-pink-600', 'text' => 'text-white', 'accent' => '#ffffff', 'pattern_bg' => '#9a3412'],
        'forest' => ['label' => 'Forest', 'from' => 'from-emerald-500', 'to' => 'to-teal-700', 'text' => 'text-white', 'accent' => '#ffffff', 'pattern_bg' => '#064e3b'],
        'midnight' => ['label' => 'Midnight', 'from' => 'from-slate-900', 'to' => 'to-slate-700', 'text' => 'text-white', 'accent' => '#e2e8f0', 'pattern_bg' => '#0f172a'],
        'candy' => ['label' => 'Candy', 'from' => 'from-fuchsia-400', 'to' => 'to-rose-400', 'text' => 'text-white', 'accent' => '#ffffff', 'pattern_bg' => '#86198f'],
        'minimal' => ['label' => 'Minimal Light', 'from' => 'from-gray-100', 'to' => 'to-gray-200', 'text' => 'text-ink-900', 'accent' => '#111827', 'pattern_bg' => '#e5e7eb'],
    ];

    public const BUTTON_STYLES = ['rounded', 'pill', 'square', 'outline'];

    public const HEADER_LAYOUTS = [
        'classic' => ['label' => 'Classic', 'desc' => 'Avatar bulat di atas'],
        'banner' => ['label' => 'Banner', 'desc' => 'Banner lebar di atas'],
        'cutout' => ['label' => 'Cutout', 'desc' => 'Avatar mengambang'],
        'shape' => ['label' => 'Shape', 'desc' => 'Avatar kotak rounded'],
        'hero' => ['label' => 'Hero', 'desc' => 'Avatar besar tearuh'],
    ];

    public const BACKGROUND_TYPES = [
        'gradient' => 'Gradient',
        'solid' => 'Solid Color',
        'pattern' => 'Pattern',
        'image' => 'Upload Gambar',
    ];

    public const PATTERNS = [
        'dots' => ['label' => 'Dots', 'swatch' => 'pattern-dots'],
        'grid' => ['label' => 'Grid', 'swatch' => 'pattern-grid'],
        'diagonal' => ['label' => 'Diagonal', 'swatch' => 'pattern-diagonal'],
        'waves' => ['label' => 'Waves', 'swatch' => 'pattern-waves'],
    ];

    public const FONT_LABELS = [
        'sans' => 'Sans',
        'serif' => 'Serif',
        'mono' => 'Mono',
    ];

    public const FONT_FAMILIES = [
        'sans' => "'Plus Jakarta Sans', sans-serif",
        'serif' => "'Playfair Display', serif",
        'mono' => "'JetBrains Mono', monospace",
    ];

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
