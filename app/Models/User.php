<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str; // Asegúrate de importar Str
use Carbon\Carbon; // Asegúrate de importar Carbon
use MongoDB\Laravel\Eloquent\Model as MongoEloquent;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'users';
    protected $connection = 'mongodb';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     /**
     * Create a new personal access token for the user.
     *
     * Sobreescribe el método createToken del trait HasApiTokens para compatibilidad con MongoDB.
     *
     * @param  string  $name
     * @param  array  $abilities
     * @param  \Carbon\Carbon|null  $expiresAt
     * @return object // Importante: el tipo de retorno es un objeto genérico
     */
    public function createToken(string $name, array $abilities = ['*'], ?Carbon $expiresAt = null)
    {
        $plainTextToken = Str::random(40);

        // Aquí se crea la instancia de tu App\Models\PersonalAccessToken
        // El método tokens() es definido por HasApiTokens y usa Sanctum::$personalAccessTokenModel
        // que debería estar configurado a App\Models\PersonalAccessToken::class en AppServiceProvider.
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
        ]);

        // Retorna un objeto estándar que imita la estructura de NewAccessToken,
        // evitando el error de tipo estricto.
        $tokenResult = new \stdClass();
        $tokenResult->accessToken = $token; // Tu instancia de App\Models\PersonalAccessToken
        $tokenResult->plainTextToken = $plainTextToken; // El token de texto plano

        return $tokenResult;
    }

    // Opcional: Si el método tokens() del trait HasApiTokens no está usando
    // tu PersonalAccessToken de MongoDB, puedes sobreescribirlo así:
    /*
    public function tokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable', null, null, '_id');
    }
    */
}
