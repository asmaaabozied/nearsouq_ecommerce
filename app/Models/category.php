<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class category extends Model
{
    use SoftDeletes;
    protected $table = "categories";
    protected $guarded = [];
    protected $appends = ['image_path', 'name', 'description'];

    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",


    ];

    public function getImagePathAttribute()
    {
        return asset('uploads/shops/categories/' . $this->image);

    }//end of get image path

    public function getNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
    }// end of get name

    public function getDescriptionAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->description_ar : $this->description_en;
    }
}
