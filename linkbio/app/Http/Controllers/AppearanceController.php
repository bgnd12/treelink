<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppearanceController extends Controller
{
    public function edit(Request $request): View
    {
        $profile = $request->user()->getOrCreateProfile();

        return view('dashboard.appearance', [
            'profile' => $profile,
            'themes' => Profile::AVAILABLE_THEMES,
            'buttonStyles' => Profile::BUTTON_STYLES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'string', 'in:'.implode(',', array_keys(Profile::AVAILABLE_THEMES))],
            'button_style' => ['required', 'string', 'in:'.implode(',', Profile::BUTTON_STYLES)],
            'font' => ['required', 'string', 'in:sans,serif,mono'],
        ]);

        $request->user()->getOrCreateProfile()->update($validated);

        return back()->with('status', 'Tampilan berhasil diperbarui.');
    }
}
