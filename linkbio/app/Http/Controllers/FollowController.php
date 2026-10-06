<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FollowController extends Controller
{
    public function toggle(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->is_active, 404);
        abort_if($request->user()->is($user), 422, 'Kamu tidak bisa mengikuti akun sendiri.');

        $isFollowing = $request->user()->following()->whereKey($user->id)->exists();

        if ($isFollowing) {
            $request->user()->following()->detach($user->id);
        } else {
            $request->user()->following()->syncWithoutDetaching([$user->id]);
        }

        return back()->with('status', $isFollowing ? 'Kamu berhenti mengikuti '.$user->name.'.' : 'Kamu sekarang mengikuti '.$user->name.'.');
    }

    public function connections(string $username, string $connection): View
    {
        abort_unless(in_array($connection, ['followers', 'following'], true), 404);

        $profileUser = User::where('username', strtolower($username))
            ->where('is_active', true)
            ->firstOrFail();
        $relation = $connection === 'followers' ? $profileUser->followers() : $profileUser->following();

        return view('profile.connections', [
            'profileUser' => $profileUser,
            'connection' => $connection,
            'accounts' => $relation->with('profile')->withCount('followers')->paginate(24),
        ]);
    }
}
