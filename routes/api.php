<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SongController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
  Route::post('dmusic/login', [AuthController::class, 'index'])->middleware('throttle:5,1');
  Route::post('dmusic/register', [AuthController::class, 'store']);
  // Route::post('dmusic/recovery-password', [AuthController:: class, 'forgotPassword']);
  // Route::post('dmusic/reset-password', [AuthController:: class, 'resetPassword']);
});

Route::get('dmusic/get-songs', [SongController::class, 'index']);
Route::get('dmusic/get-artists', [ArtistController::class, 'index']);
Route::get('dmusic/search-song/{query}', [SongController::class, 'searchSong']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('dmusic/play-song/{id}', [SongController::class, 'playSong']);
  Route::get('dmusic/play-artist/{id}', [ArtistController::class, 'playArtist']);
  Route::get('dmusic/play-library/{id}', [SongController::class, 'playFavoriteSong']);
  Route::get('dmusic/get-favorite-songs', [SongController::class, 'getFavoriteSongs']);
  Route::post('dmusic/add-favorite-song/{id}', [SongController::class, 'addFavoriteSong']);
  Route::delete('dmusic/delete-favorite-song/{id}', [SongController::class, 'deleteFavoriteSong']);
});