<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Reason extends Model
{


    protected $table = "reason";
    protected $guarded = [];
     protected $appends = [ 'name'];

    protected $hidden = [

        "updated_at",
       

    ];
    public function getNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
    }// end of get name




}
