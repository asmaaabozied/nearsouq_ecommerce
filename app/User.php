<?php

namespace App;

use App\Models\Notification;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Traits\LaratrustUserTrait;
use Sqits\UserStamps\Concerns\HasUserStamps;

// use Modules\


class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, Notifiable;
    use HasUserStamps;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'shop_id', 'last_name','name', 'email', 'phone', 'password', 'code', 'image', 'type', 'status', 'latitude', 'address', 'longitude', 'platform','online_status'
    ];

    protected $appends = ['image_path'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_by', 'updated_by', 'password', "version_no",
        "created_at",
        "updated_at",
        "deleted_at","platform",
        "deleted_by","onesignal_id","_token","code","status","email_verified_at","typeReg","address","last_name"
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime', 'email_verified_at' => 'phone_verified_at',
    ];

    public function getImagePathAttribute()
    {
        return asset('uploads/shops/profiles/' . $this->image);

    }//end of get image path
//    public function roles()
//    {
//        return $this->belongsToMany(Role::class, 'role_user');
//
//    }//end of roles

    public function products()
    {
        return $this->belongsToMany(Product::class, 'favorites');

    }//end of products

      public function productWatch()
    {
        return $this->belongsToMany(Product::class, 'visitor_product')->withPivot(['product_id','user_id','category_id','seen_count']);

    }//end of products

      public function shops()
    {
        return $this->belongsToMany(Shop::class, 'merchants')->withPivot(['user_id','shop_id','active']);

    }//end of products

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');

    }//end of variants


}//end of model
