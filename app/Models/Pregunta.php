<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pregunta extends Model
{
    use HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'preguntas';
    protected $connection = 'mongodb';

    protected $fillable = [
        'enunciado',
        'opciones',
        'opcion_correcta_id',
        'nivel_dificultad'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'opciones' => 'array',
        ];
    }
}
