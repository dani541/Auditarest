<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Audit extends Model
{
    protected $fillable = [
        'scheduled_date',
        'status',
        'user_id',       // FK: auditor
        'restaurant_id', // FK: restaurante
        'category_id',   // FK: categoría
        'observations',  // Observaciones generales
    ];
    
    protected $dates = [
        'scheduled_date',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'scheduled_date' => 'date',
    ];

    // --- Relaciones ---

    /**
     * La Auditoría está asignada a un Auditor. (FK: user_id)
     */
    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * La Auditoría se realiza a un Restaurante. (FK: restaurant_id)
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * La Auditoría es de una Categoría específica. (FK: category_id)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Una Auditoría tiene muchas Respuestas de verificación.
     */
    public function verificationResponses(): HasMany
    {
        return $this->hasMany(VerificationResponse::class);
    }
    
    /**
     * Los ítems de verificación asociados a través de las respuestas.
     */
    public function verificationItems(): BelongsToMany
    {
        return $this->belongsToMany(VerificationItem::class, 'verification_responses')
                    ->withPivot(['status', 'corrective_measure', 'temperature'])
                    ->withTimestamps();
    }
    
    /**
     * Respuestas heredadas (mantener compatibilidad)
     */
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Una Auditoría tiene muchas Evidencias.
     */
    public function evidences()
    {
        return $this->hasMany(Evidence::class);
    }
    
    /**
     * Obtener el porcentaje de cumplimiento de la auditoría
     */
    public function getCompliancePercentageAttribute()
    {
        $total = $this->verificationItems()->count();
        if ($total === 0) {
            return 0;
        }
        
        $compliance = $this->verificationItems()
            ->where('verification_responses.status', 'C')
            ->count();
            
        return round(($compliance / $total) * 100, 2);
    }
    
    /**
     * Obtener el estado de la auditoría con formato
     */
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pendiente' => 'warning',
            'en_curso' => 'info',
            'completada' => 'success',
            'vencida' => 'danger',
        ];
        
        $status = strtolower($this->status);
        $class = $statuses[$status] ?? 'secondary';
        
        return '<span class="badge bg-' . $class . '">' . ucfirst($status) . '</span>';
    }






}

// Registro de auditorías realizadas por los Auditores a los Restaurantes.
