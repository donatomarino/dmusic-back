<?php

namespace App\Http\Controllers;

use App\Models\Playlist;

class PlaylistController extends Controller
{
    public function index()
    {
        try {
            $playlists = Playlist::all();
            return response()->json([
                'success' => true,
                'data' => $playlists
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener las playlists'
            ], 500);
        }
    }

    public function playPlaylist($id)
    {
        try {
            $playlist = Playlist::find($id);

            // No hay condiciones en cuanto se puede hacer esta solicitud solamente si la playlist está en la base de datos y aparece en la app.
            return response()->json([
                'success' => true,
                'data' => $playlist
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al reproducir la playlist'
            ], 500);
        }
    }
}
