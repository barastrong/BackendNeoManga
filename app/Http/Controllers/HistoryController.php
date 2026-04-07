<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::id();

        $latestHistorySubquery = History::selectRaw('MAX(id) as id')
            ->where('user_id', $userId)
            ->groupBy('manga_id');

        $histories = History::with(['manga', 'chapter'])
            ->whereIn('id', $latestHistorySubquery)
            ->latest('id') 
            ->paginate(18); 

        return view('manga.history', compact('histories'));
    }

    /**
     *
     * @param  \App\Models\History  $history
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(History $history)
    {
        if ($history->user_id !== Auth::id()) {
            abort(403);
        }
        $history->delete();
        return redirect()->back()->with('success', 'Riwayat berhasil dihapus.');
    }

    /**
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        History::where('user_id', Auth::id())->delete();

        return redirect()->route('history.index')->with('success', 'Semua riwayat telah berhasil dibersihkan.');
    }

        /**
     *
     * @param  int  $mangaId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetForManga($mangaId)
    {
        History::where('user_id', Auth::id())
               ->where('manga_id', $mangaId)
               ->delete();

        return redirect()->back()->with('success', 'Reading history for this manga has been reset.');
    }
}