<?php

namespace App\Models;
use HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    //

    protected $fillable = [
        'scheduled_date',
        'status',
        'user_id',       // FK: auditor
        'restaurant_id', // FK: restaurante
        'category_id',   // FK: categoría
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
     * Una Auditoría tiene muchas Respuestas.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Una Auditoría tiene muchas Evidencias.
     */
    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }






}

// Registro de auditorías realizadas por los Auditores a los Restaurantes.
