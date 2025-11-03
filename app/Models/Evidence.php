<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
     protected $fillable = [
        'file_path',
        'file_type',
        'description',
        'audit_id', // FK: auditorias
    ];

    /**
     * Una Evidencia pertenece a una Auditoría. (FK: audit_id)
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }
}

// Evidencias (archivos, fotos, documentos) asociadas a una auditoría.
