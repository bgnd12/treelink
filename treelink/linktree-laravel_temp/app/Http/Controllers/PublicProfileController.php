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

        $shopProducts = [
            ['name' => 'Starter Kit', 'price' => 'Rp299.000', 'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Brand Mentoring', 'price' => 'Rp550.000', 'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=600&q=80'],
        ];

        $linkIdTypes = ['Content Creator', 'Brand', 'Product', 'Event'];

        return view('public.show', [
            'profileUser' => $user,
            'profile' => $profile,
            'links' => $links,
            'shopProducts' => $shopProducts,
            'linkIdTypes' => $linkIdTypes,
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