<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Donation_request extends Model
{

    protected $table = 'donation_requests';
    public $timestamps = true;
    protected $fillable = array('patient_name', 'patient_age', 'hospital_name', 'blood_type_id', 'num_of_blood_bags', 'hospital_address', 'city_id', 'patient_phone', 'notes', 'client_id', 'latitude', 'longitude');

    public function bloodType()
    {
        return $this->belongsTo('App\models\BloodType');
    }

    public function city()
    {
        return $this->belongsTo('App\models\City');
    }

    public function client()
    {
        return $this->belongsTo('App\models\Client');
    }
    public function notifications()
    {
        return $this->hasMany('App\models\Notification');
    }
}
