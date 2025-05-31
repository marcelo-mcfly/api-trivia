<?php

namespace App\Models;

// Importa el modelo de Eloquent de MongoDB
use MongoDB\Laravel\Eloquent\Model as MongoEloquent;
use Laravel\Sanctum\Contracts\HasAbilities; // Trait requerido por Sanctum
use Carbon\Carbon; // Para manejar fechas
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;

class PersonalAccessToken extends MongoEloquent implements HasAbilities
{
    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';

    protected $fillable = [
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public function tokenable()
    {
        return $this->morphTo();
    }
/**
     * Find the token instance from the given raw token.
     *
     * Este método es crucial y debe ser implementado manualmente en tu modelo PersonalAccessToken
     * cuando no extiendes la clase de Jenssegers para Auth, sino directamente MongoEloquent.
     * Sanctum lo busca para autenticar.
     *
     * @param  string  $token
     * @return static|null
     */
    public static function findToken($token)
    {
        // Almacenamos el token en la base de datos como un hash SHA-256 por seguridad.
        return static::where('token', hash('sha256', $token))->first();
    }

    /**
     * Determine if the token has the given ability.
     *
     * @param  string  $ability  // <-- Mantenemos el PHPDoc para documentación
     * @return bool
     */
    public function can($ability) // <-- ¡CAMBIA LA FIRMA A SÓLO UN ARGUMENTO SIN TIPO!
    {
        return in_array('*', $this->abilities) ||
               (is_array($this->abilities) && in_array($ability, $this->abilities));
    }

    /**
     * Determine if the token does not have the given ability.
     *
     * @param  string  $ability // <-- Mantenemos el PHPDoc para documentación
     * @return bool
     */
    public function cant($ability) // <-- ¡CAMBIA LA FIRMA A SÓLO UN ARGUMENTO SIN TIPO!
    {
        return ! $this->can($ability);
    }

    /**
     * Determine if the token is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
