<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
      use SoftDeletes;
    protected $table = "variants";
    protected $guarded = [];
    protected $appends = ['image_path', 'name'];
    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
        'name_en','name_ar','pivot'

    ];

    public function getNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
    }// end of get name

    public function getImagePathAttribute()
    {
        return asset('uploads/shops/products/' . $this->image);

    }//end of get image path
}
