<?php

// app/Models/Audit.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date',
        'status',
        'auditor',
        'supervisor',
        'responsable',
        'incidencias_comentarios',
        'user_id',
        'restaurant_id',
        'category_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function verificationResponses()
    {
        return $this->hasMany(VerificationResponse::class);
    }
}

// Registro de auditorías realizadas por los Auditores a los Restaurantes.
