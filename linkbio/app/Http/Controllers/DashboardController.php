<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $user->getOrCreateProfile();
        $links = $user->links()->get();

        $totalViews = $user->profileViews()->count();
        $totalClicks = DB::table('link_clicks')
            ->join('links', 'links.id', '=', 'link_clicks.link_id')
            ->where('links.user_id', $user->id)
            ->count();

        $viewsLast7Days = $user->profileViews()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $topLinks = $user->links()
            ->withCount('clicks')
            ->orderByDesc('clicks_count')
            ->take(5)
            ->get();

        return view('dashboard.overview', [
            'profile' => $profile,
            'links' => $links,
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
            'viewsLast7Days' => $viewsLast7Days,
            'topLinks' => $topLinks,
            'activeLinksCount' => $links->where('is_active', true)->count(),
        ]);
    }
}
