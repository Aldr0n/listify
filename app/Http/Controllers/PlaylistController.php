<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePlaylistRequest;
use App\Models\Playlist;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('playlists');
    }


    /**
     * Display the specified resource.
     */
    public function show(Playlist $playlist)
    {
        // return view('playlists-view', [
        //     'playlist' => $playlist,
        // ]);
        return view('playlist-view', [
            'playlist' => $playlist,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Playlist $playlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlaylistRequest $request, Playlist $playlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Playlist $playlist)
    {
        //
    }
}
