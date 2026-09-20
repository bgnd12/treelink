<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $user->getOrCreateProfile();

        return view('dashboard.profile', [
            'user' => $user,
            'profile' => $profile,
            'socialPlatforms' => Profile::SOCIAL_PLATFORMS,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'max:30', 'min:3',
                'regex:/^[a-zA-Z0-9_.]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'display_name' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:280'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
        ]);

        $profile = $user->getOrCreateProfile();

        $profileData = [
            'display_name' => $validated['display_name'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'social_links' => array_filter($validated['social_links'] ?? []),
        ];

        if ($request->hasFile('avatar')) {
            if ($profile->avatar_path) {
                Storage::disk('public')->delete($profile->avatar_path);
            }

            $profileData['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $profile->update($profileData);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    public function updateAccount(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return back()->with('status', 'Pengaturan akun berhasil diperbarui.');
    }

    public function destroyAccount(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm_delete' => ['required', 'accepted'],
        ]);

        $user = $request->user();
        \Illuminate\Support\Facades\Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Akun Anda telah dihapus.');
    }
}
