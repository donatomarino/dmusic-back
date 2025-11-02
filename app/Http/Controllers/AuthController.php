<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Comprobar campos
            $loginUserData = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ], [
                'email.required' => 'El correo es obligatorio',
                'password.required' => 'La password es obligatoria',
            ]);

            // Buscar usuario
            $user = User::where('email', $loginUserData['email'])->first();
            if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Las credenciales no son válidas',
                    'error' => true
                ], 401);
            }

            // Crear token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Usuario autenticado correctamente',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'initial_name' => $user->full_name[0],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al autenticar el usuario: ' . $e->getMessage(),
                'error' => true
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $userData = $request->validate([
                'full_name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
            ], [
                'full_name.required' => 'El nombre es obligatorio',
                'email.required' => 'El correo es obligatorio',
                'email.email' => 'El correo no es válido',
                'email.unique' => 'El correo ya está registrado',
                'password.required' => 'La password es obligatoria',
                'password.min' => 'La password debe tener al menos 8 caracteres',
            ]);

            User::create([
                'full_name' => $userData['full_name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado correctamente'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al registrar el usuario: ' . $e->getMessage(),
                'error' => true
            ], 500);
        }
    }
}
