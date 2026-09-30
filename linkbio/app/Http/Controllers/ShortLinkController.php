<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            'destination_url' => $this->destinationUrlRules(),
            'slug' => $this->slugRules(),
        ], $this->validationMessages());

        $slug = $request->slug;
        if (!$slug) {
            do {
                $slug = Str::lower(Str::random(6));
            } while (ShortLink::where('slug', $slug)->exists());
        }

        auth()->user()->shortLinks()->create([
            'slug' => $slug,
            'destination_url' => $request->destination_url,
            'is_active' => true,
        ]);

        return back()->with('status', __('Short link created successfully.'));
    }

    public function update(Request $request, ShortLink $shortLink)
    {
        if ($shortLink->user_id !== $request->user()->id) abort(403);

        $request->validate([
            'destination_url' => $this->destinationUrlRules(),
            'slug' => $this->slugRules($shortLink),
        ], $this->validationMessages());

        $shortLink->update([
            'destination_url' => $request->destination_url,
            'slug' => $request->slug,
        ]);

        return back()->with('status', __('Short link updated successfully.'));
    }

    public function destroy(Request $request, ShortLink $shortLink)
    {
        if ($shortLink->user_id !== $request->user()->id) abort(403);
        $shortLink->delete();
        return back()->with('status', __('Short link deleted successfully.'));
    }

    public function toggle(Request $request, ShortLink $shortLink)
    {
        if ($shortLink->user_id !== $request->user()->id) abort(403);
        $shortLink->update(['is_active' => !$shortLink->is_active]);
        return back()->with('status', __('Short link status updated.'));
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

    /**
     * Slugs are unique across the whole platform, so the same address can never
     * be claimed twice - not even by a different account.
     */
    private function slugRules(?ShortLink $ignore = null): array
    {
        $unique = Rule::unique('short_links', 'slug');

        if ($ignore) {
            $unique = $unique->ignore($ignore->id);
        }

        return [Rule::requiredIf($ignore !== null), 'string', 'alpha_dash', 'max:50', $unique];
    }

    private function destinationUrlRules(): array
    {
        return ['required', 'url', 'max:2048'];
    }

    /**
     * @return array<string, string>
     */
    private function validationMessages(): array
    {
        return [
            'slug.required' => __('Please enter a custom address for your short link.'),
            'slug.unique' => __('The address ":input" is already taken by someone else. Please use a different address.'),
            'slug.alpha_dash' => __('The address may only contain letters, numbers, dashes and underscores.'),
            'slug.max' => __('The address may not be longer than :max characters.'),
            'destination_url.required' => __('Please enter the destination URL.'),
            'destination_url.url' => __('The destination URL is not valid. Include http:// or https://'),
            'destination_url.max' => __('The destination URL may not be longer than :max characters.'),
        ];
    }
}
