<?php

namespace App\Http\Controllers;

use App\Models\LinkPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfilePageController extends Controller
{
    public function create(): View
    {
        return view('dashboard.profile-pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'username' => [
                'required', 'string', 'min:3', 'max:60', 'alpha_dash',
                'not_regex:/^(?:'.implode('|', array_map('preg_quote', PublicProfileController::RESERVED_USERNAMES)).')$/i',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (LinkPage::whereRaw('LOWER(username) = ?', [Str::lower((string) $value)])->exists()) {
                        $fail('URL publik tersebut sudah digunakan. Silakan pilih URL lain.');
                    }
                },
            ],
        ]);

        $user = $request->user();
        $page = DB::transaction(function () use ($user, $validated): LinkPage {
            $page = $user->pages()->create([
                'name' => $validated['name'],
                'username' => strtolower($validated['username']),
                'is_default' => false,
            ]);

            $page->profile()->create([
                'user_id' => $user->id,
                'bio' => null,
                'theme' => 'aurora',
                'button_style' => 'rounded',
                'background_type' => 'gradient',
                'background_value' => 'from-brand-500 to-indigo-600',
            ]);

            return $page;
        });

        $request->session()->put('active_link_page_id', $page->id);

        return redirect()->route('dashboard.index')->with('status', 'Treelink baru berhasil dibuat. Silakan mulai mengaturnya.');
    }

    public function switch(Request $request, LinkPage $page): RedirectResponse
    {
        abort_unless($page->user_id === $request->user()->id, 403);
        $request->session()->put('active_link_page_id', $page->id);

        return redirect()->route('dashboard.editor');
    }
}
