<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'display_name',
    'bio',
    'avatar_url',
    'theme',
    'button_style',
    'font',
    'social_links',
    'settings',
])]
class Profile extends Model
{
    use HasFactory;

    public const AVAILABLE_THEMES = [
        'aurora' => ['label' => 'Aurora', 'from' => 'from-indigo-600', 'to' => 'to-fuchsia-500', 'text' => 'text-white', 'from_hex' => '#4f46e5', 'to_hex' => '#d946ef', 'text_hex' => '#ffffff'],
        'ocean' => ['label' => 'Ocean', 'from' => 'from-sky-500', 'to' => 'to-cyan-400', 'text' => 'text-white', 'from_hex' => '#0ea5e9', 'to_hex' => '#22d3ee', 'text_hex' => '#ffffff'],
        'midnight' => ['label' => 'Midnight', 'from' => 'from-slate-900', 'to' => 'to-slate-700', 'text' => 'text-white', 'from_hex' => '#0f172a', 'to_hex' => '#334155', 'text_hex' => '#ffffff'],
        'sunset' => ['label' => 'Sunset', 'from' => 'from-orange-500', 'to' => 'to-rose-500', 'text' => 'text-white', 'from_hex' => '#f97316', 'to_hex' => '#f43f5e', 'text_hex' => '#ffffff'],
        'forest' => ['label' => 'Forest', 'from' => 'from-emerald-600', 'to' => 'to-lime-500', 'text' => 'text-white', 'from_hex' => '#059669', 'to_hex' => '#84cc16', 'text_hex' => '#ffffff'],
        'rose' => ['label' => 'Rose', 'from' => 'from-pink-500', 'to' => 'to-red-400', 'text' => 'text-white', 'from_hex' => '#ec4899', 'to_hex' => '#f87171', 'text_hex' => '#ffffff'],
        'cream' => ['label' => 'Cream', 'from' => 'from-amber-50', 'to' => 'to-orange-100', 'text' => 'text-ink-900', 'from_hex' => '#fffbeb', 'to_hex' => '#ffedd5', 'text_hex' => '#0f172a'],
    ];

    public const HEADER_LAYOUTS = [
        'classic' => ['label' => 'Classic', 'desc' => 'Avatar bulat, tampilan ikonik'],
        'hero' => ['label' => 'Hero', 'desc' => 'Avatar besar dengan ring gradient'],
        'banner' => ['label' => 'Banner', 'desc' => 'Banner warna di bagian atas'],
        'cutout' => ['label' => 'Cutout', 'desc' => 'Nama menyatu dengan avatar'],
        'shape' => ['label' => 'Shape', 'desc' => 'Avatar bentuk unik'],
    ];

    public const BUTTON_STYLES = [
        'soft' => ['label' => 'Soft', 'desc' => 'Transparan, lembut'],
        'solid' => ['label' => 'Solid', 'desc' => 'Warna pekat'],
        'outline' => ['label' => 'Outline', 'desc' => 'Hanya border'],
        'pill' => ['label' => 'Pill', 'desc' => 'Bulat penuh'],
        'square' => ['label' => 'Square', 'desc' => 'Sudut tajam'],
    ];

    public const PATTERNS = [
        'dots' => ['label' => 'Dots'],
        'grid' => ['label' => 'Grid'],
        'waves' => ['label' => 'Waves'],
        'rings' => ['label' => 'Rings'],
    ];

    public const FONTS = [
        'sans' => ['label' => 'Sans', 'family' => "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif"],
        'serif' => ['label' => 'Serif', 'family' => "'Playfair Display', ui-serif, Georgia, serif"],
        'mono' => ['label' => 'Mono', 'family' => "'JetBrains Mono', ui-monospace, monospace"],
    ];

    public const SETTINGS_DEFAULTS = [
        'layout' => 'classic',
        'wallpaper_type' => 'gradient',
        'bg_color' => '#ffffff',
        'pattern' => 'dots',
        'button_radius' => 14,
        'button_shadow' => false,
        'button_border' => false,
        'text_color' => '',
        'button_color' => '',
        'accent_color' => '',
        'show_social' => true,
        'show_share' => true,
        'custom_wallpaper_url' => '',
        'featured_link_id' => null,
        'animations_enabled' => false,
        'seo_title' => '',
        'seo_description' => '',
    ];

