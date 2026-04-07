<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiHistoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $latestHistorySubquery = History::selectRaw('MAX(id) as id')
            ->where('user_id', $user->id)
            ->groupBy('manga_id');

        $histories = History::with([
                'manga' => function ($query) {
                    $query->withCount('chapters')->withAvg('ratings', 'rating');
                },
                'chapter'
            ])
            ->whereIn('id', $latestHistorySubquery)
            ->latest('id')
            ->paginate(18);

        return response()->json($histories);
    }

    public function destroy(Request $request, History $history): JsonResponse
    {
        if ($history->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $history->delete();

        return response()->json(null, 204);
    }

    public function clearAll(Request $request): JsonResponse
    {
        $request->user()->histories()->delete();

        return response()->json(null, 204);
    }

    public function clearForManga(Request $request, Manga $manga): JsonResponse
    {
        History::where('user_id', $request->user()->id)
               ->where('manga_id', $manga->id)
               ->delete();

        return response()->json(null, 204);
    }
}