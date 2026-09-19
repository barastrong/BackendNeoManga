@extends('layouts.admin')

@section('title', 'Gamifikasi — Admin NeoManga')
@section('page-title', 'Gamifikasi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/gamification/index.css') }}">

<div>
    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="gam-eyebrow"><i class="fa-solid fa-gamepad mr-1.5"></i>Gamifikasi</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Kelola Sistem</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">Gamifikasi</h1>
            <p class="text-sm text-slate-400 mt-1">Atur XP per baca, batas harian, dan title level user.</p>
        </div>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="mt-5 flex items-center gap-3 px-5 py-4 rounded-2xl border text-sm" style="background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.25);color:#6ee7b7">
            <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- STATS --}}
    <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="gam-stat">
            <span class="ic" style="background:rgba(255,46,77,.13);color:#ff2e4d"><i class="fa-solid fa-bolt"></i></span>
            <div><p class="lbl">XP / Chapter</p><p class="val">{{ $settings['xp_per_read'] ?? 5 }}</p></div>
        </div>
        <div class="gam-stat">
            <span class="ic" style="background:rgba(56,189,248,.13);color:#38bdf8"><i class="fa-solid fa-hourglass-half"></i></span>
            <div><p class="lbl">Cap XP / Hari</p><p class="val">{{ $settings['daily_xp_cap'] ?? 100 }}</p></div>
        </div>
        <div class="gam-stat">
            <span class="ic" style="background:rgba(52,211,153,.13);color:#34d399"><i class="fa-solid fa-ranking-star"></i></span>
            <div><p class="lbl">Total Level</p><p class="val">100</p></div>
        </div>
        <div class="gam-stat">
            <span class="ic" style="background:rgba(167,139,250,.13);color:#a78bfa"><i class="fa-solid fa-trophy"></i></span>
            <div><p class="lbl">Menuju Lv 100</p><p class="val">{{ number_format(100*100 + 47*100 - 48) }} XP</p></div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- SETTINGS XP --}}
        <div class="gam-card">
            <div class="gam-card-head">
                <i class="fa-solid fa-gear"></i>
                <div>
                    <h2>Pengaturan XP</h2>
                    <p>Nilai yang dipakai sistem level &amp; cap harian.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.gamification.settings') }}" class="mt-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="gam-label" for="xp_per_read">XP per Chapter Baru</label>
                    <input type="number" id="xp_per_read" name="xp_per_read" class="gam-input" min="1" max="1000" required value="{{ $settings['xp_per_read'] ?? 5 }}">
                    <p class="gam-hint">Diberikan saat user pertama kali baca sebuah chapter.</p>
                </div>
                <div>
                    <label class="gam-label" for="daily_xp_cap">Batas XP Harian</label>
                    <input type="number" id="daily_xp_cap" name="daily_xp_cap" class="gam-input" min="1" max="100000" required value="{{ $settings['daily_xp_cap'] ?? 100 }}">
                    <p class="gam-hint">Max XP per hari per user (anti-farm).</p>
                </div>
                <button type="submit" class="gam-btn gam-btn-primary w-full">
                    <i class="fa-solid fa-floppy-disk"></i>Simpan Pengaturan
                </button>
            </form>
        </div>

        {{-- TITLE LEVEL (edit 100) --}}
        <div class="gam-card lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="gam-card-head">
                    <i class="fa-solid fa-medal"></i>
                    <div>
                        <h2>Title Level 1–100</h2>
                        <p>Klik field untuk edit — tersimpan semua saat tekan Simpan.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.gamification.titles.reset') }}" onsubmit="return confirm('Reset semua title ke default?')">
                    @csrf
                    <button type="submit" class="gam-btn gam-btn-ghost">
                        <i class="fa-solid fa-rotate-left"></i>Reset ke Default
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('admin.gamification.titles') }}" class="mt-5">
                @csrf
                @method('PUT')
                <div class="gam-grid">
                    @for ($lvl = 1; $lvl <= 100; $lvl++)
                        <div class="gam-lvl">
                            <span class="gam-lvl-n">Lv {{ $lvl }}</span>
                            <input
                                type="text"
                                name="titles[{{ $lvl }}]"
                                class="gam-lvl-input"
                                maxlength="60"
                                value="{{ $titles[$lvl]->title ?? ($defaults[$lvl] ?? '') }}"
                                placeholder="Title Lv {{ $lvl }}">
                        </div>
                    @endfor
                </div>

                <div class="mt-6 flex items-center justify-between gap-3">
                    <p class="gam-hint m-0">Menuju Lv {{ 100 }} butuh {{ number_format(100*100 + 47*100 - 48) }} XP total.</p>
                    <button type="submit" class="gam-btn gam-btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>Simpan Semua Title
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection