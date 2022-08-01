<?php

namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = "ratings";
    protected $guarded = [];
         protected $with = ['user'];
     
         protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by"
   
    ];

     
    
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of user
}
