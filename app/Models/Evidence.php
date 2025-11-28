<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
     protected $fillable = [
        'path',
        'original_name',
        'mime_type',
        'size',
        'audit_id',
    ];

    protected $appends = ['url'];
    
    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Una Evidencia pertenece a una Auditoría.
     */
    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Obtener la URL de la evidencia
     */
    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * Obtener el tamaño del archivo formateado
     */
    public function getFormattedSizeAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->size;
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}

// Evidencias (archivos, fotos, documentos) asociadas a una auditoría.
