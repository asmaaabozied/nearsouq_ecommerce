<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cart_product_option extends Model
{
  
    protected $table = "cart_product_options";

    protected $guarded = [];

    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",


    ];
}
