<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\MangaView;
use App\Models\User;
use App\Services\EngagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $period = in_array($request->get('period'), ['today', 'week', 'month']) ? $request->get('period') : 'week';

        $since = match ($period) {
            'today' => now()->startOfDay(),
            'month' => now()->startOfMonth(),
            default => now()->startOfWeek(),
        };

        // User paling banyak baca (dari manga_views per periode) — guest (user_id null) dilewati
        $counts = MangaView::whereNotNull('user_id')
            ->where('view_date', '>=', $since->toDateString())
            ->select('user_id')
            ->selectRaw('COUNT(*) as reads_count')
            ->groupBy('user_id')
            ->pluck('reads_count', 'user_id')
            ->sortDesc()
            ->take(10);

        $users = collect();
        if ($counts->isNotEmpty()) {
            $users = User::whereIn('id', $counts->keys())
                ->get()
                ->map(function (User $u) use ($counts) {
                    $u->reads_count = (int) ($counts[$u->id] ?? 0);
                    $u->level = EngagementService::levelFor((int) $u->xp);
                    return $u;
                })
                ->sortByDesc('reads_count')
                ->values();
        }

        return view('ranking', compact('users', 'period'));
    }
}