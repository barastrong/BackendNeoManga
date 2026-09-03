@extends('layouts.app')

@section('title', 'Edit Profil — NeoManga')

@section('content')
<div class="container-nm py-6 md:py-8">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <h1 class="font-display text-2xl font-bold text-slate-900 dark:text-white">
                <i class="fa-solid fa-gear text-[#ff2e4d] mr-2"></i>Edit Profil
            </h1>
            <a href="{{ route('user.profile') }}" class="text-xs font-semibold text-[#ff2e4d] hover:text-[#e62242] transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i>Kembali ke Profil
            </a>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-4 py-3 text-sm flex items-center gap-2" role="alert">
                <i class="fa-solid fa-circle-check"></i>
                <span>Profil berhasil diperbarui.</span>
            </div>
        @endif

        <div class="rounded-2xl bg-white dark:bg-slate-900 ring-1 ring-slate-200 dark:ring-white/10 p-5 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="rounded-2xl bg-white dark:bg-slate-900 ring-1 ring-slate-200 dark:ring-white/10 p-5 sm:p-8">
            @include('profile.partials.update-password-form')
        </div>

        <div class="rounded-2xl bg-white dark:bg-slate-900 ring-1 ring-slate-200 dark:ring-white/10 p-5 sm:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection