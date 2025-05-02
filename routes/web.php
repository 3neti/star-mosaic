<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('album-gallery', function () {
    return Inertia::render('AlbumGallery');
})->name('album-gallery');

use Aerni\Spotify\Facades\Spotify;

//Route::get('test', function () {
//    $data = Spotify::playlist('5pn8zwd2XxLLZIRyNWhdma')->get();
//
//
//    return $data;
//})->name('test');
//
//Route::get('test', function () {
//    $data = Spotify::playlist('5pn8zwd2XxLLZIRyNWhdma')->get();
//
//    // Ensure directory exists
//    Storage::makeDirectory('spotify');
//
//    // Save the data to a JSON file
//    Storage::put('spotify/playlist.json', json_encode($data, JSON_PRETTY_PRINT));
//
//    return response()->json([
//        'message' => 'Playlist data saved to storage/app/spotify/playlist.json',
//        'preview' => array_slice($data, 0, 3), // optional preview
//    ]);
//})->name('test');

use Illuminate\Support\Str;

//Route::get('extract', function () {
//    $playlistId = '5pn8zwd2XxLLZIRyNWhdma';
//
//    Storage::makeDirectory('spotify');
//    Storage::makeDirectory('spotify/images');
//
//    $playlistData = Spotify::playlist($playlistId)->get();
//    Storage::put('spotify/playlist.json', json_encode($playlistData, JSON_PRETTY_PRINT));
//
//    $downloadedAlbums = [];
//    $skipped = 0;
//    $downloaded = 0;
//    $offset = 0;
//    $limit = 100;
//
//    do {
//        $response = Spotify::playlistTracks($playlistId)
//            ->limit($limit)
//            ->offset($offset)
//            ->get();
//
//        foreach ($response['items'] as $i => $item) {
//            $track = $item['track'];
//            $album = $track['album'] ?? null;
//            $albumName = $album['name'] ?? null;
//            $albumId = $album['id'] ?? null;
//            $images = $album['images'] ?? [];
//
//            if (!$albumName || !$albumId || !isset($images[0]['url'])) {
//                continue;
//            }
//
//            if (in_array($albumName, $downloadedAlbums)) {
//                $skipped++;
//                continue;
//            }
//
//            // Synchronized index based on offset + current loop index
//            $index = $offset + $i + 1;
//            $slug = Str::slug("{$index}-{$albumName}-{$albumId}") . '.jpg';
//            $filename = "spotify/images/{$slug}";
//
//            if (Storage::exists($filename)) {
//                $skipped++;
//                continue;
//            }
//
//            $imageContents = Http::get($images[0]['url'])->body();
//            Storage::put($filename, $imageContents);
//
//            $downloadedAlbums[] = $albumName;
//            $downloaded++;
//        }
//
//        $offset += $limit;
//        $more = isset($response['next']) && $response['next'] !== null;
//
//    } while ($more);
//
//    return response()->json([
//        'message' => 'Album covers processed.',
//        'downloaded' => $downloaded,
//        'skipped' => $skipped,
//    ]);
//})->name('extract');


//Route::get('extract', function () {
//    $playlistId = '5pn8zwd2XxLLZIRyNWhdma';
//
//    Storage::makeDirectory('spotify/images');
//
//    $playlistData = Spotify::playlist($playlistId)->get();
//    Storage::put('spotify/playlist.json', json_encode($playlistData, JSON_PRETTY_PRINT));
//
//    $downloadedAlbums = [];
//    $skipped = 0;
//    $downloaded = 0;
//    $offset = 0;
//    $limit = 100;
//
//    do {
//        $response = Spotify::playlistTracks($playlistId)
//            ->limit($limit)
//            ->offset($offset)
//            ->get();
//
//        foreach ($response['items'] as $i => $item) {
//            $track = $item['track'];
//            $album = $track['album'] ?? null;
//            $albumName = $album['name'] ?? null;
//            $albumId = $album['id'] ?? null;
//            $images = $album['images'] ?? [];
//
//            if (!$albumName || !$albumId || !isset($images[0]['url'])) {
//                continue;
//            }
//
//            if (in_array($albumName, $downloadedAlbums)) {
//                $skipped++;
//                continue;
//            }
//
//            $index = $offset + $i + 1;
//            $slug = Str::slug("{$index}-{$albumName}-{$albumId}") . '.jpg';
//            $relativePath = "spotify/images/{$slug}";
//            $absolutePath = storage_path("app/public/{$relativePath}");
//
//            if (file_exists($absolutePath)) {
//                $skipped++;
//                continue;
//            }
//
//            $imageContents = Http::get($images[0]['url'])->body();
//
//            // Ensure directory exists before writing
//            if (!file_exists(dirname($absolutePath))) {
//                mkdir(dirname($absolutePath), 0755, true);
//            }
//
//            file_put_contents($absolutePath, $imageContents);
//
//            $downloadedAlbums[] = $albumName;
//            $downloaded++;
//        }
//
//        $offset += $limit;
//        $more = isset($response['next']) && $response['next'] !== null;
//
//    } while ($more);
//
//    return response()->json([
//        'message' => 'Album covers processed and saved to storage/app/spotify/images.',
//        'downloaded' => $downloaded,
//        'skipped' => $skipped,
//    ]);
//})->name('extract');

