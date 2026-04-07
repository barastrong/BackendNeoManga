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
        Schema::create('mangas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('alternative_title');
            $table->string('slug')->unique(); 
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('artist')->nullable();
            $table->enum('status',['hiatus', 'ongoing', 'completed','cancelled'])->default('ongoing');
            $table->string('cover_image')->nullable();
            $table->enum('type', ['manga', 'manhwa', 'manhua','webtoon'])->default('manga');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mangas');
    }
};
