<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SongController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
  Route::post('dmusic/login', [AuthController::class, 'index']);
  Route::post('dmusic/register', [AuthController::class, 'store']);
  Route::post('dmusic/recovery-password', [AuthController:: class, 'forgotPassword']);
  Route::post('dmusic/reset-password', [AuthController:: class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->group(function () {
  Route::put('dmusic/update-user', [AuthController::class, 'update']); //
  Route::get('dmusic/get-songs', [SongController::class, 'index']);
  Route::get('dmusic/get-artists', [ArtistController::class, 'index']);
  Route::post('dmusic/search-song', [SongController::class, 'searchSong']);
  Route::post('dmusic/play-song/{id}', [SongController::class, 'playSong']);
  Route::post('dmusic/play-artist/{id}', [ArtistController::class, 'playArtist']);
});