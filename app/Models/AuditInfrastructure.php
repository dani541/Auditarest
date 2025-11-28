<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditInfrastructure extends Model
{
protected $fillable = [
    'audit_id',
    'floor_condition', 'floor_notes',
    'walls_condition', 'walls_notes',
    'windows_condition', 'windows_notes',
    'doors_condition', 'doors_notes',
    'ceiling_condition', 'ceiling_notes',
    'lighting_condition', 'lighting_notes',
    'countertops_condition', 'countertops_notes',
    'work_tables_condition', 'work_tables_notes',
    'additional_notes',
    'total_score',
    'percentage'
];

protected $casts = [
    'floor_condition' => 'boolean',
    'walls_condition' => 'boolean',
    'windows_condition' => 'boolean',
    'doors_condition' => 'boolean',
    'ceiling_condition' => 'boolean',
    'lighting_condition' => 'boolean',
    'countertops_condition' => 'boolean',
    'work_tables_condition' => 'boolean',
    'total_score' => 'float',
    'percentage' => 'float'
];

protected static function booted()
{
    static::saving(function ($model) {
        $totalScore = 0;
        $maxScore = 0;
        
        // Calcular puntuación basada en condiciones booleanas
        $fields = ['floor', 'walls', 'windows', 'doors', 'ceiling', 'lighting', 'countertops', 'work_tables'];
        
        foreach ($fields as $field) {
            $conditionField = $field . '_condition';
            if ($model->$conditionField) {
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
// En app/Models/AuditInfrastructure.php

public function isCompleted()
{
    // Verifica que todos los campos requeridos estén completos
    $requiredFields = [
        'floor_condition', 
        'walls_condition',
        'windows_condition',
        'doors_condition',
        'ceiling_condition',
        'lighting_condition',
        'countertops_condition',
        'work_tables_condition'
    ];

    foreach ($requiredFields as $field) {
        if (is_null($this->$field)) {
            return false;
        }
    }

    return true;
}


}