<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    
     protected $fillable = [
        'name',
        'address',
        'city',
        'contact_name',
        'contact_phone',
        'contact_email',
    ];

   
    /**
     * Get the audits for the restaurant.
     */
    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class, 'restaurant_id');
    }


}

//Establecimiento que va a ser auditado.
