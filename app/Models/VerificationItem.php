<?php

// app/Models/VerificationItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'description',
        'type',
        'order'
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function responses()
    {
        return $this->hasMany(VerificationResponse::class);
    }
}