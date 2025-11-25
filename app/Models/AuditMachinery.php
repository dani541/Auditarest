<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditMachinery extends Model
{
    protected $fillable = [
        'audit_id',
        'stove_condition', 'stove_notes',
        'oven_condition', 'oven_notes',
        'fryer_condition', 'fryer_notes',
        'refrigerator_condition', 'refrigerator_notes',
        'freezer_condition', 'freezer_notes',
        'microwave_condition', 'microwave_notes',
        'dishwasher_condition', 'dishwasher_notes',
        'maintenance_up_to_date', 'last_maintenance_date', 'maintenance_notes',
        'total_score',
        'percentage'
    ];

    protected $casts = [
        'stove_condition' => 'boolean',
        'oven_condition' => 'boolean',
        'fryer_condition' => 'boolean',
        'refrigerator_condition' => 'boolean',
        'freezer_condition' => 'boolean',
        'microwave_condition' => 'boolean',
        'dishwasher_condition' => 'boolean',
        'maintenance_up_to_date' => 'boolean',
        'last_maintenance_date' => 'date',
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
            
            $machinery = [
                'stove', 'oven', 'fryer', 'refrigerator',
                'freezer', 'microwave', 'dishwasher'
            ];
            
            foreach ($machinery as $item) {
                $conditionField = $item . '_condition';
                if ($model->$conditionField) {
                    $totalScore++;
                }
                $maxScore++;
            }
            
            $model->total_score = $totalScore;
            $model->percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 2) : 0;
            
            if ($model->audit) {
                $model->audit->calculateTotalScore();
            }
        });
    }
    public function isCompleted()
{
    $requiredFields = [
        'stove_condition',
        'oven_condition',
        'fryer_condition',
        'refrigerator_condition',
        'freezer_condition',
        'microwave_condition',
        'dishwasher_condition'
    ];

    foreach ($requiredFields as $field) {
        if (is_null($this->$field)) {
            return false;
        }
    }

    return true;
}
}