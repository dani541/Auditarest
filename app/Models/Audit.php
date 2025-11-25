<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Audit extends Model
{
    protected $fillable = [
        'restaurant_id',
        'auditor',
        'date',
        'supervisor',
        'general_notes',
        'is_completed',
        'total_score'
    ];

    protected $casts = [
        'date' => 'date',
        'is_completed' => 'boolean',
        'total_score' => 'decimal:2'
    ];

    /**
     * Obtiene el restaurante asociado a la auditoría.
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Obtiene la infraestructura evaluada en la auditoría.
     */
    public function infrastructure(): HasOne
    {
        return $this->hasOne(AuditInfrastructure::class);
    }

    /**
     * Obtiene la maquinaria evaluada en la auditoría.
     */
    public function machinery(): HasOne
    {
        return $this->hasOne(AuditMachinery::class);
    }

    /**
     * Obtiene la evaluación de higiene de la auditoría.
     */
    public function hygiene(): HasOne
    {
        return $this->hasOne(AuditHygiene::class);
    }

    /**
     * Obtiene el usuario que realizó la auditoría.
     */
    public function auditorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    /**
     * Obtiene el supervisor de la auditoría.
     */
    public function supervisorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // In app/Models/Audit.php

    public function verificationItems()
    {
        return $this->hasMany(VerificationItem::class);
    }

    /**
     * Calcula el puntaje total de la auditoría.
     */
    public function calculateTotalScore(): void
    {
        $total = 0;
        $count = 0;
        
        // Sumar los puntajes de todas las secciones
        foreach (['infrastructure', 'machinery', 'hygiene'] as $relation) {
            if ($this->$relation && $this->$relation->total_score !== null) {
                $total += $this->$relation->total_score;
                $count++;
            }
        }
        
        // Calcular el promedio si hay secciones con puntaje
        $this->total_score = $count > 0 ? round($total / $count, 2) : null;
        $this->save();
    }

    /**
     * Marca la auditoría como completada.
     */
    public function markAsCompleted(): void
    {
        $this->is_completed = true;
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Obtiene el progreso de la auditoría.
     */
   public function getProgressAttribute()
{
    $sections = ['infrastructure', 'machinery', 'hygiene'];
    $completed = 0;
    
    foreach ($sections as $section) {
        if ($this->$section && $this->$section->isCompleted()) {
            $completed++;
        }
    }
    
    // Fix: Remove the $ before count
    return count($sections) > 0 ? (int) (($completed / count($sections)) * 100) : 0;
}

    /**
     * Scope para obtener auditorías completadas.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope para obtener auditorías pendientes.
     */
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Obtiene el estado de la auditoría.
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_completed) {
            return 'Completada';
        }
        
        $progress = $this->progress;
        
        if ($progress === 0) {
            return 'Pendiente';
        } elseif ($progress < 100) {
            return 'En progreso';
        }
        
        return 'Pendiente de revisión';
    }
}