<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    protected $table = 'cities';
    public $timestamps = true;
    protected $fillable = array('name', 'governorate_id');

    public function governorate()
    {
        return $this->belongsTo('App\models\Governorate');
    }

    public function donationRequests()
    {
        return $this->hasMany('App\models\Donation_request');
    }

    public function clients()
    {
        return $this->hasMany('App\models\Client');
    }
}
