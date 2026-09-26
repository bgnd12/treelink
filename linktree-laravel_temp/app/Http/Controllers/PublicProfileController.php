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
        $user = User::where('username', $username)->firstOrFail();
        $profile = $user->getOrCreateProfile();
        $links = $user->links()->where('is_active', true)->get();

        return view('public.show', [
            'profileUser' => $user,
            'profile' => $profile,
            'links' => $links,
        ]);
    }

    public function redirect(Request $request, string $username, string $link): RedirectResponse
    {
        $user = User::where('username', $username)->firstOrFail();
        $resolvedLink = $user->links()->where('is_active', true)->where(function ($query) use ($link) {
            $query->where('slug', $link)->orWhere('id', ctype_digit($link) ? (int) $link : 0);
        })->firstOrFail();

        $resolvedLink->increment('clicks_count');

        return redirect()->away($resolvedLink->url);
    }
}