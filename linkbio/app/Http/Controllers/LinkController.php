<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LinkController extends Controller
{
    public function index(Request $request): View
    {
        $links = $request->user()->links()->get();

        return view('dashboard.links', [
            'links' => $links,
            'profile' => $request->user()->getOrCreateProfile(),
            'availableIcons' => Link::AVAILABLE_ICONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:2048', 'regex:/^(https?:\/\/|mailto:|tel:|whatsapp:).+/i'],
            'icon' => ['nullable', 'string', 'in:'.implode(',', Link::AVAILABLE_ICONS)],
        ], [
            'url.regex' => 'URL harus diawali dengan http://, https://, mailto:, atau tel:.',
        ]);

        $maxPosition = (int) $request->user()->links()->max('position');

        $request->user()->links()->create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'icon' => $validated['icon'] ?? 'link',
            'position' => $maxPosition + 1,
            'is_active' => true,
        ]);

        return back()->with('status', 'Link berhasil ditambahkan.');
    }

    public function update(Request $request, Link $link): RedirectResponse
    {
        $this->authorizeOwnership($link);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:2048', 'regex:/^(https?:\/\/|mailto:|tel:|whatsapp:).+/i'],
            'icon' => ['nullable', 'string', 'in:'.implode(',', Link::AVAILABLE_ICONS)],
        ]);

        $link->update($validated);

        return back()->with('status', 'Link berhasil diperbarui.');
    }

    public function destroy(Link $link): RedirectResponse
    {
        $this->authorizeOwnership($link);

        $link->delete();

        return back()->with('status', 'Link berhasil dihapus.');
    }

    public function toggle(Link $link): RedirectResponse
    {
        $this->authorizeOwnership($link);

        $link->update(['is_active' => ! $link->is_active]);

        return back()->with('status', $link->is_active ? 'Link diaktifkan.' : 'Link dinonaktifkan.');
    }

    /**
     * Persist new link order sent from the drag-and-drop UI.
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:links,id'],
        ]);

        $userLinkIds = $request->user()->links()->pluck('id')->all();

        foreach ($validated['order'] as $index => $linkId) {
            if (! in_array((int) $linkId, $userLinkIds, true)) {
                continue;
            }

            Link::where('id', $linkId)->update(['position' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }

    private function authorizeOwnership(Link $link): void
    {
        abort_unless($link->user_id === Auth::id(), 403);
    }
}
