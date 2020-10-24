<?php

namespace app\models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Client extends  Model implements Authenticatable
{

    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = array('phone', 'password', 'name', 'email', 'd_o_b', 'last_donation_date', 'city_id', 'blood_type_id');

    public function bloodType()
    {
        return $this->belongsTo('App\models\BloodType');
    }

    public function donationRequests()
    {
        return $this->hasMany('App\models\Donation_request');
    }

    public function city()
    {
        return $this->belongsTo('App\models\City');
    }

    public function notifications()
    {
        return $this->belongsToMany('App\models\Notification')->withPivot('is_seen');
    }

    public function governorates()
    {
        return $this->belongsToMany('App\models\Governorate');
    }

    public function posts()
    {
        return $this->belongsToMany('App\models\Post');
    }

    public function bloodTypes()
    {
        return $this->belongsToMany('App\models\BloodType');
    }

    protected $hidden = [
        'password', 'api_token',
    ];


    /////
    public function getAuthIdentifierName()
    {
        return ('s');
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return ('s');
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return ('s');
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return ('s');
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        return ('s');
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return ('s');
    }
}
