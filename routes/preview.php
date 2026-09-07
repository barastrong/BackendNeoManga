<?php
// TEMP preview (WAJIB dihapus setelah dipakai) — lihat profile.show sebagai user id=1
Route::get('/__preview_profile', function () {
    Auth::loginUsingId(1);
    return app(\App\Http\Controllers\ProfileController::class)->show(request());
});
