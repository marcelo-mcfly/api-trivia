<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;

class Trivia extends Model
{
    use HasApiTokens;
    //
}
