<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditQuestion extends Model
{
    protected $fillable = ['category_id', 'question', 'order'];

    public function category()
    {
        return $this->belongsTo(AuditCategory::class);
    }
}