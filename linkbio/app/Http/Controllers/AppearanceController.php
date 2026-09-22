<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppearanceController extends Controller
{
    public function edit(Request $request): View
    {
        $profile = $request->user()->getOrCreateProfile();
        $links = $request->user()->links()->where('is_active', true)->get();

        return view('dashboard.appearance', [
            'profile' => $profile,
            'links' => $links,
            'themes' => Profile::AVAILABLE_THEMES,
            'buttonStyles' => Profile::BUTTON_STYLES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'string', 'in:'.implode(',', array_keys(Profile::AVAILABLE_THEMES))],
            'button_style' => ['required', 'string', 'in:'.implode(',', Profile::BUTTON_STYLES)],
            'font' => ['required', 'string', 'in:sans,serif,mono'],
            'social_links' => ['nullable', 'array'],
            'social_links.instagram' => ['nullable', 'url', 'max:255'],
            'social_links.tiktok' => ['nullable', 'url', 'max:255'],
            'social_links.youtube' => ['nullable', 'url', 'max:255'],
            'social_links.facebook' => ['nullable', 'url', 'max:255'],
            'social_links.twitter' => ['nullable', 'url', 'max:255'],
            'social_links.whatsapp' => ['nullable', 'url', 'max:255'],
            'featured_link_id' => ['nullable', 'integer', Rule::exists('links', 'id')->where(fn ($query) => $query->where('user_id', $request->user()->id)->where('is_active', true))],
            'animations_enabled' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:300'],
        ]);

        $profile = $request->user()->getOrCreateProfile();
        $socialLinks = $profile->social_links ?? [];
        foreach (['instagram', 'tiktok', 'youtube', 'facebook', 'twitter', 'whatsapp'] as $platform) {
            $socialLinks[$platform] = $validated['social_links'][$platform] ?? null;
        }

        $profile->update([
            'theme' => $validated['theme'],
            'button_style' => $validated['button_style'],
            'font' => $validated['font'],
            'social_links' => array_filter($socialLinks),
            'featured_link_id' => $validated['featured_link_id'] ?? null,
            'animations_enabled' => $request->boolean('animations_enabled'),
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
        ]);

        return back()->with('status', 'Tampilan berhasil diperbarui.');
    }
}
