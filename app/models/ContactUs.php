<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model 
{

    protected $table = 'contact_us';
    public $timestamps = true;
    protected $fillable = array('email', 'title', 'message', 'name', 'phone');

}