Route::get('extract', function () {
    $playlistId = '5pn8zwd2XxLLZIRyNWhdma';

    $disk = Storage::disk('public'); // use the configured 'public' disk
    $directory = 'spotify/images';

    $disk->makeDirectory($directory); // make sure the folder exists

    $playlistData = Spotify::playlist($playlistId)->get();
    Storage::put('spotify/playlist.json', json_encode($playlistData, JSON_PRETTY_PRINT)); // keep metadata local if needed

    $downloadedAlbums = [];
    $skipped = 0;
    $downloaded = 0;
    $offset = 0;
    $limit = 100;

    do {
        $response = Spotify::playlistTracks($playlistId)
            ->limit($limit)
            ->offset($offset)
            ->get();

        foreach ($response['items'] as $i => $item) {
            $track = $item['track'];
            $album = $track['album'] ?? null;
            $albumName = $album['name'] ?? null;
            $albumId = $album['id'] ?? null;
            $images = $album['images'] ?? [];

            if (!$albumName || !$albumId || !isset($images[0]['url'])) {
                continue;
            }

            if (in_array($albumName, $downloadedAlbums)) {
                $skipped++;
                continue;
            }

            $index = $offset + $i + 1;
            $slug = Str::slug("{$index}-{$albumName}-{$albumId}") . '.jpg';
            $path = "{$directory}/{$slug}";

            if ($disk->exists($path)) {
                $skipped++;
                continue;
            }

            $imageContents = Http::get($images[0]['url'])->body();
            $disk->put($path, $imageContents);

            $downloadedAlbums[] = $albumName;
            $downloaded++;
        }

        $offset += $limit;
        $more = isset($response['next']) && $response['next'] !== null;

    } while ($more);

    return response()->json([
        'message' => 'Album covers processed and saved to cloud disk.',
        'downloaded' => $downloaded,
        'skipped' => $skipped,
    ]);
})->name('extract');

use Illuminate\Support\Facades\Response;

Route::get('/images/{filename}', function ($filename) {
    $path = storage_path("app/spotify/images/{$filename}");

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('filename', '.*');

Route::get('/collage', function () {
    return Inertia::render('Collage');
})->name('collage');

//Route::get('/mosaic', function () {
//    $disk = Storage::disk('public');
//    $files = $disk->files('spotify/images');
//
//    $tileSize = 64; // pixels per tile
//    $cols = 25;     // number of images per row
//    $total = count($files);
//    $rows = ceil($total / $cols);
//
//    $mosaic = imagecreatetruecolor($cols * $tileSize, $rows * $tileSize);
//
//    $x = $y = 0;
//    foreach ($files as $file) {
//        $contents = $disk->get($file);
//        $image = imagecreatefromstring($contents);
//
//        // Resize tile to fixed size
//        $thumb = imagecreatetruecolor($tileSize, $tileSize);
//        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $tileSize, $tileSize, imagesx($image), imagesy($image));
//
//        imagecopy($mosaic, $thumb, $x * $tileSize, $y * $tileSize, 0, 0, $tileSize, $tileSize);
//        imagedestroy($thumb);
//
//        $x++;
//        if ($x >= $cols) {
//            $x = 0;
//            $y++;
//        }
//    }
//
//    ob_start();
//    imagejpeg($mosaic);
//    $imageData = ob_get_clean();
//
//    return response($imageData)->header('Content-Type', 'image/jpeg');
//});


Route::get('/mosaic', function () {
    $disk = Storage::disk('public');
    $files = $disk->files('spotify/images');

    if (empty($files)) {
        abort(404, 'No album images found.');
    }

    $tileSize = 64; // px per tile
    $cols = 25; // number of tiles per row
    $rows = ceil(count($files) / $cols);

    $mosaicWidth = $cols * $tileSize;
    $mosaicHeight = $rows * $tileSize;
    $mosaic = imagecreatetruecolor($mosaicWidth, $mosaicHeight);

    // Optional: white background
    $white = imagecolorallocate($mosaic, 255, 255, 255);
    imagefill($mosaic, 0, 0, $white);

    $x = $y = 0;
    foreach ($files as $file) {
        try {
            $imageData = $disk->get($file);
            $srcImage = imagecreatefromstring($imageData);
            if (!$srcImage) continue;

            $thumb = imagecreatetruecolor($tileSize, $tileSize);
            imagecopyresampled(
                $thumb,
                $srcImage,
                0, 0, 0, 0,
                $tileSize, $tileSize,
                imagesx($srcImage),
                imagesy($srcImage)
            );

            imagecopy(
                $mosaic,
                $thumb,
                $x * $tileSize,
                $y * $tileSize,
                0, 0, $tileSize, $tileSize
            );

            imagedestroy($srcImage);
            imagedestroy($thumb);

            $x++;
            if ($x >= $cols) {
                $x = 0;
                $y++;
            }
        } catch (Exception $e) {
            continue; // skip corrupt images
        }
    }

    ob_start();
    imagejpeg($mosaic);
    $output = ob_get_clean();
    imagedestroy($mosaic);

    return response($output)->header('Content-Type', 'image/jpeg');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
