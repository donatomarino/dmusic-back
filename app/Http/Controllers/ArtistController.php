<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Exception;

class ArtistController extends Controller
{
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

    public function playArtist($id)
    {
        try {
            $artist = Artist::find($id);

            // No hay condiciones en cuanto se puede hacer esta solicitud solamente si la canción está en la base de datos y aparece en la app.
            return response()->json([
                'success' => true,
                'data' => $artist
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
