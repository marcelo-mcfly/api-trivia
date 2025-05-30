<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pregunta;

class PreguntasController extends Controller
{
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


    public function listar()
    {
        $preguntas = Pregunta::paginate(5);

        return response()->json([
            'message' => 'Lista de preguntas',
            'preguntas' => $preguntas
        ],200);
    }
}
