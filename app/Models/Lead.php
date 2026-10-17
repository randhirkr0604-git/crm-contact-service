<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    protected $fillable = [
        'lead_name',
        'contact_name',
        'email',
        'phone',
    ];

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'source');
    }

}
