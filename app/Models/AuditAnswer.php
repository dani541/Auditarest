<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditAnswer extends Model
{
    protected $fillable = ['audit_id', 'question_id', 'complies', 'incidence'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function question()
    {
        return $this->belongsTo(AuditQuestion::class);
    }
}