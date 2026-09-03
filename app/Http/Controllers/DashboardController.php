<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter;
use App\Services\ViewTrackingService;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1) Section "Populer" — berdasar view tracking, filter periode: today|week|month
        $period = in_array($request->get('period'), ['today', 'week', 'month']) ? $request->get('period') : 'week';

        $popularMangas = Cache::remember("popular_mangas_{$period}", 300, function () use ($period) {
            return ViewTrackingService::popular($period, 12);
        });

        // 2) Section "Update Terbaru" — manga dengan chapter published, diurut chapter terbaru
        $mangas = Manga::with(['latestPublishedChapter'])
            ->withAvg('ratings', 'rating')
            ->whereHas('chapters', fn($q) => $q->where('status', 'published'))
            ->orderByDesc(
                Chapter::select('created_at')
                    ->whereColumn('manga_id', 'mangas.id')
                    ->where('status', 'published')
                    ->latest()
                    ->limit(1)
            )
            ->paginate(12); // 3 baris x 4 kolom

        return view('dashboard', compact('mangas', 'popularMangas', 'period'));
    }
}
