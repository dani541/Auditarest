<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- Relaciones ---

    /**
     * Un Usuario pertenece a un Rol. (FK: role_id)
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Un Usuario (Auditor) tiene muchas Auditorías asignadas.
     */
    public function audits(): HasMany
    {
        // El campo 'user_id' en la tabla 'audits' identifica al auditor
        return $this->hasMany(Audit::class);
    }
}


//Usuarios del sistema.
//  Incluye la identificación del Auditor que realiza las auditorías.