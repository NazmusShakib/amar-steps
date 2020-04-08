<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUnitTotal extends Model
{
    protected $table = 'user_unit_totals';

    protected $fillable = ['user_id', 'unit_id', 'grand_total'];

    protected $hidden = ['pivot'];
}
