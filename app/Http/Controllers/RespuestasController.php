<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Respuesta;
use App\Models\Pregunta;
use App\Models\Trivia;
use App\Models\User;
use App\Models\ParamPuntaje;
use Illuminate\Support\Facades\Log;

class RespuestasController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/respuestas/crear",
     *     summary="Registrar respuestas de una trivia",
     *     tags={"Respuestas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"jugador", "trivia_id", "preguntas"},
     *             @OA\Property(property="jugador", type="string", format="email", example="jugador@email.com"),
     *             @OA\Property(property="trivia_id", type="string", example="6658b60e9d63c84e8c0f0a11"),
     *             @OA\Property(
     *                 property="preguntas",
     *                 type="array",
     *                 @OA\Items(
     *                     required={"pregunta_id", "respuesta_jugador"},
     *                     @OA\Property(property="pregunta_id", type="string", example="664a23c48d5f6a1e9b7f8b4f"),
     *                     @OA\Property(property="respuesta_jugador", type="string", example="1")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Respuestas guardadas correctamente"),
     *     @OA\Response(response=422, description="Validación fallida"),
     *     @OA\Response(response=404, description="Jugador no registrado"),
     *     @OA\Response(response=500, description="Error interno al guardar la respuesta")
     * )
     */

    public function crear(Request $request)
    {
        // Validar datos básicos
        $validated = \Validator::make($request->all(), [
            'jugador' => 'required|email',
            'trivia_id' => 'required|string',
            'preguntas' => 'required|array',
            'preguntas.*.pregunta_id' => 'required|string',
            'preguntas.*.respuesta_jugador' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Datos inválidos o incompletos',
                'errors' => $validated->errors()
            ], 422);
        }

        // Validar que el jugador exista
        $user = User::where('email', $request->jugador)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Jugador no registrado'
            ], 404);
        }

        try {
            $puntajeTotal = 0;
            $detallePreguntas = [];

            foreach ($request->preguntas as $respuesta) {
                $pregunta = Pregunta::find($respuesta['pregunta_id']);

                if (!$pregunta) {
                    continue; // Ignorar preguntas inválidas
                }

                $correcta = $pregunta->opcion_correcta_id;
                $nivel = $pregunta->nivel_dificultad;

                $puntaje = 0;
                if ($respuesta['respuesta_jugador'] === $correcta) {
                    $param = ParamPuntaje::where('nivel', $nivel)->first();
                    $puntaje = $param ? $param->puntaje : 0;
                    $puntajeTotal += $puntaje;
                }

                $detallePreguntas[] = [
                    'pregunta_id' => $pregunta->_id,
                    'respuesta_jugador' => $respuesta['respuesta_jugador'],
                    'respuesta_correcta' => $correcta,
                    'nivel_dificultad' => $nivel,
                    'puntaje' => $puntaje
                ];
            }

            $respuesta = Respuesta::create([
                'jugador' => $request->jugador,
                'trivia_id' => $request->trivia_id,
                'preguntas' => $detallePreguntas,
                'puntaje' => $puntajeTotal
            ]);

            return response()->json([
                'message' => 'Respuestas guardadas',
                'respuesta' => $respuesta
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al guardar respuesta: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al guardar respuesta',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
 * @OA\Get(
 *     path="/api/respuestas/listar",
 *     summary="Listar respuestas de un jugador (por email y número de trivia opcional)",
 *     tags={"Respuestas"},
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         required=true,
 *         description="Correo electrónico del jugador",
 *         @OA\Schema(type="string", format="email", example="jugador@email.com")
 *     ),
 *     @OA\Parameter(
 *         name="numero_trivia",
 *         in="query",
 *         required=false,
 *         description="Número de trivia (opcional)",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(response=200, description="Listado de respuestas del jugador"),
 *     @OA\Response(response=422, description="Datos inválidos o incompletos")
 * )
 */

    public function listar(Request $request)
    {
        $validated = \Validator::make($request->all(), [
            'email' => 'required|email',
            'numero_trivia' => 'nullable|integer'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Datos inválidos o incompletos',
                'errors' => $validated->errors()
            ], 422);
        }

        $email = $request->query('email');
        $numeroTrivia = $request->query('numero_trivia');

        // Si vino numero_trivia, buscamos la especifica
        if ($numeroTrivia !== null) {
            // Suponiendo que puede haber más de una trivia con el mismo número (o solo una)
            $trivias = Trivia::where('numero_trivia', (int)$numeroTrivia)->pluck('id')->toArray();

            if (empty($trivias)) {
                // No existe trivia con ese número, devolvemos vacío
                return response()->json([
                    'respuestas' => [],
                    'message' => 'No se encontró trivia con ese número',
                ]);
            }

            $query = Respuesta::where('jugador', $email)
                ->whereIn('trivia_id', $trivias);
        } else {
            // Sin filtro por trivia
            $query = Respuesta::where('jugador', $email);
        }

        $respuestas = $query->orderBy('created_at', 'desc')->get();

        // Decodificamos 'preguntas' si es string
        $respuestas->transform(function ($respuesta) {
            if (is_string($respuesta->preguntas)) {
                $respuesta->preguntas = json_decode($respuesta->preguntas);
            }
            return $respuesta;
        });

        // Traemos todas las trivias usadas en las respuestas para hacer join manual
        $triviaIds = $respuestas->pluck('trivia_id')->unique()->toArray();

        $trivias = Trivia::whereIn('id', $triviaIds)
            ->get()
            ->keyBy('id'); // clave por id para acceso rápido

        // Agregamos la info de la trivia en cada respuesta
        $respuestas->transform(function ($respuesta) use ($trivias) {
            $trivia = $trivias->get($respuesta->trivia_id);
            if ($trivia) {
                $respuesta->numero_trivia = $trivia->numero_trivia;
                $respuesta->nombre_trivia = $trivia->nombre_trivia;
            } else {
                $respuesta->numero_trivia = null;
                $respuesta->nombre_trivia = null;
            }
            return $respuesta;
        });

        return response()->json([
            'respuestas' => $respuestas
        ]);
    }
}
