# Spotify Playlist Manager

A Laravel-based web application that allows users to manage and analyze their Spotify playlists. The application stores playlist data locally and provides searching and filtering capabilities.

## Features

-   User authentication and registration
-   Search playlists by keywords or direct URL/ID
-   View paginated list of saved playlists
-   Detailed playlist view with songs
-   Search songs within playlists
-   Sort and filter by audio features (tempo, loudness, danceability, etc.)
-   Local storage of playlist covers and track data
-   Relationship mapping between songs and playlists

## Requirements

-   PHP 8.1 or higher
-   Composer
-   Node.js & NPM
-   Database (MySQL, PostgreSQL, or SQLite)
-   Spotify Developer Account

## Installation

1. Clone the repository
   git clone https://github.com/yourusername/spotify-playlist-manager.git
   cd spotify-playlist-manager

2. Install dependencies
   composer install
   npm install

3. Configure environment variables
   cp .env.example .env
   php artisan key:generate

4. Configure Spotify API credentials
   SPOTIFY_CLIENT_ID=your_client_id
   SPOTIFY_CLIENT_SECRET=your_client_secret
   SPOTIFY_REDIRECT_URI=[your_app_url]/callback

5. Configure your database in `.env`:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=spotify_manager
   DB_USERNAME=root
   DB_PASSWORD=

7. Run migrations
   php artisan migrate

8. Start the queue worker (keep this running in a separate terminal)
   php artisan queue:work --tries=3 --timeout=90 --max-jobs=1000 --max-time=3600

9. Link storage for playlist covers
   php artisan storage:link

10. Start the development server or use Laravel Herd
   php artisan serve

Enjoy!
