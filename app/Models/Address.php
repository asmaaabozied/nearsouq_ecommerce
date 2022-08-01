<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Address extends Model
{
       use SoftDeletes;
    protected $table="addresses";
    protected $guarded=[];
        protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
       "phone",

     
        
        

    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of user
}
