<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\User;
use Exception;

class SongController extends Controller
{
    public function index()
    {
        try {
            $songs = Song::all();
            return response()->json([
                'success' => true,
                'data' => $songs
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener las canciones'
            ], 500);
        }
    }

    public function searchSong(Request $request)
    {
        try {
            $query = $request->input('query');
            $songs = Song::where('title', 'LIKE', "%{$query}%")->get();
            if ($songs) {
                return response()->json([
                    'success' => true,
                    'data' => $songs
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Canción no encontrada'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al buscar la canción'
            ], 500);
        }
    }

    public function playSong($id)
    {
        try {
            $song = Song::find($id);

            // No hay condiciones en cuanto se puede hacer esta solicitud solamente si la canción está en la base de datos y aparece en la app.
            return response()->json([
                'success' => true,
                'data' => $song
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al reproducir la canción'
            ], 500);
        }
    }

    public function playLibrary(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            $songs = Song::with('artist')->get();

            // $users_songs = 
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al reproducir la librería'
            ], 500);
        }
    }

    public function addFavoriteSong(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            $attached = $user->songs()->attach($request->song_id);

            if ($attached) {
                return response()->json([
                    'success' => true,
                    'message' => 'Canción agregada a favoritos'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'La canción ya está en favoritos'
                ], 409);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al agregar la canción a favoritos'
            ], 500);
        }
    }

    public function deleteFavoriteSong(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            $deleted = $user->songs()->detach($request->song_id);

            if ($deleted) {
                return response(null, 204);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'La canción no está en favoritos'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al eliminar la canción de favoritos'
            ], 500);
        }
    }

    public function getFavoriteSongs($id)
    {
        try {
            $user = User::find($id);
            $songs = $user->songs->get();
            return response()->json([
                'success' => true,
                'data' => $songs
            ], 200);
        } catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener las canciones favoritas'
            ], 500);
        }
    }
}
