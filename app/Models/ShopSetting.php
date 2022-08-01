<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    protected $table="shop_settings";

    protected $fillable = ['shop_id','payment','created_by'];
}
