<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Una Categoría puede tener muchos Formularios.
     */
    public function forms(): HasMany
    {
        // El campo 'category_id' en la tabla 'forms'
        return $this->hasMany(Form::class);
    }
}
