<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Profile;
use App\Support\Brands;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Backs the Linktree-style editor screen (Content / Header / Design / Enhance).
 *
 * The Alpine component in resources/js/editor.js owns the live state and posts
 * every mutation back to the endpoints below, which answer with the refreshed
 * profile payload so the phone preview stays in sync without a reload.
 */
class EditorController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $user->getOrCreateProfile();

        $socials = collect(Profile::SOCIAL_PLATFORMS)
            ->map(fn (array $meta, string $key): array => [
                'key' => $key,
                'label' => $meta['label'],
                'url' => $profile->social_links[$key] ?? '',
            ])
            ->values()
            ->all();

        return view('dashboard.editor', [
            'user' => $user,
            'editorData' => [
                'profile' => $this->profilePayload($profile),
                'links' => $user->links()->get()->toArray(),
                'products' => $user->products()->get()->toArray(),
                'publicUrl' => $user->publicUrl(),
                'themes' => Profile::AVAILABLE_THEMES,
                'headerLayouts' => Profile::HEADER_LAYOUTS,
                'backgroundTypes' => Profile::BACKGROUND_TYPES,
                'cardStyles' => Profile::CARD_STYLES,
                'patterns' => Profile::PATTERNS,
                'buttonStyles' => Profile::BUTTON_STYLES,
                'fonts' => Profile::FONT_LABELS,
                'icons' => Link::AVAILABLE_ICONS,
                'iconSvgs' => Brands::iconSvgs(),
                'brandHosts' => Brands::hosts(),
                'socials' => $socials,
            ],
        ]);
    }

    /**
     * Header tab: display name, username, bio, header layout and avatar.
     */
    public function header(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'display_name' => ['nullable', 'string', 'max:255'],
            'username' => [
                'nullable', 'string', 'max:60', 'alpha_dash',
                'not_regex:/^(?:'.implode('|', array_map(
                    'preg_quote',
                    PublicProfileController::RESERVED_USERNAMES
                )).')$/i',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'bio' => ['nullable', 'string', 'max:280'],
            'header_layout' => ['nullable', Rule::in(array_keys(Profile::HEADER_LAYOUTS))],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ], [
            'username.unique' => 'Username tersebut sudah dipakai.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, tanda hubung, dan garis bawah.',
            'username.not_regex' => 'Username ini dipakai oleh sistem. Pilih username lain.',
        ]);

        $profile = $user->getOrCreateProfile();

        $attributes = [
            'display_name' => $validated['display_name'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'header_layout' => $validated['header_layout'] ?? 'classic',
        ];

        if ($request->hasFile('avatar')) {
            if ($profile->avatar_path) {
                Storage::disk('public')->delete($profile->avatar_path);
            }

            $attributes['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $profile->update($attributes);

        if (! empty($validated['username'])) {
            $user->update(['username' => strtolower($validated['username'])]);
        }

        return response()->json(['profile' => $this->profilePayload($profile->fresh())]);
    }

    /**
     * Design tab: theme, background, button styling and font.
     */
    public function design(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme' => ['nullable', Rule::in(array_keys(Profile::AVAILABLE_THEMES))],
            'button_style' => ['nullable', Rule::in(Profile::BUTTON_STYLES)],
            'button_color' => ['nullable', 'string', 'max:20'],
            'button_radius' => ['nullable', 'integer', 'min:0', 'max:999'],
            'button_border_width' => ['nullable', 'integer', 'min:0', 'max:32'],
            'button_shadow' => ['nullable', 'boolean'],
            'font' => ['nullable', Rule::in(array_keys(Profile::FONT_LABELS))],
            'background_type' => ['nullable', Rule::in(array_keys(Profile::BACKGROUND_TYPES))],
            'background_value' => ['nullable', 'string', 'max:255'],
            'background_image' => ['nullable', 'image', 'max:4096'],
            'remove_background_image' => ['nullable', 'boolean'],
            'card_enabled' => ['nullable', 'boolean'],
            'card_style' => ['nullable', Rule::in(array_keys(Profile::CARD_STYLES))],
            'card_color' => ['nullable', 'string', 'max:20', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'card_opacity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'card_radius' => ['nullable', 'integer', 'min:0', 'max:999'],
            'card_border_width' => ['nullable', 'integer', 'min:0', 'max:32'],
            'card_shadow' => ['nullable', 'boolean'],
        ]);

        $profile = $request->user()->getOrCreateProfile();

        $attributes = [
            'theme' => $validated['theme'] ?? $profile->theme,
            'button_style' => $validated['button_style'] ?? $profile->button_style,
            'button_color' => ($validated['button_color'] ?? null) ?: null,
            'button_radius' => $validated['button_radius'] ?? null,
            'button_border_width' => $validated['button_border_width'] ?? 0,
            'button_shadow' => (bool) ($validated['button_shadow'] ?? false),
            'font' => $validated['font'] ?? $profile->font,
            'background_type' => $validated['background_type'] ?? $profile->background_type,
            'background_value' => ($validated['background_value'] ?? null) ?: null,
            'card_enabled' => $request->boolean('card_enabled'),
            'card_style' => $validated['card_style'] ?? $profile->card_style,
            'card_color' => ($validated['card_color'] ?? null) ?: null,
            'card_opacity' => $validated['card_opacity'] ?? null,
            'card_radius' => $validated['card_radius'] ?? null,
            'card_border_width' => $validated['card_border_width'] ?? 1,
            'card_shadow' => (bool) ($validated['card_shadow'] ?? false),
        ];

        if ($request->boolean('remove_background_image')) {
            if ($profile->background_image_path) {
                Storage::disk('public')->delete($profile->background_image_path);
            }

            $attributes['background_image_path'] = null;
        } elseif ($request->hasFile('background_image')) {
            if ($profile->background_image_path) {
                Storage::disk('public')->delete($profile->background_image_path);
            }

            $attributes['background_image_path'] = $request->file('background_image')->store('backgrounds', 'public');
        }

        $profile->update($attributes);

        return response()->json(['profile' => $this->profilePayload($profile->fresh())]);
    }

    /**
     * Enhance tab: social icons, featured link, animation toggle and SEO.
     */
    public function enhance(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->getOrCreateProfile();

        $validated = $request->validate([
            'social_links' => ['nullable', 'array'],
            'featured_link_id' => ['nullable', 'integer'],
            'linkid_active' => ['nullable', 'boolean'],
            'linkid_types' => ['nullable', 'array'],
            'linkid_description' => ['nullable', 'string', 'max:300'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'animation_enabled' => ['nullable', 'boolean'],
        ]);

        $socialLinks = [];

        foreach ((array) ($validated['social_links'] ?? []) as $platform => $url) {
            if (array_key_exists($platform, Profile::SOCIAL_PLATFORMS) && is_string($url) && trim($url) !== '') {
                $socialLinks[$platform] = trim($url);
            }
        }

        $featuredLinkId = $validated['featured_link_id'] ?? null;

        if ($featuredLinkId !== null && ! $user->links()->whereKey($featuredLinkId)->exists()) {
            $featuredLinkId = null;
        }

        $profile->update([
            'social_links' => $socialLinks,
            'is_linkid_active' => (bool) ($validated['linkid_active'] ?? false),
            'linkid_types' => array_values(array_filter((array) ($validated['linkid_types'] ?? []), fn ($type) => is_string($type) && trim($type) !== '')),
            'linkid_description' => ($validated['linkid_description'] ?? null) ?: null,
            'featured_link_id' => $featuredLinkId,
            'seo_title' => ($validated['seo_title'] ?? null) ?: null,
            'seo_description' => ($validated['seo_description'] ?? null) ?: null,
            'animation_enabled' => (bool) ($validated['animation_enabled'] ?? false),
        ]);

        // Keep the denormalised is_featured flag on links in sync.
        Link::where('user_id', $user->id)->update(['is_featured' => false]);

        if ($featuredLinkId !== null) {
            Link::whereKey($featuredLinkId)->update(['is_featured' => true]);
        }

        return response()->json([
            'profile' => $this->profilePayload($profile->fresh()),
            'links' => $user->links()->get()->toArray(),
        ]);
    }

    /**
     * Shape the profile for the Alpine editor: raw columns plus the derived
     * theme/font values the live phone preview reads.
     */
    private function profilePayload(Profile $profile): array
    {
        $theme = $profile->themeConfig();

        return array_merge($profile->toArray(), [
            'username' => $profile->user?->username,
            'avatar_url' => $profile->avatar_url,
            'background_image_url' => $profile->background_image_url,
            'theme_from' => $theme['from'],
            'theme_to' => $theme['to'],
            'theme_text' => $theme['text'],
            'font_family' => $profile->fontFamily(),
        ]);
    }
}
