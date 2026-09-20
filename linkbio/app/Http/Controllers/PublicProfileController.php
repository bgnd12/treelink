<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function show(Request $request, string $username): View
    {
        $user = User::where('username', strtolower($username))
            ->where('is_active', true)
            ->firstOrFail();

        $profile = $user->getOrCreateProfile();
        $links = $user->links()->where('is_active', true)->orderBy('position')->get();

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
