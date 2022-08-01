<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
   protected $table="wallets";

   protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
