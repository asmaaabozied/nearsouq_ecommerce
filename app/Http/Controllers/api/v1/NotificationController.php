<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelLocalization;

class NotificationController extends Controller
{

    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }

    /*---------------------------------------------
    || Name     : show notifications for auth user |
    || Tested   : Done                             |
    || parameter:                                  |
    || Info     : type                             |
    ----------------------------------------------*/
    public function ShowNotification()
    {
        $users = auth()->user();
        $notification = $users->notifications;
        $notifications = NotificationResource::collection($notification);

        return $this->responseWithoutMessageJson(1,$notifications);
    }
}
