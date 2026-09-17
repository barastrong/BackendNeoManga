<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // otp_code dulunya varchar(6) (plaintext). Sekarang dipakai buat hash
        // bcrypt (60 char) — wajib diperlebar.
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp_code', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp_code', 6)->nullable()->change();
        });
    }
};