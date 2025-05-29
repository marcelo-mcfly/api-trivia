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
    public function storeV1(Request $request)
    {
        $validated = $request->validate([
            'nombre_trivia' => 'required|string',
            'numero_trivia' => 'required|integer',
            'preguntas' => 'required|array',
            'preguntas.*.enunciado' => 'required|string',
            'preguntas.*.opciones' => 'required|array|min:2',
            'preguntas.*.opciones.*.texto_opcion' => 'required|string',
            'preguntas.*.opcion_correcta_id' => 'required|string',
        ]);

        // Agregar ID únicos a las opciones
        $preguntas = collect($validated['preguntas'])->map(function ($pregunta) {
            $pregunta['opciones'] = collect($pregunta['opciones'])->map(function ($opcion) {
                $opcion['id'] = uniqid(); // Genera ID único
                return $opcion;
            })->toArray();

            return $pregunta;
        })->toArray();

        $trivia = Trivia::create([
            'numero_trivia' => $validated['numero_trivia'],
            'preguntas' => $preguntas,
        ]);

        return response()->json([
            'message' => 'Trivia creada con preguntas embebidas',
            'trivia' => $trivia
        ]);
    }


    public function store(Request $request)
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
            ]);
        } catch (Exception $e) {
            Log::error('Error al crear trivia', ['exception' => $e]);

            return response()->json([
                'message' => 'Ocurrió un error al crear la trivia.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
                ]);
            } else {
                return response()->json([
                    'message' => "No se encontró trivia con número $numeroTrivia"
                ], 404);
            }
        } else {
            // Si no se pasa número, retorna todas las trivias
            $trivias = Trivia::all();

            return response()->json([
                'message' => 'Listado de todas las trivias',
                'trivias' => $trivias,
            ]);
        }
    }
}
