<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\ProfileView;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'total_links' => Link::count(),
            'total_views' => ProfileView::count(),
            'total_clicks' => LinkClick::count(),
            'new_users_7d' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $latestUsers = User::withCount('links')
            ->latest()
            ->take(8)
            ->get();

        $mostViewedUsers = User::withCount('profileViews')
            ->orderByDesc('profile_views_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestUsers', 'mostViewedUsers'));
    }

    public function users(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $users = User::withCount(['links', 'profileViews'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }

    public function show(User $user): View
    {
        $user->load('profile', 'links.clicks');

        $totalClicks = $user->links->sum(fn ($link) => $link->clicks->count());

        return view('admin.user-show', [
            'targetUser' => $user,
            'totalClicks' => $totalClicks,
            'totalViews' => $user->profileViews()->count(),
        ]);
    }

    public function toggleActive(User $user): RedirectResponse
    {
        abort_if($user->is_admin, 403, 'Tidak dapat menonaktifkan akun admin.');

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('status', $user->is_active ? 'Akun diaktifkan kembali.' : 'Akun dinonaktifkan.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is_admin, 403, 'Tidak dapat menghapus akun admin.');

        $user->delete();

        return redirect()->route('admin.users')->with('status', 'Akun pengguna berhasil dihapus.');
    }
}
