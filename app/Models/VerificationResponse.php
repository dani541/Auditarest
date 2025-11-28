<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationResponse extends Model
{
    protected $fillable = [
        'audit_id',
        'verification_item_id',
        'status',
        'corrective_measure',
        'temperature'
    ];

    protected $casts = [
        'temperature' => 'decimal:2',
    ];

    /**
     * La auditoría a la que pertenece esta respuesta
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * El ítem de verificación al que corresponde esta respuesta
     */
    public function verificationItem(): BelongsTo
    {
        return $this->belongsTo(VerificationItem::class);
    }
}
