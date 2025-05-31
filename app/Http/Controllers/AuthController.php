<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/login",
     * summary="Autentica un usuario y genera un token de acceso",
     * tags={"Autenticación"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"email", "password"},
     * @OA\Property(
     * property="email",
     * type="string",
     * format="email",
     * example="test@example.com",
     * description="Correo electrónico del usuario"
     * ),
     * @OA\Property(
     * property="password",
     * type="string",
     * format="password",
     * example="password",
     * description="Contraseña del usuario"
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Autenticación exitosa",
     * @OA\JsonContent(
     * @OA\Property(
     * property="access_token",
     * type="string",
     * example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     * description="Token de acceso para solicitudes autenticadas"
     * ),
     * @OA\Property(
     * property="token_type",
     * type="string",
     * example="Bearer",
     * description="Tipo de token"
     * ),
     * @OA\Property(
     * property="user",
     * type="object",
     * description="Detalles del usuario autenticado",
     * @OA\Property(property="_id", type="string", example="60c72b2f9f1b2c3d4e5f6a7b", description="ID del usuario (MongoDB ObjectId)"),
     * @OA\Property(property="name", type="string", example="John Doe", description="Nombre del usuario"),
     * @OA\Property(property="email", type="string", format="email", example="john@example.com", description="Correo electrónico del usuario")
     *
     * )
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Credenciales incorrectas",
     * @OA\JsonContent(
     * @OA\Property(
     * property="message",
     * type="string",
     * example="Credenciales incorrectas"
     * )
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Error interno del servidor",
     * @OA\JsonContent(
     * @OA\Property(
     * property="message",
     * type="string",
     * example="Ocurrió un error inesperado."
     * )
     * )
     * )
     * )
     */
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
