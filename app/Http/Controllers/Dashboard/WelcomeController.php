<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Shop;
use App\Models\Order;
use App\Role;
use App\Models\Product;
use App\Models\Notification;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class WelcomeController extends Controller
{


    /*----------------------------------------------------
  || Name     : show to dashboard index with total all cruds       |
  || Tested   : Done                                    |
  ||                                     |
  ||                                    |
    -----------------------------------------------------*/
    public function index()
    {

        $notification_count = Notification::where('user_id',Auth::user()->id)->where('read',0)->where('delete',0)->count();
        $notifications = Notification::where('user_id',Auth::user()->id)
        ->where('delete',0)
        ->orderBy('read','ASC')
        ->orderBy('created_at','DESC')
        ->get();
        session(['notification_count' => $notification_count, 'notifications'=> $notifications]);
        $roles = 0;
        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {


            $shops = auth()->user()->shops()->count();
            $users = User::where('shop_id', Session::get('shop_id'))->count();
            $orders = Order::where('user_id', Auth::id())->count();
            $products = Product::where('shop_id', Session::get('shop_id'))->count();


        } elseif(auth()->user()->type=='SuperAdmin') {

            $shops = Shop::count();
            $roles = Role::count();
            $users = User::count();
            $orders = Order::count();
            $products = Product::count();


        }else{
            
              $shops = 0;
            $roles = 0;
            $users = 0;
            $orders = 0;
            $products = 0; 
        }


        return view('dashboard.welcome', compact('shops', 'orders', 'products', 'roles', 'users','notifications','notification_count'));

    }//end of index

}//end of controller
