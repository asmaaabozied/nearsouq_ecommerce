<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{

    protected $table = "payment_methods";
    protected $guarded = [];
    protected $appends = ['name','icon_path'];

    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by", "name_ar", "name_en","entity_id","public_key","enable",'secret_key'


    ];

    public function getNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
    }// end of get name
    
       public function getIconPathAttribute()
    {
        return asset('uploads/shops/payments/' . $this->icon);

    }//end of get image path
}
