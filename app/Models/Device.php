<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table="devices";
    protected $fillable = array('user_id','oauth_access_tokens_id','login_status','last_login_date','one_signal_id');
}
