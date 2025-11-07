<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Exception;

class ArtistController extends Controller
{
    /**
     * Listar todos los artistas
     * @author Donato Marino
     * 
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $artists = Artist::all()->makeHidden(['created_at', 'updated_at']);
            return response()->json([
                'success' => true,
                'data' => $artists
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener los artistas: ' . $e->getMessage(),
                'error' => true
            ], 500);
        }
    }

    /**
     * Reproducir todas las canciones de un artista
     * @author Donato Marino
     * 
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function playArtist($id)
    {
        try {
            // Obtener el id, título y url de las canciones del artista
            $songs = Artist::find($id)->songs()->select('id', 'title', 'url')->get();

            return response()->json([
                'success' => true,
                'data' => $songs
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al reproducir el artista: ' . $e->getMessage(),
                'error' => true
            ], 500);
        }
    }
}
