<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom daily XP (cap harian anti-farm) + re-backfill XP dengan rate 5/chapter
     * biar konsisten dengan XP_PER_READ yang baru.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('daily_xp')->default(0)->after('xp');
            $table->date('daily_xp_date')->nullable()->after('daily_xp');
        });

        DB::statement('UPDATE users SET xp = COALESCE((SELECT COUNT(*) FROM histories WHERE histories.user_id = users.id), 0) * 5');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['daily_xp', 'daily_xp_date']);
        });
    }
};