<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{

    protected $table = "products";
    protected $guarded = [];
    protected $appends = ['image_path', 'name', 'description'];
    protected $with = ['options'];


    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
        "image", "published"

        , 'extras', 'desc_ar', 'desc_en', 'category_id'
    ];

    public function getImagePathAttribute()
    {
        return asset('uploads/shops/products/' . $this->image);

    }//end of get image path

    public function getNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
    }// end of get name

    public function getDescriptionAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->desc_ar : $this->desc_en;
    }

    public function Shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_products','product_id','shop_id')->withPivot('quantity','shop_id');

    }//end of Shops
    
    public function Shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');

    }//end of Shops

    //

    public function options()
    {
        return $this->belongsToMany(Option::class, 'product_options');

    }//end of options


    public function category()
    {
        return $this->belongsTo(category::class, 'category_id');

    }//end of category

    public function ratings()
    {
        return $this->hasMany(ProductRating::class, 'product_id');

    }//end of ratings
    
     public function images()
    {
        return $this->hasMany(Image::class,'imageable_id');

    }//end of images

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'cart_product_options')->sum('extra_price');

    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'favorites')->withPivot('user_id');
    }//end of users
}
