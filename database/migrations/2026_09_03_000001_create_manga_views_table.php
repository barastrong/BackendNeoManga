<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manga_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manga_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('view_date', 10)->index(); // YYYY-MM-DD, biar gampang group per hari
            $table->string('period', 7)->default('daily'); // daily|weekly|monthly
            $table->unique(['manga_id', 'user_id', 'view_date']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manga_views');
    }
};
