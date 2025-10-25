<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function teachings(): HasMany
    {
        return $this->hasMany(Teaching::class);
    }
}
