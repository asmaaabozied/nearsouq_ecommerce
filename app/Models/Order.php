<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class Order extends Model
{

    use SoftDeletes;
    
    protected $table = "orders";

    protected $guarded = [];

    protected $hidden = [
     
    
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by"

    ];
    
      public function shops()
    {
        return $this->belongsToMany(Shop::class, 'order_details')->withPivot('order_id', 'shop_id','created_at');

    }//end of shops
    
    public function address()
    {
        return $this->belongsTo(Address::class)->withdefault();

    }//end of address
    
     public function PaymentType()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_type','code');

    }//end of PaymentType
    

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');

    }//end of orderDetails
    
        public function user(){
        return $this->belongsTo(User::class, 'user_id') ;
    }
}
