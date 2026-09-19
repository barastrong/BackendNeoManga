<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LevelTitle;
use App\Models\Setting;
use App\Services\EngagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GamificationController extends Controller
{
    /** Halaman CMS: setting XP + daftar 100 title level. */
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        $titles = LevelTitle::orderBy('level')->get()->keyBy('level');
        $defaults = (new \ReflectionClass(EngagementService::class))->getConstant('LEVEL_TITLES');

        return view('admin.gamification.index', compact('settings', 'titles', 'defaults'));
    }

    /** Simpan setting XP per baca + cap harian. */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'xp_per_read'  => 'required|integer|min:1|max:1000',
            'daily_xp_cap' => 'required|integer|min:1|max:100000',
        ]);

        Setting::updateOrCreate(['key' => 'xp_per_read'], ['value' => $validated['xp_per_read']]);
        Setting::updateOrCreate(['key' => 'daily_xp_cap'], ['value' => $validated['daily_xp_cap']]);

        return back()->with('success', 'Pengaturan XP disimpan: ' . $validated['xp_per_read'] . ' XP/chapter, cap ' . $validated['daily_xp_cap'] . ' XP/hari.');
    }

    /** Simpan semua title level sekaligus (form satu halaman). */
    public function updateTitles(Request $request)
    {
        $validated = $request->validate([
            'titles'   => 'required|array|min:1|max:100',
            'titles.*' => 'required|string|max:60',
        ]);

        $changed = 0;
        foreach ($validated['titles'] as $level => $title) {
            LevelTitle::updateOrCreate(['level' => (int) $level], ['title' => trim($title)]);
            $changed++;
        }

        return back()->with('success', "$changed title level berhasil disimpan.");
    }

    /** Reset semua title ke default bawaan. */
    public function resetTitles()
    {
        $defaults = (new \ReflectionClass(EngagementService::class))->getConstant('LEVEL_TITLES');

        DB::transaction(function () use ($defaults) {
            LevelTitle::query()->delete();
            foreach ($defaults as $level => $title) {
                LevelTitle::create(['level' => $level, 'title' => $title]);
            }
        });

        return back()->with('success', 'Semua title level di-reset ke default (' . count($defaults) . ' level).');
    }
}