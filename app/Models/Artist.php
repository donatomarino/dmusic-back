<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artist extends Model
{
    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }
}
