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
        Schema::create('tracks', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->string('spotify_id')->unique();
            $table->string('name');
            $table->integer('duration_ms');
            $table->json('artists')->nullable();
            $table->json('album')->nullable();
            $table->string('thumbnail_id')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('href')->nullable();
            $table->integer('popularity')->nullable();
            $table->integer('track_number')->nullable();
            $table->boolean('explicit')->nullable();
            $table->json('available_markets')->nullable();
        });

        /*
         * Pivot table for playlist <-> track relationship
         */
        Schema::create('playlist_track', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->foreignId('playlist_id')->constrained('playlists')->onDelete('cascade');
            $table->foreignId('track_id')->constrained('tracks')->onDelete('cascade');

            $table->unique(['playlist_id', 'track_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('playlist_track');
    }
};
