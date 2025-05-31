<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trivia;
use App\Models\Pregunta;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

use function Laravel\Prompts\error;
use Illuminate\Support\Facades\Log;


class TriviaController extends Controller
{

    /**
     * @OA\Post(
     * path="/trivias/crear",
     * summary="Crea una nueva trivia",
     * tags={"Trivias"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * required={"numero_trivia","nombre_trivia","pregunta_ids"},
     * @OA\Property(property="numero_trivia", type="integer", example=1, description="Número identificador de la trivia"),
     * @OA\Property(property="nombre_trivia", type="string", example="Trivia de Recursos Humanos", description="Nombre descriptivo de la trivia"),
     * @OA\Property(
     * property="pregunta_ids",
     * type="array",
     * @OA\Items(type="string", example="68387d483208ef498505f092", description="Lista de IDs de preguntas asociadas a la trivia (MongoDB ObjectId)")
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Trivia creada exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Trivia creada con éxito."),
     * @OA\Property(property="trivia", type="object", description="Detalles de la trivia creada",
     * @OA\Property(property="_id", type="string", example="68387d483208ef498505f093"),
     * @OA\Property(property="numero_trivia", type="integer", example=1),
     * @OA\Property(property="nombre_trivia", type="string", example="Trivia de Recursos Humanos")
     *
     * )
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * ),
     * @OA\Response(
     * response=409,
     * description="Conflicto: Trivia ya existe",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Ya existe una trivia con este número de trivia.")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validación fallida",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos."),
     * @OA\Property(property="errors", type="object", description="Detalles de los errores de validación")
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Error interno del servidor",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Error interno del servidor.")
     * )
     * )
     * )
     */
    public function crear(Request $request)
    {
        $validacion = \Validator::make($request->all(), [

            'numero_trivia' => 'required|integer',
            'nombre_trivia' => 'required|string',
            'pregunta_ids' => 'required|array'

        ]);
        if ($validacion->fails()) {
            return response()->json([
                'message' => 'Faltan datos obligatorios.'
            ], 422);
        }

        try {
            log::info('antes de consulta');
            // Verificar si ya existe una trivia con ese numero_trivia
            $existe = Trivia::where('numero_trivia', $request->numero_trivia)->exists();

            Log::info('pasó');

            if ($existe) {
                return response()->json([
                    'message' => "La trivia con número $request->numero_trivia ya existe."
                ], 409); // Código 409 Conflict
            }

            // Traer preguntas completas de MongoDB por los IDs
            $preguntas = Pregunta::whereIn('_id',  $request->pregunta_ids)->get()->toArray();

            // Crear la trivia con preguntas embebidas (array de documentos)
            $trivia = Trivia::create([
                'numero_trivia' =>  $request->numero_trivia,
                'nombre_trivia' =>  $request->nombre_trivia,
                'preguntas' => $preguntas
            ]);

            return response()->json([
                'message' => 'Trivia creada',
                'trivia' => $trivia,
            ], 201);
        } catch (Exception $e) {
            Log::error('Error al crear trivia', ['exception' => $e]);

            return response()->json([
                'message' => 'Ocurrió un error al crear la trivia.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/trivias/listar",
     * summary="Listar trivias o una trivia por número",
     * tags={"Trivias"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="numero_trivia",
     * in="query",
     * description="Número de la trivia a consultar (opcional). Si se omite, lista todas las trivias.",
     * required=false,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Listado de trivias o trivia específica encontrada",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="_id", type="string", example="68387d483208ef498505f093", description="ID único de la trivia (MongoDB ObjectId)"),
     * @OA\Property(property="numero_trivia", type="integer", example=1, description="Número de la trivia"),
     * @OA\Property(property="nombre_trivia", type="string", example="Trivia de Recursos Humanos", description="Nombre de la trivia"),
     * @OA\Property(property="pregunta_ids", type="array", @OA\Items(type="string"), example={"68387d483208ef498505f092"}, description="IDs de preguntas asociadas")
     *
     * ),
     * example={
     * {"_id": "6838e481574f16179305e352", "numero_trivia": 1, "nombre_trivia": "Trivia de Historia", "pregunta_ids": {"6838f481574f16179305e353"}},
     * {"_id": "6838e481574f16179305e354", "numero_trivia": 2, "nombre_trivia": "Trivia de Ciencias", "pregunta_ids": {}}
     * }
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * ),
     * @OA\Response(
     * response=204,
     * description="No Content: No se encontró la trivia específica (si se consultó por número y no existe).",
     *
     * ),
     * @OA\Response(
     * response=500,
     * description="Error interno del servidor",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Error interno del servidor.")
     * )
     * )
     * )
     */
    public function listar(Request $request)
    {
        $numeroTrivia = $request->query('numero_trivia'); // Parámetro opcional desde la URL

        if ($numeroTrivia) {
            // Buscar trivia por número
            $trivia = Trivia::where('numero_trivia', (int)$numeroTrivia)->first();

            if ($trivia) {
                return response()->json([
                    'message' => 'Trivia encontrada',
                    'trivia' => $trivia,
                ], 200);
            } else {
                return response()->json([
                    'message' => "No se encontró trivia con número $numeroTrivia"
                ], 204);
            }
        } else {
            // Si no se pasa número, retorna todas las trivias
            $trivias = Trivia::all();

            return response()->json([
                'message' => 'Listado de todas las trivias',
                'trivias' => $trivias,
            ], 200);
        }
    }
}
