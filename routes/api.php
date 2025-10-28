<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SongController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
  Route::post('dmusic/login', [AuthController::class, 'index']);
  Route::post('dmusic/register', [AuthController::class, 'register']);
  // Route::post('dmusic/recovery-password', [AuthController:: class, 'forgotPassword']);
  // Route::post('dmusic/reset-password', [AuthController:: class, 'resetPassword']);
});

Route::get('dmusic/get-songs', [SongController::class, 'index']);
Route::get('dmusic/get-artists', [ArtistController::class, 'index']);
Route::post('dmusic/search-song/{id}', [SongController::class, 'searchSong']);

Route::middleware('auth:sanctum')->group(function () {
  Route::post('dmusic/play-song/{id}', [SongController::class, 'playSong']);
  Route::post('dmusic/play-artist/{id}', [ArtistController::class, 'playArtist']);
  Route::post('dmusic/play-library/{id}', [SongController::class, 'playFavoriteSong']);
  Route::post('dmusic/get-favorite-songs', [SongController::class, 'getFavoriteSongs']);
  Route::post('dmusic/add-favorite-song/{id}', [SongController::class, 'addFavoriteSong']);
  Route::delete('dmusic/delete-favorite-song/{id}', [SongController::class, 'deleteFavoriteSong']);
});