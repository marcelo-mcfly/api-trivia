<?php

namespace App\Http\Controllers;

/**
 * @OA\OpenApi(
 * @OA\Info(
 * version="1.0.0",
 * title="API de Trivias y Preguntas",
 * description="Documentación de la API para la aplicación de Trivias y Preguntas.",
 * @OA\Contact(
 * email="test@email.com"
 * )
 * ),
 * @OA\Server(
 * url="http://localhost:8000/api",
 * description="Servidor de Desarrollo"
 * ),
 * @OA\Components(
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * * type="http",
 * scheme="bearer",
 * bearerFormat="JWT",
 * description="Ingresa el token Bearer (ej. 'Bearer your_token_here')"
 * )
 * )
 * )
 */

abstract class Controller
{
    //

}
