<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    //

        protected $fillable = [
        'answer_value',
        'audit_id', // FK: auditorias
        'form_id',  // FK: formularios
    ];

    /**
     * Una Respuesta pertenece a una Auditoría. (FK: audit_id)
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Una Respuesta pertenece a una pregunta de Formulario. (FK: form_id)
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }





}

// Respuestas dadas en las auditorías a las preguntas del formulario.
