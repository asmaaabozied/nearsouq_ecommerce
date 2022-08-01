<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order_products_option extends Model
{
    //

    use SoftDeletes;
    protected $table = "order_products_options";
    protected $appends = ['option_name','variant_name'];

    protected $guarded = [];

    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
        "variant_id",
        "option_id",
        "order_detail_id",
        "option_name_ar",
        "option_name_en",
        "variant_name_ar",
        "variant_name_en"


    ];
    
      public function getOptionNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->option_name_ar : $this->option_name_en;
    }// end of get name
    
         public function getVariantNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->variant_name_ar : $this->variant_name_en;
    }// end of get name

}
