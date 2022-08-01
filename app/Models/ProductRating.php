<?php

namespace App\Models;
use App\User;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $table = "product_ratings";
    protected $guarded = [];
    protected $appends = ['created'];

     protected $with = ['user'];
     
         protected $hidden = [


        "updated_at",
   
    ];

     
    
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of user
    
    
    function formatDate($date = '', $format = 'Y-m-d'){
    if($date == '' || $date == null)
        return;

    return date($format,strtotime($date));
}
    
    
      public function getCreatedAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->format('d-m-Y / h:i:s A');
        
    }
}
