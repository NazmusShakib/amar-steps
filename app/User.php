<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Twilio\Rest\Client;
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
        return !is_null($this->phone_verified_at);
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

    public function callToVerify()
    {
        $code = random_int(100000, 999999);

        $this->forceFill([
            'verification_code' => $code
        ])->save();

        $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        $message = $client->messages->create(
            '+88'.$this->phone,
            [
                "body" => "The Robots are coming! Head for the hills!:: {$code}",
                "from" => "+16038997505",
                "statusCallback" => "http://127.0.0.1:8000/api/v1/build-twiml/{$code}"]
        );

        // print($message->sid);
    }
}
