<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{
  /**
   * Obtener todas las canciones
   * @author Donato Marino
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
      return response()->json([
        'success' => false,
        'message' => 'Error inesperado al obtener las canciones: ' . $e->getMessage(),
        'error' => true
      ], 500);
    }
  }

  /**
   * Buscar canción por nombre
   * @author Donato Marino
   * 
   * @param Request $request
   * 
   * @return JsonResponse
   */
  public function searchSong(Request $request)
  {
    try {
      $response = true;
      $songs = Song::with('artist')->where('title', 'LIKE', "%$request->song_name%")->get()->makeHidden(['created_at', 'updated_at', 'genre', 'id_artist']);

      // Si no hay resultados, success = false
      count($songs) === 0 && $response = false;

      return response()->json([
        'success' => $response,
        'data' => $songs
      ], 200);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Error inesperado al buscar la canción: ' . $e->getMessage(),
        'error' => true
      ], 500);
    }
  }

  /**
   * Reproducir canción
   * @author Donato Marino
   * 
   * @param int $id
   * 
   * @return JsonResponse
   */
  public function playSong($id)
  {
    try {
      // Reordenar: primero la canción con el id dado, luego el resto
      $first = Song::select('title', 'url', 'id')->find($id);
      $others = Song::select('title', 'url', 'id')->where('id', '!=', $id)->orderBy('id', 'asc')->get();

      $songs = $first ? collect([$first])->merge($others) : $others;

      // No hay condiciones en cuanto se puede hacer esta solicitud solamente si la canción está en la base de datos y aparece en la app.
      return response()->json([
        'success' => true,
        'data' => $songs
      ], 200);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Error inesperado al reproducir la canción: ' . $e->getMessage(),
        'error' => true
      ], 500);
    }
  }

  /**
   * Reproducir canción de la librería de favoritos
   * @author Donato Marino
   * 
   * @param int $id
   * 
   * @return JsonResponse
   */
  public function playFavoriteSong($id)
  {
    try {
      /** @var \App\Models\User $user */ // Para que no marque error en el IDE
      $user = Auth::user();
      $songs = $user->songs()->with('artist')->get()->makeHidden(['pivot', 'created_at', 'updated_at', 'genre', 'id_artist']);

      // Reordenar: primero la canción con el id dado, luego el resto
      $first = $songs->where('id', $id);
      $others = $songs->where('id', '!=', $id);
      $orderedSongs = $first->concat($others)->values();

      return response()->json([
        'success' => true,
        'data' => $orderedSongs
      ], 200);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Error inesperado al reproducir la librería: ' . $e->getMessage(),
        'error' => true
      ], 500);
    }
  }

  /**
   * Agregar canción a favoritos
   * @author Donato Marino
   * 
   * @param int $id
   * 
   * @return JsonResponse
   */
  public function addFavoriteSong($id)
  {
    try {
      /** @var \App\Models\User $user */ // Para que no marque error en el IDE
      $user = Auth::user();
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
      return response()->json([
        'success' => false,
        'message' => 'Error inesperado al agregar la canción a favoritos: ' . $e->getMessage(),
        'error' => true
      ], 500);
    }
  }

  /**
   * Eliminar canción de favoritos
   * @author Donato Marino
   * 
   * @param int $id
   * 
   * @return JsonResponse
   */
  public function deleteFavoriteSong($id)
  {
    try {
      /** @var \App\Models\User $user */ // Para que no marque error en el IDE
      $user = Auth::user();
      // Detach devuelve el número de registros eliminados
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
      return response()->json([
        'success' => false,
        'message' => 'Error inesperado al eliminar la canción de favoritos: ' . $e->getMessage(),
        'error' => true
      ], 500);
    }
  }

  /**
   * Obtener canciones favoritas del usuario autenticado
   * @author Donato Marino
   * 
   * @return JsonResponse
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
      return response()->json([
        'success' => false,
        'message' => 'Error inesperado al obtener las canciones favoritas: ' . $e->getMessage(),
        'error' => true
      ], 500);
    }
  }
}
