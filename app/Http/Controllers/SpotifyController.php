<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Http;
use Aerni\Spotify\Facades\Spotify;
use Illuminate\Http\Request;

class SpotifyController extends Controller
{
    public function redirectToSpotify()
    {
        $query = http_build_query([
            'client_id' => config('spotify.auth.client_id'),
            'response_type' => 'code',
            'redirect_uri' => config('spotify.auth.redirect_uri'),
            'scope' => 'playlist-read-private playlist-read-collaborative',
        ]);

        return redirect("https://accounts.spotify.com/authorize?$query");
    }

    public function handleSpotifyCallback(Request $request)
    {
        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'redirect_uri' => config('spotify.auth.redirect_uri'),
            'client_id' => config('spotify.auth.client_id'),
            'client_secret' => config('spotify.auth.client_secret'),
        ]);

        session(['spotify_access_token' => $response['access_token']]);
        return redirect('/');
    }

    public function getPlaylistCovers(Request $request)
    {
        $token = session('spotify_access_token');
        if (!$token) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $playlistId = $request->query('playlist_id');

        $response = Http::withToken($token)
            ->get("https://api.spotify.com/v1/playlists/$playlistId/tracks");

        $items = $response->json('items');

        $covers = collect($items)->map(function ($item) {
            return [
                'name' => $item['track']['name'],
                'cover' => $item['track']['album']['images'][0]['url'] ?? null,
            ];
        });

        return response()->json($covers);
    }
}
