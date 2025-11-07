<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Un Rol tiene muchos Usuarios.
     */
    public function users(): HasMany
    {
        // Un rol puede ser asignado a muchos usuarios
        return $this->hasMany(User::class);
    }


}


//  Define los roles 
// de acceso al sistema (Administrador, Auditor, Restaurante).