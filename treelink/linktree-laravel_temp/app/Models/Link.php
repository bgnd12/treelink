<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'title', 'url', 'slug', 'icon', 'is_active', 'clicks_count', 'position'])]
class Link extends Model
{
    use HasFactory;

    public const AVAILABLE_ICONS = [
        'link', 'instagram', 'tiktok', 'youtube', 'whatsapp', 'facebook', 'threads', 'x',
        'telegram', 'discord', 'spotify', 'github', 'linkedin', 'website', 'mail',
        'shop', 'product', 'donation', 'contact', 'location',
    ];

    /**
     * Accent colors used when rendering a link icon tile.
     *
     * @var array<string, string>
     */
    public const ICON_COLORS = [
        'link' => '#6366f1',
        'instagram' => '#e1306c',
        'tiktok' => '#0f172a',
        'youtube' => '#ff0000',
        'whatsapp' => '#25d366',
        'facebook' => '#1877f2',
        'threads' => '#111827',
        'x' => '#111827',
        'github' => '#181717',
        'telegram' => '#2aabee',
        'discord' => '#5865f2',
        'spotify' => '#1db954',
        'linkedin' => '#0a66c2',
        'mail' => '#ea580c',
        'website' => '#0891b2',
        'shop' => '#7c3aed',
        'product' => '#7c3aed',
        'donation' => '#e11d48',
        'contact' => '#2563eb',
        'location' => '#dc2626',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accent color for an icon name.
     */
    public static function iconColor(string $icon): string
    {
        return self::ICON_COLORS[$icon] ?? '#6366f1';
    }

    /**
     * Human friendly label for an icon name.
     */
    public static function iconLabel(string $icon): string
    {
        return ucfirst(str_replace('-', ' ', $icon));
    }

    public static function iconSvg(string $icon): string
    {
        $paths = [
            'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>',
            'tiktok' => '<path d="M14 4v10.5a4.5 4.5 0 1 1-3-4.24V7.1c3.1 2.1 5.4 2.3 6 2.3V6.2c-1.4-.1-2.5-.8-3-2.2Z"/>',
            'youtube' => '<rect x="3" y="6" width="18" height="12" rx="3"/><path d="m10 9 5 3-5 3V9Z" fill="currentColor" stroke="none"/>',
            'whatsapp' => '<path d="M6.5 19.5 7.5 16A7 7 0 1 1 12 19a7 7 0 0 1-3.5-.9l-2 .4Z"/><path d="M9.5 9.5c.5 2 1.5 3 3.5 4l1.5-1c.3-.2.7-.1.9.1l.8.8c.3.3.3.8 0 1.1-3.5 2-8-2.5-6-6 .3-.3.8-.3 1.1 0l.8.8c.2.2.3.6.1.9l-.7 1.3Z" fill="currentColor" stroke="none"/>',
            'facebook' => '<path d="M14 8h3V4.5h-3c-2.8 0-4.5 1.7-4.5 4.7V11H7v3.5h2.5V20H13v-5.5h3l.5-3.5H13V9.5c0-1 .3-1.5 1-1.5Z" fill="currentColor" stroke="none"/>',
            'x' => '<path d="m5 4 5.4 6.5L5.3 20H8l3.6-7 5.8 7H21l-6.2-7.4L20.5 4h-2.7l-3.1 6.2L9.5 4H5Z" fill="currentColor" stroke="none"/>',
            'threads' => '<circle cx="12" cy="12" r="8.5"/><path d="M16.5 12c-1.8-1.2-5.3-1.2-6.8.3-1.2 1.2-.8 3.2.8 3.7 2.2.7 4.3-.8 4.3-3.1 0-3.3-2-5-5-5-2 0-3.4.7-4.2 2" fill="none"/>',
            'telegram' => '<path d="m21 4-3 16-5.2-4.1-2.8 2.7.4-4.2L18 7l-9.2 5.8-4-1.3L21 4Z" fill="currentColor" stroke="none"/>',
            'discord' => '<path d="M7 7.5A14 14 0 0 1 12 6a14 14 0 0 1 5 1.5l2 9a13 13 0 0 1-4 2l-1-1.5a8 8 0 0 1-4 0L9 18.5a13 13 0 0 1-4-2l2-9Z"/><circle cx="9.5" cy="12" r="1" fill="currentColor" stroke="none"/><circle cx="14.5" cy="12" r="1" fill="currentColor" stroke="none"/>',
            'spotify' => '<circle cx="12" cy="12" r="9" fill="currentColor" stroke="none"/><path d="M7 10c3-1 7-1 10 .5M7.5 13c2.5-.7 5.5-.5 8 .6M8.5 16c1.8-.4 3.5-.2 5 .4" fill="none" stroke="white" stroke-width="1.3"/>',
            'github' => '<path d="M12 3a9 9 0 0 0-2.8 17.6c.4.1.6-.2.6-.4v-1.6c-2.4.5-2.9-1-2.9-1-.4-1-.9-1.3-.9-1.3-.8-.5.1-.5.1-.5.9.1 1.4.9 1.4.9.8 1.4 2.1 1 2.6.8.1-.6.3-1 .6-1.2-1.9-.2-3.8-.9-3.8-4.1 0-.9.3-1.6.8-2.2-.1-.2-.4-1.1.1-2.2 0 0 .7-.2 2.3.8a8 8 0 0 1 4.2 0c1.6-1 2.3-.8 2.3-.8.5 1.1.2 2 .1 2.2.5.6.8 1.3.8 2.2 0 3.2-2 3.9-3.8 4.1.3.3.6.8.6 1.6v2.4c0 .2.2.5.6.4A9 9 0 0 0 12 3Z" fill="currentColor" stroke="none"/>',
            'linkedin' => '<path d="M5 8.5H2V21h3V8.5ZM3.5 3A1.8 1.8 0 1 0 3.5 6.6 1.8 1.8 0 0 0 3.5 3ZM8 8.5h3v1.7c.6-1.1 1.8-2 3.8-2 4 0 4.7 2.6 4.7 6V21h-3v-6c0-1.4 0-3.2-2-3.2s-2.3 1.5-2.3 3.1V21H8V8.5Z" fill="currentColor" stroke="none"/>',
        ];

        $path = $paths[$icon] ?? '<circle cx="12" cy="12" r="8"/><path d="M12 8v8m-4-4h8"/>';

        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$path.'</svg>';
    }
}
