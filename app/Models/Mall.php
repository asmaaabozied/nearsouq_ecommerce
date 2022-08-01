<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class Mall extends Model
{
    use SoftDeletes;
    protected $table = "malls";
    protected $guarded = [];
    protected $appends = ['image_path','name','description'];
    protected $with=['shops'];
    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
       // "image","visible",'name_ar','name_en','desc_ar','desc_en','mall_category_id'

    ];
    public function getNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
    }

    public function getImagePathAttribute()
    {
        return asset('images/malls/' . $this->image);

    }//end of get image path

    public function getDescriptionAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->desc_ar : $this->desc_en;
    }


     public function category()
    {
        return $this->belongsTo(category::class, 'mall_category_id');

    }//end of category

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'rated_id')->where('type','Mall');

    }//end of ratings

        public function shops()
    {
        return $this->hasMany(Shop::class, 'mall_id')->where('shops.published','TRUE')
            ->where(DB::raw("(SELECT COUNT(*)  as shop_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0 )"), '>', 0);
            

    }//end of ratings
}
