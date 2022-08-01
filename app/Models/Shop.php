<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;

    protected $table = "shops";


    protected $guarded = [];


    protected $appends = ['image_path', 'name', 'description', 'brand_name'];
    protected $with = ['category', 'Products'];
    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
        "published"
        , 'extras'


    ];

    public function getNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
    }

    public function getBrandNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->brand_name_ar : $this->brand_name_en;
    }

    public function getImagePathAttribute()
    {
        return asset('uploads/shops/profiles/' . $this->image);

    }//end of get image path

    public function getDescriptionAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->desc_ar : $this->desc_en;
    }


    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details')->withPivot('order_id', 'shop_id');

    }//end of orders


    public function Products()
    {
        return $this->belongsToMany(Product::class, 'shop_products')->withPivot('product_id', 'shop_id', 'quantity');

    }//end of Products

    public function OfferProducts()
    {
        return $this->belongsToMany(Product::class, 'shop_products')->whereNotNull('discount_price');

    }//end of OfferProducts


    public function ShopParent()
    {

        return $this->hasMany(Shop::class, 'parent_id');


    }


    public function getQuantity()
    {

//        return $this->Products->pivot->quantity ?? '';

    } //getQuantity

    public function category()
    {
        return $this->belongsTo(category::class, 'category_id');

    }//end of category

    //
    public function mall()
    {
        return $this->belongsTo(Mall::class, 'mall_id');

    }//end of mall

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'rated_id')->where('type', 'Shop');

    }//end of ratings

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');

    }//end of user
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'shop_id');

    }//end of transactions

    public function last_transaction()
    {
        return $this->hasOne(Transaction::class, 'shop_id')->latest();

    }//end of last_transaction
}
