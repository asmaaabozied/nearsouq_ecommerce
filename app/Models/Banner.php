<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = "banners";
    protected $guarded = [];


    protected $hidden = [

        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
        "image"
    ];
    public function getImagePathAttribute()
    {
        return asset('uploads/shops/banners/' . $this->image);
    }




}
