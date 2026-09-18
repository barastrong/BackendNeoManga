<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ViewTrackingService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $period = in_array($request->get('period'), ['today', 'week', 'month']) ? $request->get('period') : 'week';

        $mangas = ViewTrackingService::popular($period, 10);

        return view('ranking', compact('mangas', 'period'));
    }
}