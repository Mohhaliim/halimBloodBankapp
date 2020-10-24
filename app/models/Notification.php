<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = array('title', 'content', 'donation_request_id');

    public function clients()
    {
        return $this->belongsToMany('App\models\Client')->withPivot('is_seen');
    }
    public function donationRequest()
    {
        return $this->belongsTo('App\models\Donation_request');
    }
}
