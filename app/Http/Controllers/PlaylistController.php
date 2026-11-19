<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\JsonResponse;

class PlaylistController extends Controller
{
    /**
     * Obtener todas las playlists.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $playlists = Playlist::all();
            return response()->json([
                'success' => true,
                'data' => $playlists
            ], 200);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener las playlists',
                'error' => true
            ], 500);
        }
    }

    /**
     * Obtener una playlist por su identificador.
     *
     * @param string $id Identificador de la playlist
     * @return JsonResponse
     */
    public function playPlaylist($id)
    {
        try {
            $playlist = Playlist::find($id);
            if (!$playlist) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la playlist',
                    'error' => true
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $playlist
            ], 200);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener la playlist',
                'error' => true
            ], 500);
        }
    }
}
