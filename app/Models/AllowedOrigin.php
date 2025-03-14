<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedOrigin extends Model
{
    
    protected $table = 'allowed_origins';

    protected $fillable = [
        'origin',
    ];
}
