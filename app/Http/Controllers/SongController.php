<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{
    /**
     * Obtener todas las canciones.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $songs = Song::with('artist')->get()->makeHidden(['id_artist', 'created_at', 'updated_at', 'genre']);
            return response()->json([
                'success' => true,
                'data' => $songs
            ], 200);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener las canciones',
                'error' => true
            ], 500);
        }
    }


    /**
     * Obtener las canciones favoritas del usuario autenticado.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFavoriteSongs()
    {
        try {
            /** @var \App\Models\User $user */ // Para que no marque error en el IDE
            $user = Auth::user();
            $songs = $user->songs()->with('artist')->get()->makeHidden(['pivot', 'created_at', 'updated_at', 'genre', 'id_artist']);
            return response()->json([
                'success' => true,
                'data' => $songs
            ], 200);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener las canciones favoritas',
                'error' => true
            ], 500);
        }
    }

    /**
     * Buscar canciones por título.
     *
     * @param string $query Texto que se busca en el título
     * @return JsonResponse
     */
    public function searchSong($query)
    {
        try {
            $response = true;
            $songs = Song::with('artist')->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($query) . "%"])->get()->makeHidden(['created_at', 'updated_at', 'genre', 'id_artist']);

            count($songs) === 0 && $response = false;

            return response()->json([
                'success' => $response,
                'data' => $songs
            ], 200);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al buscar canciones',
                'error' => true
            ], 500);
        }
    }

    /**
     * Obtener las canciones ordenadas a partir de la canción indicada.
     *
     * @param string $id Identificador de la canción
     * @return JsonResponse
     */
    public function playSong($id)
    {
        try {
            $first = Song::select('title', 'url', 'id')->find($id);
            if (!$first) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la canción',
                    'error' => true
                ], 404);
            }

            $others = Song::select('title', 'url', 'id')->where('id', '!=', $id)->orderBy('id', 'asc')->get();

            $songs = $first ? collect([$first])->merge($others) : $others;

            // No hay condiciones en cuanto se puede hacer esta solicitud solamente si la canción está en la base de datos y aparece en la app.
            return response()->json([
                'success' => true,
                'data' => $songs
            ], 200);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener las canciones',
                'error' => true
            ], 500);
        }
    }

    /**
     * Obtener las canciones favoritas ordenadas a partir de la indicada.
     *
     * @param string $id Identificador de la canción
     * @return JsonResponse
     */
    public function playFavoriteSong($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $songs = $user->songs()->with('artist')->get()->makeHidden(['pivot', 'created_at', 'updated_at', 'genre', 'id_artist']);

            // Reordenar para que la canción seleccionada aparezca primero.
            $first = $songs->where('id', $id);
            if ($first->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La canción no está en tus favoritos',
                    'error' => true
                ], 404);
            }

            $others = $songs->where('id', '!=', $id);
            $orderedSongs = $first->concat($others)->values();

            return response()->json([
                'success' => true,
                'data' => $orderedSongs
            ], 200);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener la librería',
                'error' => true
            ], 500);
        }
    }

    /**
     * Agregar una canción a los favoritos del usuario autenticado.
     *
     * @param string $id Identificador de la canción
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFavoriteSong($id)
    {
        try {
            /** @var \App\Models\User $user */ // Para que no marque error en el IDE
            $user = Auth::user();
            if (!Song::whereKey($id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la canción',
                    'error' => true
                ], 404);
            }

            $song = $user->songs()->where('song_id', $id)->first();
            if ($song) {
                return response()->json([
                    'success' => false,
                    'message' => 'La canción ya está en favoritos',
                    'error' => 409
                ], 409);
            }

            $user->songs()->attach($id);
            return response()->json([
                'success' => true,
                'message' => 'Canción agregada a favoritos'
            ], 201);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al agregar la canción a favoritos',
                'error' => true
            ], 500);
        }
    }

    /**
     * Eliminar una canción de los favoritos del usuario autenticado.
     *
     * @param string $id Identificador de la canción
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function deleteFavoriteSong($id)
    {
        try {
            /** @var \App\Models\User $user */ // Para que no marque error en el IDE
            $user = Auth::user();
            $deleted = $user->songs()->detach($id);

            if ($deleted) {
                return response(null, 204);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'La canción no está en favoritos',
                    'error' => true
                ], 404);
            }
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al eliminar la canción de favoritos',
                'error' => true
            ], 500);
        }
    }
}
