<?php

namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    protected $guarded=[];

    protected $hidden = [
        'created_by', 'updated_by',
        "deleted_at",
        "deleted_by",
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
