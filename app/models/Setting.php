<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = array('about_app', 'phone', 'email', 'fb_link', 'tw_link', 'insta_link', 'yt_link', 'notifications_settings_note');
}
