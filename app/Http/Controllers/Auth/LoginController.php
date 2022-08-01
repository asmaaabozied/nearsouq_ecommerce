<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated (Request $request , User $user){
        if($user){
            // if($user->type=='SuperAdmin'){
            //     return redirect('/dashboard');
            // }
            if($user->hasRole('Vendor') || $user->hasRole('Merchant'))
            {
               
                    if (isset(auth()->user()->shops) && !auth()->user()->shops->isEmpty()) {
                  
                      return redirect('/dashboard/ShowShopsAuth');  
                }else{
            
                Auth::logout();
               $redirect='/login';
               session()->flash('success', __('site.no_vendor_found'));
                 return back();
                }
                
            
            }
            elseif($user->hasRole('SuperAdmin') || $user->hasRole('Admin')){
                return redirect('/dashboard');

            }
        }
        else{
            Auth::logout();
            $redirect='/login';
            return back()->withErrors( __('site.messages.auth_faild') );

        }
    }
}
