<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class Gateway extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'serial_number', 'name', 'IPv4_address', 'peripheral',
    ];

    public function peripheral(): HasOneOrMany
    {
        return $this->hasMany(Peripheral::class);
    }
}
