<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'user_code',
        'height', 'weight', 'headshot', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
	];

	public function hasVerifiedPhone()
        {
            return ! is_null($this->phone_verified_at);
        }

        public function markPhoneAsVerified()
        {
            return $this->forceFill([
                'phone_verified_at' => $this->freshTimestamp(),
            ])->save();
        }

    /*Standard methods removed for brevity*/
    public function roles()
    {
        return $this->belongsToMany(Role::class)->select('name', 'display_name');
    }

    /**
     * Return role with auth object
     * @var array
     */
    // protected $with = ['roles'];


    /**
     * return user profile
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class)
            ->select('gender', 'dob', 'country', 'city', 'bio', 'address');
    }
}
