<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity', 'notes', 'thumbnail', 'user_id',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'activity' => 'json'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->user_id = Auth::id();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->select('id', 'name', 'email', 'phone');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'DESC')->get();
    }
}