    public const SOCIAL_PLATFORMS = [
        'instagram' => ['label' => 'Instagram', 'placeholder' => 'https://instagram.com/kamu'],
        'tiktok' => ['label' => 'TikTok', 'placeholder' => 'https://tiktok.com/@kamu'],
        'youtube' => ['label' => 'YouTube', 'placeholder' => 'https://youtube.com/@kamu'],
        'x' => ['label' => 'X (Twitter)', 'placeholder' => 'https://x.com/kamu'],
        'whatsapp' => ['label' => 'WhatsApp', 'placeholder' => 'https://wa.me/62812xxxx'],
        'telegram' => ['label' => 'Telegram', 'placeholder' => 'https://t.me/kamu'],
        'github' => ['label' => 'GitHub', 'placeholder' => 'https://github.com/kamu'],
        'linkedin' => ['label' => 'LinkedIn', 'placeholder' => 'https://linkedin.com/in/kamu'],
        'email' => ['label' => 'Email', 'placeholder' => 'kamu@email.com'],
        'website' => ['label' => 'Website', 'placeholder' => 'https://kamu.com'],
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'settings' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Resolve the theme configuration used to render the public page.
     *
     * @return array{label: string, from: string, to: string, text: string, from_hex: string, to_hex: string, text_hex: string}
     */
    public function themeConfig(): array
    {
        return self::AVAILABLE_THEMES[$this->theme] ?? self::AVAILABLE_THEMES['aurora'];
    }

    /**
     * Merged design settings with defaults.
     *
     * @return array<string, mixed>
     */
    public function settings(): array
    {
        return array_merge(self::SETTINGS_DEFAULTS, $this->settings ?? []);
    }

    /**
     * A single resolved design setting.
     */
    public function setting(string $key, mixed $default = null): mixed
    {
        return $this->settings()[$key] ?? $default;
    }

    /**
     * Avatar image URL, falling back to an auto-generated placeholder.
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?: $this->defaultAvatar(),
        );
    }

    /**
     * Compute the CSS value (background-color/background-image/filter) used.
     */
    public function backgroundStyle(): string
    {
        $settings = $this->settings();
        $theme = $this->themeConfig();

        switch ($settings['wallpaper_type']) {
            case 'custom':
                if (! empty($settings['custom_wallpaper_url'])) {
                    $wallpaperUrl = addcslashes((string) $settings['custom_wallpaper_url'], "\\\"");

                    return 'background-color: #f8fafc; background-image: url("'.$wallpaperUrl.'"); background-size: cover; background-position: center; background-attachment: fixed;';
                }

                return 'background-color: #f8fafc;';
            case 'solid':
                $base = $settings['bg_color'];

                return 'background-color: '.$base.';';
            case 'pattern':
                $base = $settings['bg_color'];
                $overlay = $this->isLightColor($base)
                    ? 'rgba(15,23,42,0.12)'
                    : 'rgba(255,255,255,0.16)';

                return 'background-color: '.$base.'; background-image: '.$this->patternImage($settings['pattern'], $overlay).';';
            default:
                return 'background-image: linear-gradient(135deg, '.$theme['from_hex'].', '.$theme['to_hex'].');';
        }
    }

    /**
     * Foreground (text) color the whole page should use.
     */
    public function textColor(): string
    {
        $settings = $this->settings();

        if (! empty($settings['text_color'])) {
            return $settings['text_color'];
        }

        if ($settings['wallpaper_type'] === 'gradient') {
            return $this->themeConfig()['text_hex'];
        }

        return $this->isLightColor($settings['bg_color']) ? '#0f172a' : '#ffffff';
    }

    /**
     * True when a hex color is bright enough to use dark text over it.
     */
    public function isLightColor(string $hex): bool
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $rgb = array_map('hexdec', str_split(substr($hex, 0, 6), 2));

        [$r, $g, $b] = array_map(fn (int $v) => $v / 255, $rgb);

        $lum = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;

        return $lum > 0.55;
    }

    /**
     * Background-image value for the given pattern name.
     */
    public function patternImage(string $pattern, string $overlay): string
    {
        return match ($pattern) {
            'grid' => 'linear-gradient('.$overlay.' 1px, transparent 1px), linear-gradient(90deg, '.$overlay.' 1px, transparent 1px); background-size: 24px 24px',
            'waves' => 'radial-gradient(ellipse at 50% -20%, '.$overlay.' 40%, transparent 42%); background-size: 40px 26px',
            'rings' => 'radial-gradient(circle, transparent 6px, '.$overlay.' 7px, '.$overlay.' 8px, transparent 9px); background-size: 32px 32px',
            default => 'radial-gradient(circle, '.$overlay.' 1.5px, transparent 1.6px); background-size: 16px 16px',
        };
    }

    /**
     * CSS string describing button look (background/border/radius/shadow).
     */
    public function buttonStyle(): string
    {
        $settings = $this->settings();
        $style = $this->button_style;

        if ($style === 'pill') {
            $radius = '999px';
        } elseif ($style === 'square') {
            $radius = '0px';
        } else {
            $radius = $settings['button_radius'].'px';
        }

        $css = [];

        if ($style === 'outline') {
            $css[] = 'background-color: transparent';
            $css[] = 'border: 1.5px solid '.$this->textColor();
        } else {
            if (! empty($settings['button_color'])) {
                $bg = $settings['button_color'];
                $fg = $this->isLightColor($bg) ? '#0f172a' : '#ffffff';
            } else {
                $bg = $this->textColor() === '#ffffff' ? 'rgba(255,255,255,0.14)' : 'rgba(255,255,255,0.85)';
                $fg = $this->textColor() === '#ffffff' ? '#ffffff' : '#0f172a';
            }

            if ($style === 'solid') {
                if (str_starts_with($bg, 'rgba')) {
                    $bg = $this->textColor() === '#ffffff' ? '#ffffff' : '#0f172a';
                    $fg = $this->textColor() === '#ffffff' ? '#0f172a' : '#ffffff';
                }
            }

            $css[] = 'background-color: '.$bg;
            $css[] = 'color: '.$fg;

            if ($settings['button_border']) {
                $css[] = 'border: 1px solid '.($this->textColor() === '#ffffff' ? 'rgba(255,255,255,0.35)' : 'rgba(15,23,42,0.15)');
            } else {
                $css[] = 'border: 1px solid transparent';
            }
        }

        if ($settings['button_shadow']) {
            $css[] = 'box-shadow: 0 8px 24px -6px rgba(0,0,0,0.35)';
        }

        $css[] = 'border-radius: '.$radius;

        return implode('; ', $css).';';
    }

    private function defaultAvatar(): string
    {
        $name = $this->display_name ?: $this->user?->name ?: 'U';
        $initial = mb_strtoupper(mb_substr($name, 0, 1));

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#4f46e5"/><stop offset="1" stop-color="#7c3aed"/></linearGradient></defs><circle cx="48" cy="48" r="48" fill="url(#g)"/><text x="48" y="61" font-family="Arial, sans-serif" font-size="44" font-weight="bold" fill="#ffffff" text-anchor="middle">'.$initial.'</text></svg>';

        return 'data:image/svg+xml,'.rawurlencode($svg);
    }
}
