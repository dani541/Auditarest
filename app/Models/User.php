<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Un Usuario puede tener un restaurante asignado
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Un Usuario (auditor) puede auditar muchos restaurantes
     */
public function auditedRestaurants()
{
    return $this->hasManyThrough(
        Restaurant::class,  // Modelo de destino
        Audit::class,       // Modelo intermedio
        'auditor',          // Clave foránea en la tabla audits (usamos el nombre del auditor)
        'id',               // Clave foránea en la tabla restaurants
        'name',             // Clave local en users (el nombre del usuario)
        'restaurant_id'     // Clave local en audits
    )->where('audits.auditor', $this->name);  // Aseguramos que coincida el nombre
}

    /**
     * Un Usuario (Auditor) tiene muchas Auditorías asignadas.
     */
    public function audits(): HasMany
    {
        // El campo 'user_id' en la tabla 'audits' identifica al auditor
        return $this->hasMany(Audit::class);
    }

  

   // En app/Models/User.php
public function hasRole($roleName)
{
    return $this->role && $this->role->name === $roleName;
}
}


//Usuarios del sistema.
//  Incluye la identificación del Auditor que realiza las auditorías.