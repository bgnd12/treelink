<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    public function index()
    {
        $shortLinks = auth()->user()->shortLinks()->latest()->get();
        return view('dashboard.short-links.index', compact('shortLinks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'destination_url' => 'required|url|max:2048',
            'slug' => 'nullable|string|alpha_dash|max:50|unique:short_links,slug',
        ]);

        $slug = $request->slug;
        if (!$slug) {
            do {
                $slug = Str::random(6);
            } while (ShortLink::where('slug', $slug)->exists());
        }

        auth()->user()->shortLinks()->create([
            'slug' => $slug,
            'destination_url' => $request->destination_url,
            'is_active' => true,
        ]);

        return back()->with('status', 'Short link created successfully.');
    }

    public function update(Request $request, ShortLink $shortLink)
    {
        if ($shortLink->user_id !== auth()->id()) abort(403);

        $request->validate([
            'destination_url' => 'required|url|max:2048',
            'slug' => 'required|string|alpha_dash|max:50|unique:short_links,slug,' . $shortLink->id,
        ]);

        $shortLink->update($request->only('destination_url', 'slug'));

        return back()->with('status', 'Short link updated successfully.');
    }

    public function destroy(ShortLink $shortLink)
    {
        if ($shortLink->user_id !== auth()->id()) abort(403);
        $shortLink->delete();
        return back()->with('status', 'Short link deleted successfully.');
    }

    public function toggle(ShortLink $shortLink)
    {
        if ($shortLink->user_id !== auth()->id()) abort(403);
        $shortLink->update(['is_active' => !$shortLink->is_active]);
        return back()->with('status', 'Short link status updated.');
    }

    public function resolve($slug)
    {
        $shortLink = ShortLink::where('slug', $slug)->first();
        
        if (!$shortLink || !$shortLink->is_active) {
            abort(404);
        }

        $shortLink->increment('clicks');
        return redirect()->away($shortLink->destination_url);
    }
}
