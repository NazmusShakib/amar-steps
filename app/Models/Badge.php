<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Badge extends Model
{
    use SoftDeletes;

    protected $table = 'badges';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'target', 'badge_icon', 'description', 'unit_id'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_by', 'unit_id'
    ];

    protected $with = ['unit'];

    //Add extra attribute
    protected $attributes = ['status'];

    //Make it available in the json response
    protected $appends = ['status'];

    public function getStatusAttribute()
    {
        $unlock = 'locked';
        $status = Badge::whereHas('user', function ($q) {
            $q->where('users.id', '=', Auth::id());
        })->find($this->id);
        if ($status) {
            $unlock = 'unlocked';
        }
        return $unlock;
    }


    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')
            ->select('id', 'name', 'email', 'phone');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo(BadgeUnit::class, 'unit_id')
            ->select('id', 'actual_name', 'short_name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsToMany(User::class, 'user_badge','badge_id', 'user_id')->withTimestamps();
    }

}
