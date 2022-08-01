<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if(str_contains(url()->current(), 'api')){
            abort(response()->json(['error' => 'Unauthenticated.'], 401));
        }elseif (! $request->expectsJson()) {
            return route('login');
        }
    }
    
        /*protected function unauthenticated($request, array $guards)
    {
        if(str_contains(url()->current(), 'api')){
            if (!Auth::guard('api')->check()){
                abort(response()->json(['error' => 'Unauthenticated.'], 401));
            }
        }
        else{
            $this->redirectTo($request);
        }
    }*/
}
