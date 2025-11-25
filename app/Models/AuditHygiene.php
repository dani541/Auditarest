<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditHygiene extends Model
{
    protected $fillable = [
        'audit_id',
        // Higiene personal
        'uniforms_condition', 'uniforms_notes',
        'hand_washing_condition', 'hand_washing_notes',
        'hygiene_kits_condition', 'hygiene_kits_notes',
        // Manipulación de alimentos
        'food_handling_condition', 'food_handling_notes',
        'gloves_usage', 'gloves_notes',
        'hair_restraint_usage', 'hair_restraint_notes',
        // Limpieza y desinfección
        'cleaning_supplies_condition', 'cleaning_supplies_notes',
        'sanitization_procedures', 'sanitization_notes',
        // Almacenamiento
        'food_storage_condition', 'food_storage_notes',
        'chemical_storage_condition', 'chemical_storage_notes',
        // Puntuación
        'total_score',
        'percentage'
    ];

    protected $casts = [
        // Higiene personal
        'uniforms_condition' => 'boolean',
        'hand_washing_condition' => 'boolean',
        'hygiene_kits_condition' => 'boolean',
        // Manipulación de alimentos
        'food_handling_condition' => 'boolean',
        'gloves_usage' => 'boolean',
        'hair_restraint_usage' => 'boolean',
        // Limpieza y desinfección
        'cleaning_supplies_condition' => 'boolean',
        'sanitization_procedures' => 'boolean',
        // Almacenamiento
        'food_storage_condition' => 'boolean',
        'chemical_storage_condition' => 'boolean',
        // Puntuación
        'total_score' => 'float',
        'percentage' => 'float'
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            $totalScore = 0;
            $maxScore = 0;
            
            // Lista de campos booleanos a evaluar
            $booleanFields = [
                // Higiene personal
                'uniforms_condition',
                'hand_washing_condition',
                'hygiene_kits_condition',
                // Manipulación de alimentos
                'food_handling_condition',
                'gloves_usage',
                'hair_restraint_usage',
                // Limpieza y desinfección
                'cleaning_supplies_condition',
                'sanitization_procedures',
                // Almacenamiento
                'food_storage_condition',
                'chemical_storage_condition'
            ];
            
            // Calcular puntuación basada en condiciones booleanas
            foreach ($booleanFields as $field) {
                if ($model->$field) {
                    $totalScore++;
                }
                $maxScore++;
            }
            
            $model->total_score = $totalScore;
            $model->percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 2) : 0;
            
            // Actualizar el puntaje total en la auditoría
            if ($model->audit) {
                $model->audit->calculateTotalScore();
            }
        });
    }
public function isCompleted()
{
    $requiredFields = [
        'uniforms_condition',
        'hand_washing_condition',
        'hygiene_kits_condition',
        'food_handling_condition',
        'gloves_usage',
        'hair_restraint_usage',
        'cleaning_supplies_condition',
        'sanitization_procedures',
        'food_storage_condition',
        'chemical_storage_condition'
    ];

    foreach ($requiredFields as $field) {
        if (is_null($this->$field)) {
            return false;
        }
    }

    return true;
}
    
}