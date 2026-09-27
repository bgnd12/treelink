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

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:2048', 'regex:/^(https?:\/\/|mailto:|tel:|whatsapp:).+/i'],
            'icon' => ['nullable', 'string', 'in:'.implode(',', Link::AVAILABLE_ICONS)],
        ], [
            'url.regex' => 'URL harus diawali dengan http://, https://, mailto:, atau tel:.',
        ]);

        $maxPosition = (int) $request->user()->links()->max('position');

        $link = $request->user()->links()->create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'icon' => $validated['icon'] ?? 'link',
            'position' => $maxPosition + 1,
            'is_active' => true,
        ]);

        return $this->respond($request, $link, 'Link berhasil ditambahkan.', 201);
    }

    public function update(Request $request, Link $link): JsonResponse|RedirectResponse
    {
        $this->authorizeOwnership($link);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:2048', 'regex:/^(https?:\/\/|mailto:|tel:|whatsapp:).+/i'],
            'icon' => ['nullable', 'string', 'in:'.implode(',', Link::AVAILABLE_ICONS)],
        ]);

        $link->update($validated);

        return $this->respond($request, $link, 'Link berhasil diperbarui.');
    }

    public function destroy(Request $request, Link $link): JsonResponse|RedirectResponse
    {
        $this->authorizeOwnership($link);

        $link->delete();

        return $this->respond($request, null, 'Link berhasil dihapus.');
    }

    public function toggle(Request $request, Link $link): JsonResponse|RedirectResponse
    {
        $this->authorizeOwnership($link);

        $link->update(['is_active' => ! $link->is_active]);

        return $this->respond(
            $request,
            $link,
            $link->is_active ? 'Link diaktifkan.' : 'Link dinonaktifkan.'
        );
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

    /**
     * The editor talks JSON, while the legacy links page posts a normal form.
     */
    private function respond(Request $request, ?Link $link, string $message, int $status = 200): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['link' => $link?->fresh(), 'status' => 'ok'], $status);
        }

        return back()->with('status', $message);
    }
}
