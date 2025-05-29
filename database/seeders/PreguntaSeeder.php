<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pregunta;

class PreguntaSeeder extends Seeder
{
    public function run(): void
    {
        $preguntas = [
            [
                'enunciado' => '¿Qué es una entrevista de trabajo?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Una reunión social'],
                    ['id' => '2', 'texto_opcion' => 'Un proceso de evaluación de candidatos'],
                    ['id' => '3', 'texto_opcion' => 'Una sesión de entrenamiento'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'facil'
            ],
            [
                'enunciado' => '¿Cuál es la función principal del departamento de recursos humanos?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Diseñar campañas publicitarias'],
                    ['id' => '2', 'texto_opcion' => 'Gestionar el talento humano'],
                    ['id' => '3', 'texto_opcion' => 'Controlar el inventario'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'facil'
            ],
            [
                'enunciado' => '¿Qué documento formaliza la relación laboral?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Contrato de trabajo'],
                    ['id' => '2', 'texto_opcion' => 'Currículum vitae'],
                    ['id' => '3', 'texto_opcion' => 'Acta de nacimiento'],
                ],
                'opcion_correcta_id' => '1',
                'nivel_dificultad' => 'facil'
            ],
            [
                'enunciado' => '¿Qué herramienta se usa para evaluar competencias blandas?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Prueba psicométrica'],
                    ['id' => '2', 'texto_opcion' => 'Hoja de vida'],
                    ['id' => '3', 'texto_opcion' => 'Contrato colectivo'],
                ],
                'opcion_correcta_id' => '1',
                'nivel_dificultad' => 'medio'
            ],
            [
                'enunciado' => '¿Qué es el onboarding?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Una reunión del área contable'],
                    ['id' => '2', 'texto_opcion' => 'El proceso de integración del nuevo empleado'],
                    ['id' => '3', 'texto_opcion' => 'Una evaluación de desempeño'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'medio'
            ],
            [
                'enunciado' => '¿Qué método mide la satisfacción laboral?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Entrevista de salida'],
                    ['id' => '2', 'texto_opcion' => 'Encuesta de clima organizacional'],
                    ['id' => '3', 'texto_opcion' => 'Evaluación médica'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'medio'
            ],
            [
                'enunciado' => '¿Qué implica la gestión por competencias?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Medir el inventario mensual'],
                    ['id' => '2', 'texto_opcion' => 'Asignar tareas según habilidades y conocimientos'],
                    ['id' => '3', 'texto_opcion' => 'Crear campañas publicitarias'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'dificil'
            ],
            [
                'enunciado' => '¿Cuál es el objetivo del feedback 360°?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Evaluar solo al jefe inmediato'],
                    ['id' => '2', 'texto_opcion' => 'Evaluar desde múltiples fuentes'],
                    ['id' => '3', 'texto_opcion' => 'Determinar salario mínimo'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'dificil'
            ],
            [
                'enunciado' => '¿Qué área es responsable de atraer candidatos a una vacante?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Departamento de Ventas'],
                    ['id' => '2', 'texto_opcion' => 'Departamento de Reclutamiento'],
                    ['id' => '3', 'texto_opcion' => 'Departamento de Finanzas'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'facil'
            ],
            [
                'enunciado' => '¿Qué documento contiene la descripción de un puesto y sus funciones?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Informe contable'],
                    ['id' => '2', 'texto_opcion' => 'Manual de funciones'],
                    ['id' => '3', 'texto_opcion' => 'Carta de recomendación'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'facil'
            ],
            [
                'enunciado' => '¿Cuál es una ventaja del trabajo remoto?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Mayor ausentismo'],
                    ['id' => '2', 'texto_opcion' => 'Reducción de costos operativos'],
                    ['id' => '3', 'texto_opcion' => 'Menor comunicación'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'medio'
            ],
            [
                'enunciado' => '¿Qué distingue al clima de la cultura organizacional?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'El clima es más duradero que la cultura'],
                    ['id' => '2', 'texto_opcion' => 'El clima refleja percepciones actuales; la cultura, valores arraigados'],
                    ['id' => '3', 'texto_opcion' => 'La cultura cambia semanalmente'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'dificil'
            ],
            [
                'enunciado' => '¿Qué herramienta evalúa el desempeño laboral?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Evaluación de desempeño'],
                    ['id' => '2', 'texto_opcion' => 'Reunión de cumpleaños'],
                    ['id' => '3', 'texto_opcion' => 'Auditoría financiera'],
                ],
                'opcion_correcta_id' => '1',
                'nivel_dificultad' => 'facil'
            ],
            [
                'enunciado' => '¿Cuál es una señal de rotación laboral elevada?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Pocos despidos'],
                    ['id' => '2', 'texto_opcion' => 'Alta tasa de reemplazo de personal'],
                    ['id' => '3', 'texto_opcion' => 'Aumento en vacaciones'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'medio'
            ],
            [
                'enunciado' => '¿Qué función cumple una descripción de puesto?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Establecer salario mínimo legal'],
                    ['id' => '2', 'texto_opcion' => 'Definir responsabilidades y requisitos del cargo'],
                    ['id' => '3', 'texto_opcion' => 'Coordinar campañas de publicidad'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'medio'
            ],
            [
                'enunciado' => '¿Qué mide un KPI de RRHH?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'El clima del país'],
                    ['id' => '2', 'texto_opcion' => 'Indicadores clave de rendimiento en el área de talento humano'],
                    ['id' => '3', 'texto_opcion' => 'La demanda de productos'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'dificil'
            ],
            [
                'enunciado' => '¿Qué significa capacitación in company?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Formación externa en universidades'],
                    ['id' => '2', 'texto_opcion' => 'Capacitación dentro de la empresa'],
                    ['id' => '3', 'texto_opcion' => 'Entrenamiento en línea abierto'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'medio'
            ],
            [
                'enunciado' => '¿Qué representa un headhunter?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Especialista en contratación ejecutiva'],
                    ['id' => '2', 'texto_opcion' => 'Consultor en redes sociales'],
                    ['id' => '3', 'texto_opcion' => 'Asesor de inversión'],
                ],
                'opcion_correcta_id' => '1',
                'nivel_dificultad' => 'dificil'
            ],
            [
                'enunciado' => '¿Qué es una entrevista por competencias?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Una entrevista donde se evalúa la ropa del candidato'],
                    ['id' => '2', 'texto_opcion' => 'Una entrevista que evalúa habilidades y comportamientos previos'],
                    ['id' => '3', 'texto_opcion' => 'Un test técnico'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'medio'
            ],
            [
                'enunciado' => '¿Qué ventaja ofrece el teletrabajo?',
                'opciones' => [
                    ['id' => '1', 'texto_opcion' => 'Mayor tiempo de traslado'],
                    ['id' => '2', 'texto_opcion' => 'Reducción en costos de oficina'],
                    ['id' => '3', 'texto_opcion' => 'Menor productividad'],
                ],
                'opcion_correcta_id' => '2',
                'nivel_dificultad' => 'facil'
            ]
        ];

        foreach ($preguntas as $pregunta) {
            Pregunta::create($pregunta);
        }
    }
}
