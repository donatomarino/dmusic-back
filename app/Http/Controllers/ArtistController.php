<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\JsonResponse;
use Exception;

class ArtistController extends Controller
{
    /**
     * Obtener todos los artistas.
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
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener los artistas',
                'error' => true
            ], 500);
        }
    }

    /**
     * Obtener las canciones de un artista.
     *
     * @param string $id Identificador del artista
     * @return JsonResponse
     */
    public function playArtist($id)
    {
        try {
            $artist = Artist::find($id);
            if (!$artist) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el artista',
                    'error' => true
                ], 404);
            }

            $songs = $artist->songs()->select('id', 'title', 'url')->get();

            return response()->json([
                'success' => true,
                'data' => $songs
            ], 200);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener las canciones del artista',
                'error' => true
            ], 500);
        }
    }
}
