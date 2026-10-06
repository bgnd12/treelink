<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $totalViews = $user->profileViews()->count();
        $totalClicks = DB::table('link_clicks')
            ->join('links', 'links.id', '=', 'link_clicks.link_id')
            ->where('links.user_id', $user->id)
            ->count();

        $ctr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 1) : 0;

        $linkStats = $user->links()
            ->withCount('clicks')
            ->orderByDesc('clicks_count')
            ->get();

        return view('dashboard.analytics', [
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
            'ctr' => $ctr,
            'linkStats' => $linkStats,
            'uniqueVisitors' => $user->profileViews()->distinct('ip_address')->count('ip_address'),
        ]);
    }

    /**
     * Return last-30-days daily views & clicks for the Chart.js graph.
     */
    public function chartData(Request $request): JsonResponse
    {
        $user = $request->user();
        $days = collect(range(29, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));

        $views = $user->profileViews()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $clicks = DB::table('link_clicks')
            ->join('links', 'links.id', '=', 'link_clicks.link_id')
            ->selectRaw('DATE(link_clicks.created_at) as date, COUNT(*) as total')
            ->where('links.user_id', $user->id)
            ->where('link_clicks.created_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        return response()->json([
            'labels' => $days->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M'))->values(),
            'views' => $days->map(fn ($d) => $views[$d] ?? 0)->values(),
            'clicks' => $days->map(fn ($d) => $clicks[$d] ?? 0)->values(),
        ]);
    }
}
