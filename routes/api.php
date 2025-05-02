<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\SpotifyController;
use Illuminate\Support\Facades\Storage;

Route::get('/spotify/auth', [SpotifyController::class, 'redirectToSpotify'])->name('spotify.auth');
Route::get('/spotify/callback', [SpotifyController::class, 'handleSpotifyCallback'])->name('spotify.callback');
Route::get('/spotify/playlist', [SpotifyController::class, 'getPlaylistCovers'])->name('spotify.playlist.covers');

//Route::get('/api/images', function () {
//    $files = Storage::files('spotify/images');
//
//    return collect($files)->map(function ($file) {
//        return [
//            'url' => url(str_replace('spotify/images/', 'images/', $file)),
//            'name' => basename($file),
//        ];
//    });
//})->name('spotify.images');

//Route::get('/api/images', function () {
//    $files = Storage::files('spotify/images');
//
//    // Sort alphabetically by filename
//    $sorted = collect($files)
//        ->sort()
//        ->values()
//        ->map(function ($file) {
//            return [
//                'cover' => url(str_replace('spotify/images/', 'images/', $file)),
//                'name' => basename($file),
//            ];
//        });
//
//    return response()->json($sorted);
//})->name('spotify.images');

Route::get('images', function () {
    $files = Storage::disk('public')->files('spotify/images');

    // Sort by numeric index prefix (e.g., 001-title.jpg → 1)
    $sorted = collect($files)
        ->sortBy(function ($file) {
            $filename = basename($file);
            preg_match('/^(\d+)-/', $filename, $matches);
            return isset($matches[1]) ? (int) $matches[1] : PHP_INT_MAX;
        })
        ->values()
        ->map(function ($file) {
            return [
                'cover' => Storage::disk('public')->url($file),
                'name' => basename($file),
            ];
        });

    return response()->json($sorted);
})->name('spotify.images');

//Route::get('/images', function () {
////    $files = Storage::files('spotify/images');
//    $files = Storage::disk('public')->files('spotify/images');
//
//    $sorted = collect($files)
//        ->sortBy(function ($file) {
//            $filename = basename($file);
//            preg_match('/^(\d+)-/', $filename, $matches);
//            return isset($matches[1]) ? (int) $matches[1] : PHP_INT_MAX;
//        })
//        ->values()
//        ->map(function ($file) {
//            $filename = basename($file);
//            return [
//                'cover' => url("/images/{$filename}"),
//                'name' => $filename,
//            ];
//        });
//
//    return response()->json($sorted);
//})->name('spotify.images');
