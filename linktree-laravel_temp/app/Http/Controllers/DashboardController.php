<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $user->getOrCreateProfile();
        $links = $user->links;
        $profileSettings = $profile->settings();

        $editor = [
            'tab' => session('editor_tab', request()->old('tab', 'content')),
            'content_tab' => session('editor_content_tab', request()->old('content_tab', 'links')),
            'profile' => [
                'name' => $user->name,
                'username' => $user->username,
                'display_name' => $profile->display_name,
                'bio' => $profile->bio,
                'avatar_url' => $profile->avatar_url,
            ],
            'socials' => array_filter($profile->social_links ?? []),
            'design' => [
                'theme' => $profile->theme,
                'button_style' => in_array($profile->button_style, array_keys(Profile::BUTTON_STYLES), true)
                    ? $profile->button_style
                    : array_key_first(Profile::BUTTON_STYLES),
                'font' => $profile->font,
                'settings' => $profileSettings,
            ],
            'links' => $links->map(fn (Link $link) => [
                'id' => $link->id,
                'title' => $link->title,
                'url' => $link->url,
                'icon' => $link->icon,
                'is_active' => $link->is_active,
                'position' => $link->position,
                'clicks' => $link->clicks_count,
            ])->values()->all(),
            'themes' => collect(Profile::AVAILABLE_THEMES)->map(fn (array $t) => [
                'label' => $t['label'],
                'from' => $t['from'],
                'to' => $t['to'],
                'text' => $t['text'],
                'from_hex' => $t['from_hex'],
                'to_hex' => $t['to_hex'],
                'text_hex' => $t['text_hex'],
            ])->all(),
            'icons' => Link::AVAILABLE_ICONS,
            'icon_colors' => Link::ICON_COLORS,
            'urls' => [
                'public' => $user->publicUrl(),
                'profile_update' => route('dashboard.profile.update'),
                'settings_update' => route('dashboard.settings.update'),
                'links_store' => route('dashboard.links.store'),
                'links_reorder' => route('dashboard.links.reorder'),
            ],
        ];

        return view('dashboard.editor', compact('user', 'profile', 'links', 'editor'));
    }
}
