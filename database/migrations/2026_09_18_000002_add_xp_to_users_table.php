<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('xp')->default(0)->after('password');
        });

        // Backfill XP dari riwayat baca: 10 XP per chapter yang pernah dibaca
        DB::statement('UPDATE users u SET xp = COALESCE((SELECT COUNT(*) * 10 FROM histories h WHERE h.user_id = u.id), 0)');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('xp');
        });
    }
};