<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Contact extends Model
{
     protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
