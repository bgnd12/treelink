<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    /**
     * Words that are already claimed by a real route (admin panel, auth,
     * dashboard, assets). They are matched first, so a public profile can
     * never take them over — and a user cannot register one of them and
     * end up with a profile URL that opens the wrong page.
     */
    public const RESERVED_USERNAMES = [
        'admin', 'dashboard', 'login', 'register', 'logout', 'lang',
        'forgot-password', 'reset-password', 'email', 'verify-email',
        'api', 'storage', 'build', 'up', 'assets', 'css', 'js',
        'images', 'img', 'telescope', 'horizon', 'sanctum', 'nova',
        'pulse', 'livewire', 'webhooks', 'docs', 'help', 'about',
    ];

    /** Route constraint: a valid username that is not a reserved word. */
    public const USERNAME_PATTERN = '^(?!(?:'
        .'admin|dashboard|login|register|logout|lang|forgot-password|reset-password|email'
        .'|verify-email|api|storage|build|up|assets|css|js|images|img|telescope|horizon'
        .'|sanctum|nova|pulse|livewire|webhooks|docs|help|about'
        .")$)[A-Za-z0-9_.]+$";

    public function show(Request $request, string $username)
    {
        // First check if this is a short link slug
        $shortLink = \App\Models\ShortLink::where('slug', $username)->where('is_active', true)->first();
        if ($shortLink) {
            $shortLink->increment('clicks');
            return redirect()->away($shortLink->destination_url);
        }

        $user = User::where('username', strtolower($username))
            ->where('is_active', true)
            ->firstOrFail();

        $profile = $user->getOrCreateProfile();
        $links = $user->links()->where('is_active', true)->orderBy('position')->get();
        $products = $user->products()->where('is_active', true)->get();

        // Record a profile view (skip if the owner is previewing their own page).
        if (! $request->user() || $request->user()->id !== $user->id) {
            $user->profileViews()->create([
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'referrer' => substr((string) $request->headers->get('referer'), 0, 255),
            ]);
        }

        return view('profile.show', [
            'profileUser' => $user,
            'profile' => $profile,
            'links' => $links,
            'products' => $products,
        ]);
    }

    public function redirectLink(Request $request, string $username, Link $link): RedirectResponse
    {
        abort_unless(strtolower($link->user->username) === strtolower($username), 404);
        abort_unless($link->is_active, 404);

        $link->clicks()->create([
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'referrer' => substr((string) $request->headers->get('referer'), 0, 255),
        ]);

        return redirect()->away($link->url);
    }
}
