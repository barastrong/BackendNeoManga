<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('reporter_id'); // user yang melapor
            $table->string('reason', 100)->default('lainnya'); // spam, pelecehan, spoiler, lainnya
            $table->string('status', 20)->default('pending'); // pending, resolved, dismissed
            $table->unsignedBigInteger('handled_by')->nullable(); // admin yang menangani
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();

            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('handled_by')->references('id')->on('users')->onDelete('set null');

            // Satu user hanya bisa lapor sekali per komentar
            $table->unique(['comment_id', 'reporter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_reports');
    }
};
