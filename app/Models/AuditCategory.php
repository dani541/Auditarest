<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditCategory extends Model
{
    protected $fillable = ['name', 'order'];

    public function questions()
    {
        return $this->hasMany(AuditQuestion::class)->orderBy('order');
    }
}