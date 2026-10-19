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
        $pages = $user->pages()
            ->with('profile')
            ->withCount(['links as active_links_count' => fn ($query) => $query->where('is_active', true)])
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        $totalViews = $user->profileViews()->count();
        $totalClicks = DB::table('link_clicks')
            ->join('links', 'links.id', '=', 'link_clicks.link_id')
            ->where('links.page_id', $user->currentPageId())
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
            'pages' => $pages,
            'activePageId' => $user->currentPageId(),
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
            'viewsLast7Days' => $viewsLast7Days,
            'topLinks' => $topLinks,
            'activeLinksCount' => $links->where('is_active', true)->count(),
        ]);
    }
}
