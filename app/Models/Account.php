<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Account extends Model
{
    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
    ];

    public function contacts(): MorphMany{

        return $this->morphMany(Contact::class, 'source');
        
    }
}
