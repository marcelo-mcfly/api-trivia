<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos recibidos
        /*  $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]); */

        // Buscar al usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar que el usuario exista y la contraseña sea correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $user->tokens()->delete();
        // Crear token de acceso
        $tokenResult = $user->createToken('auth_token');
        $token = $tokenResult->plainTextToken;

        // Retornar el token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}
