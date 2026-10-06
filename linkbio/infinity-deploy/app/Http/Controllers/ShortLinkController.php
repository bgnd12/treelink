<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use App\Models\User;
use Closure;
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
            } while ($this->addressIsTaken($slug));
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

    /**
     * Slugs are unique across the whole platform, so the same address can never
     * be claimed twice - not even by a different account. They also have to stay
     * routable: alpha_dash (letters, numbers, dashes, underscores) is a subset
     * of PublicProfileController::USERNAME_PATTERN, and the address is matched
     * case-insensitively so /My-Event and /my-event cannot both be claimed.
     */
    private function slugRules(?ShortLink $ignore = null): array
    {
        $unique = Rule::unique('short_links', 'slug');

        if ($ignore) {
            $unique = $unique->ignore($ignore->id);
        }

        return [
            Rule::requiredIf($ignore !== null),
            'string',
            'alpha_dash',
            'max:50',
            $unique,
            $this->addressNotReservedByAProfile(),
        ];
    }

    /**
     * A custom address is resolved before a public profile, so it must never be
     * allowed to take over the address of an existing username.
     */
    private function addressNotReservedByAProfile(): Closure
    {
        return function (string $attribute, $value, Closure $fail) {
            if (!is_string($value) || $value === '') {
                return;
            }

            if (User::whereRaw('LOWER(username) = ?', [Str::lower($value)])->exists()) {
                $fail(__('The address ":input" is already used as a public profile username. Please use a different address.', [
                    'input' => $value,
                ]));
            }
        };
    }

    private function destinationUrlRules(): array
    {
        return ['required', 'url', 'max:2048'];
    }

    /**
     * An address is unusable when it is already a custom address of somebody,
     * or the public profile username of somebody.
     */
    private function addressIsTaken(string $slug): bool
    {
        $slug = Str::lower($slug);

        return ShortLink::whereRaw('LOWER(slug) = ?', [$slug])->exists()
            || User::whereRaw('LOWER(username) = ?', [$slug])->exists();
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
