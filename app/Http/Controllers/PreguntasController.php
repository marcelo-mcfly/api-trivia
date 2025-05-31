<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pregunta;

class PreguntasController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/preguntas/crear",
     *     summary="Crear preguntas",
     *     tags={"Preguntas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 required={"enunciado","opciones","opcion_correcta_id","nivel_dificultad"},
     *                 @OA\Property(property="enunciado", type="string", example="¿Qué es una entrevista de trabajo?"),
     *                 @OA\Property(
     *                     property="opciones",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="string", example="1"),
     *                         @OA\Property(property="texto_opcion", type="string", example="Una reunión social")
     *                     )
     *                 ),
     *                 @OA\Property(property="opcion_correcta_id", type="string", example="2"),
     *                 @OA\Property(property="nivel_dificultad", type="string", enum={"facil", "medio", "dificil"})
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Preguntas creadas"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function crear(Request $request)
    {
        $datos = $request->all();

        if (!is_array($datos)) {
            return response()->json([
                'message' => 'El request debe ser un array de preguntas o una sola pregunta válida.'
            ], 400);
        }

        $preguntas = array_keys($datos) === range(0, count($datos) - 1) ? $datos : [$datos];

        foreach ($preguntas as $pregunta) {
            $validacion = \Validator::make($pregunta, [
                'enunciado' => 'required|string',
                'opciones' => 'required|array|min:2',
                'opciones.*.id' => 'required|string',
                'opciones.*.texto_opcion' => 'required|string',
                'opcion_correcta_id' => 'required|string',
                'nivel_dificultad' => 'required|in:facil,medio,dificil'
            ]);

            if ($validacion->fails()) {
                return response()->json([
                    'message' => 'Error en la validación',
                    'errores' => $validacion->errors()
                ], 422);
            }

            Pregunta::create($pregunta);
        }

        return response()->json([
            'message' => 'Registro exitoso'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/preguntas/listar",
     *     summary="Listar preguntas paginadas",
     *     tags={"Preguntas"},
     *     @OA\Response(response=200, description="Lista de preguntas")
     * )
     */
    public function listar()
    {
        $preguntas = Pregunta::paginate(5);

        return response()->json([
            'message' => 'Lista de preguntas',
            'preguntas' => $preguntas
        ], 200);
    }
}
