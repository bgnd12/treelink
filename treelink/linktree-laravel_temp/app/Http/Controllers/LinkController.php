<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $user->getOrCreateProfile();
        $links = $user->links;
        $availableIcons = Link::AVAILABLE_ICONS;

        return view('dashboard.links', compact('user', 'profile', 'links', 'availableIcons'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'slug' => ['nullable', 'string', 'max:80', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'icon' => ['nullable', 'string', 'in:'.implode(',', Link::AVAILABLE_ICONS)],
            'content_tab' => ['nullable', 'string'],
        ]);

        $maxPosition = $request->user()->links()->max('position') ?? 0;

        $slug = $this->uniqueSlug($request->user()->id, $data['slug'] ?? $data['title']);
        $link = $request->user()->links()->create([
            'title' => $data['title'],
            'url' => $data['url'],
            'slug' => $slug,
            'icon' => $data['icon'] ?? 'link',
            'position' => $maxPosition + 1,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Link berhasil ditambahkan.',
                'link' => $this->serialize($link),
            ], 201);
        }

        return back()->withInput($request->only('content_tab'))->with('status', 'Link berhasil ditambahkan.');
    }

    public function update(Request $request, Link $link): RedirectResponse|JsonResponse
    {
        abort_unless($link->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'slug' => ['nullable', 'string', 'max:80', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'icon' => ['nullable', 'string', 'in:'.implode(',', Link::AVAILABLE_ICONS)],
            'content_tab' => ['nullable', 'string'],
        ]);

        $link->update([
            'title' => $data['title'],
            'url' => $data['url'],
            'slug' => $this->uniqueSlug($request->user()->id, $data['slug'] ?? $link->title, $link->id),
            'icon' => $data['icon'] ?? $link->icon,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Link berhasil diperbarui.',
                'link' => $this->serialize($link),
            ]);
        }

        return back()->withInput($request->only('content_tab'))->with('status', 'Link berhasil diperbarui.');
    }

    public function toggle(Request $request, Link $link): RedirectResponse|JsonResponse
    {
        abort_unless($link->user_id === $request->user()->id, 403);

        $link->update(['is_active' => ! $link->is_active]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $link->is_active ? 'Link diaktifkan.' : 'Link dinonaktifkan.',
                'link' => $this->serialize($link),
            ]);
        }

        return back()->with('status', $link->is_active ? 'Link diaktifkan.' : 'Link dinonaktifkan.');
    }

    public function reorder(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        $ids = $data['order'];

        foreach ($ids as $index => $id) {
            Link::query()
                ->where('user_id', $request->user()->id)
                ->where('id', $id)
                ->update(['position' => $index + 1]);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Urutan berhasil disimpan.']);
        }

        return back()->with('status', 'Urutan link berhasil diubah.');
    }

    public function destroy(Request $request, Link $link): RedirectResponse|JsonResponse
    {
        abort_unless($link->user_id === $request->user()->id, 403);

        $link->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Link berhasil dihapus.', 'id' => $link->id]);
        }

        return back()->with('status', 'Link berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function serialize(Link $link): array
    {
        return [
            'id' => $link->id,
            'title' => $link->title,
            'url' => $link->url,
            'slug' => $link->slug,
            'short_url' => url('/'.$link->user->username.'/'.$link->slug),
            'icon' => $link->icon,
            'is_active' => $link->is_active,
            'position' => $link->position,
            'clicks' => $link->clicks_count,
        ];
    }

    private function uniqueSlug(int $userId, string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'link';
        $slug = $base;
        $counter = 2;
        while (Link::where('user_id', $userId)->where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '<>', $ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
