<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;

class Trivia extends Model
{
    use HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = 'trivias';
    protected $fillable = [
        'numero_trivia',
        'nombre_trivia',
        'preguntas'
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
            'preguntas' => 'array'
        ];
    }
}
