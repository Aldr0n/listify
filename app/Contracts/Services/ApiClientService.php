<?php

namespace App\Contracts\Services;

use App\Models\SpotifyToken;

interface ApiClientService
{
    /**
     * Fetch user's Spotify playlists
     * @param string $spotifyUserId
     * @param SpotifyToken $token
     * @return array
     */
    public function fetchUserPlaylists(string $spotifyUserId, SpotifyToken $token): array;

    /**
     * Fetch user's Spotify profile data
     * @param string $spotifyUserId
     * @param SpotifyToken $token
     * @return array
     */
    public function fetchUserProfile(string $spotifyUserId, SpotifyToken $token): array;

    /**
     * Fetch tracks from a playlist
     * @param string $playlistId
     * @param SpotifyToken $token
     * @return array
     */
    public function fetchPlaylistTracks(string $playlistId, SpotifyToken $token): array;

    /**
     * Refresh Spotify access token
     * @param int $userId
     * @return array
     */
    public function refreshToken(int $userId): array;
}

