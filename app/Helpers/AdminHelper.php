<?php


namespace App\Helpers;


use App\User;
use Illuminate\Support\Facades\Auth;

class AdminHelper
{

    protected static $guard_web = 'web';

    /**
     * @return User
     */
    public static function currentAdmin() {
        return User::find(self::currentUser()->id);
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function currentUser() {
        return Auth::guard(self::$guard_web)->user();
    }

    /**
     * @return mixed
     */
    public static function userId() {
        return self::currentAdmin()->id;
    }

    /**
     * @return mixed
     */
    public static function name() {
        return self::currentAdmin()->name;
    }

    /**
     * @return mixed
     */
    public static function email() {
        return self::currentAdmin()->email;
    }

    /**
     * @return mixed
     */
    public static function apiToken() {
        return self::currentAdmin()->api_token;
    }

    /**
     * @return mixed
     */
    public static function isSuperAdmin() {
        return self::currentAdmin()->hasRole('super_admin');
    }

    /**
     * @param $role
     * @return mixed
     */
    public static function hasRole($role) {
        return self::currentAdmin()->hasRole($role);
    }
}
