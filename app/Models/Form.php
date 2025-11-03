<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'question',
        'type',
        'category_id', // FK: categories
    ];

    /**
     * Un Formulario pertenece a una Categoría. (FK: category_id)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Un Formulario puede tener muchas Respuestas.
     */
    public function responses(): HasMany
    {
        // El campo 'form_id' en la tabla 'responses'
        return $this->hasMany(Response::class);
    }
}

// Preguntas del formulario de auditoría.
