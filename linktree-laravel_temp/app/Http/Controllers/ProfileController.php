<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $user->getOrCreateProfile();
        $socialPlatforms = Profile::SOCIAL_PLATFORMS;

        return view('dashboard.profile', compact('user', 'profile', 'socialPlatforms'));
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $profile = $user->getOrCreateProfile();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'max:30', 'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'display_name' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:280'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'string', 'max:2048'],
            'layout' => ['nullable', 'string', Rule::in(array_keys(Profile::HEADER_LAYOUTS))],
            'tab' => ['nullable', 'string'],
        ]);

        $user->update([
            'name' => $data['name'],
            'username' => strtolower($data['username']),
        ]);

        $profileData = [
            'display_name' => $data['display_name'] ?? null,
            'bio' => $data['bio'] ?? null,
            'social_links' => $request->has('social_links') ? ($data['social_links'] ?? []) : $profile->social_links,
        ];

        if (! empty($data['layout'])) {
            $settings = $profile->settings();
            $settings['layout'] = $data['layout'];
            $profileData['settings'] = $settings;
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = $user->id.'-'.time().'.'.$file->getClientOriginalExtension();

            $existing = (string) $profile->avatar_url;
            if (str_starts_with($existing, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $existing));
            }

            $path = Storage::disk('public')->putFileAs('avatars', $file, $filename);
            $profileData['avatar_url'] = 'storage/'.$path;
        }

        $profile->update($profileData);

        $request->session()->flash('editor_tab', $data['tab'] ?? 'header');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Profil berhasil diperbarui.',
                'profile' => [
                    'name' => $user->name,
                    'username' => $user->username,
                    'display_name' => $profile->display_name,
                    'bio' => $profile->bio,
                    'avatar_url' => $profile->avatar_url,
                ],
            ]);
        }

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    public function updateSettings(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $profile = $user->getOrCreateProfile();

        $data = $request->validate([
            'theme' => ['nullable', 'string', Rule::in(array_keys(Profile::AVAILABLE_THEMES))],
            'button_style' => ['nullable', 'string', Rule::in(array_keys(Profile::BUTTON_STYLES))],
            'font' => ['nullable', 'string', Rule::in(array_keys(Profile::FONTS))],
            'layout' => ['nullable', 'string', Rule::in(array_keys(Profile::HEADER_LAYOUTS))],
            'wallpaper_type' => ['nullable', 'string', Rule::in(['solid', 'gradient', 'pattern', 'custom'])],
            'custom_wallpaper_url' => ['nullable', 'url', 'max:2048'],
            'custom_wallpaper' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'bg_color' => ['nullable', 'string', 'max:20'],
            'pattern' => ['nullable', 'string', Rule::in(array_keys(Profile::PATTERNS))],
            'button_radius' => ['nullable', 'integer', 'min:0', 'max:40'],
            'button_shadow' => ['nullable', 'boolean'],
            'button_border' => ['nullable', 'boolean'],
            'text_color' => ['nullable', 'string', 'max:20'],
            'button_color' => ['nullable', 'string', 'max:20'],
            'accent_color' => ['nullable', 'string', 'max:20'],
            'show_social' => ['nullable', 'boolean'],
            'show_share' => ['nullable', 'boolean'],
            'social_links' => ['nullable', 'array'],
            'social_links.instagram' => ['nullable', 'url', 'max:2048'],
            'social_links.tiktok' => ['nullable', 'url', 'max:2048'],
            'social_links.youtube' => ['nullable', 'url', 'max:2048'],
            'social_links.facebook' => ['nullable', 'url', 'max:2048'],
            'social_links.x' => ['nullable', 'url', 'max:2048'],
            'social_links.whatsapp' => ['nullable', 'url', 'max:2048'],
            'featured_link_id' => ['nullable', 'integer', Rule::exists('links', 'id')->where(fn ($query) => $query->where('user_id', $user->id)->where('is_active', true))],
            'animations_enabled' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'tab' => ['nullable', 'string'],
        ]);

        $settings = $profile->settings();

        foreach ([
            'layout',
            'wallpaper_type',
            'bg_color',
            'pattern',
            'button_radius',
            'text_color',
            'button_color',
            'accent_color',
        ] as $key) {
            if (array_key_exists($key, $data)) {
                $settings[$key] = $data[$key] === '' ? null : $data[$key];
            }
        }

        foreach (['button_shadow', 'button_border', 'show_social', 'show_share'] as $key) {
            if (array_key_exists($key, $data)) {
                $settings[$key] = (bool) $data[$key];
            }
        }

        foreach (['featured_link_id', 'animations_enabled', 'seo_title', 'seo_description'] as $key) {
            if (array_key_exists($key, $data)) {
                $settings[$key] = $data[$key] === '' ? null : ($key === 'animations_enabled' ? (bool) $data[$key] : $data[$key]);
            }
        }

        if (array_key_exists('social_links', $data)) {
            $socialLinks = $profile->social_links ?? [];
            foreach (['instagram', 'tiktok', 'youtube', 'facebook', 'x', 'whatsapp'] as $platform) {
                $socialLinks[$platform] = $data['social_links'][$platform] ?? null;
            }
            $profile->social_links = array_filter($socialLinks);
        }

        if ($request->hasFile('custom_wallpaper')) {
            $path = $request->file('custom_wallpaper')->store('wallpapers', 'public');
            $settings['custom_wallpaper_url'] = Storage::disk('public')->url($path);
        } elseif (array_key_exists('custom_wallpaper_url', $data)) {
            $settings['custom_wallpaper_url'] = $data['custom_wallpaper_url'] ?: null;
        }

        $profile->update([
            'theme' => $data['theme'] ?? $profile->theme,
            'button_style' => $data['button_style'] ?? $profile->button_style,
            'font' => $data['font'] ?? $profile->font,
            'settings' => $settings,
        ]);

        $request->session()->flash('editor_tab', $data['tab'] ?? 'design');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pengaturan desain tersimpan.',
                'design' => [
                    'theme' => $profile->theme,
                    'button_style' => $profile->button_style,
                    'font' => $profile->font,
                    'settings' => $profile->settings(),
                ],
                'socials' => array_filter($profile->social_links ?? []),
            ]);
        }

        return back()->with('status', 'Pengaturan desain tersimpan.');
    }
}
