<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VerificationItem extends Model
{
    protected $fillable = [
        'category',
        'description',
        'type',
        'order'
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Las respuestas asociadas a este ítem de verificación
     */
    public function verificationResponses(): HasMany
    {
        return $this->hasMany(VerificationResponse::class);
    }

    /**
     * Las auditorías relacionadas a través de las respuestas
     */
    public function audits(): BelongsToMany
    {
        return $this->belongsToMany(Audit::class, 'verification_responses')
                    ->withPivot(['status', 'corrective_measure', 'temperature'])
                    ->withTimestamps();
    }

    /**
     * Obtener ítems por categoría
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category)->orderBy('order');
    }
}
