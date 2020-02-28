<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeUnit extends Model
{
    protected $table = 'units';

    public function badge()
    {
        return $this->hasOne(Badge::class, 'unit_id');
    }
}
