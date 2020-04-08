<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BadgeUnit extends Model
{
    protected $table = 'units';

    public function badge()
    {
        return $this->hasOne(Badge::class, 'unit_id');
    }

    public function userUnitTotal()
    {
        return $this->hasOne(UserUnitTotal::class, 'unit_id')
            ->where('user_id', Auth::id())->select('grand_total', 'unit_id');
    }
}
