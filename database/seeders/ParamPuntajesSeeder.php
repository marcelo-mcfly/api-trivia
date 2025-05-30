<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ParamPuntaje;

class ParamPuntajesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveles = [
            ['nivel' => 'facil', 'puntaje' => 5],
            ['nivel' => 'medio', 'puntaje' => 10],
            ['nivel' => 'dificil', 'puntaje' => 20],
        ];

        foreach ($niveles as $nivel) {
            ParamPuntaje::updateOrCreate(['nivel' => $nivel['nivel']], $nivel);
        }
    }
}
