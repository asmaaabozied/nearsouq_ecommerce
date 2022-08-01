<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;


class Cart extends Model
{


    protected $table = "carts";
    protected $guarded = [];
    protected $with=['variants'];

    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",


    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');

    }//end of Product


    //
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');

    }//end of shop

    public function cartProductOption()
    {
        return $this->hasMany(Cart_product_option::class, 'cart_id');

    }//end of shop



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of variant

    public function countProductPrice()
    {
        return $this->belongsToMany(Product::class, 'cart_product_options')->sum('price');

    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'cart_product_options');

    }//end of variant

    public function deliveryCalculator()
    {
        return $this->belongsToMany(Deliverycalculator::class, 'delivery_relation');

    }//end of deliveryCalculator
    
        public function costDeliveryCalculator()
    {
        return $this->belongsToMany(Deliverycalculator::class, 'delivery_relation')->select('cost');

    }

}
