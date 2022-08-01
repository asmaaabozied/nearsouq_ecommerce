<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = "pages";
    protected $guarded = [];
    protected $appends = ['name','description'];

    protected $hidden = [

      
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
        
     'description_ar','description_en'

    ];

    public function getNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
    }

 

    public function getDescriptionAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->description_ar : $this->description_en;
    }


}
