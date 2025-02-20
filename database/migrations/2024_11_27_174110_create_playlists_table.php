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
        Schema::create('playlists', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('spotify_id');
            $table->foreignId('user_id')->constrained('users');
            $table->string('thumbnail_id')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->json('map');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
