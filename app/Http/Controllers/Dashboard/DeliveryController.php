<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ShopDatatables;

use App\DataTables\DeliveryDatatable;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Database\QueryException;
use Response;
use Hash;
use DB;
use App\DataTables\CaptainOrdersDatatables;
use App\Models\Order;

class DeliveryController extends Controller
{

    /*----------------------------------------------------
    || Name     : show all deliveries                     |
    || Tested   : Done                                    |
    || using  : datatables                                |
    ||                                                    |
    -----------------------------------------------------*/
    public function index(DeliveryDatatable $deliveryDatatables)
    {
        if (auth()->user()->hasPermission('read_users')) {
            return $deliveryDatatables->render('dashboard.Deliverydatatable', [
                'title' => trans('site.deliveries'),
                'model' => 'users',
                'count' => $deliveryDatatables->count()
            ]);
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of index


    public function showAddress($id){
        $user = User::findOrFail($id);
        //dd($user);
        return view('dashboard.map', compact('user'));
    }

    public function showCaptainOrders($id, CaptainOrdersDatatables $orderDatatables){
        if (auth()->user()->hasPermission('read_orders')) {
            $orderDatatables->captain_id = $id;
            return $orderDatatables->render('dashboard.datatable', [
                'title' => trans('site.orders'),
                'model' => 'order',
                'count' => $orderDatatables->count(),
                'captain_id' => $id
            ]);
        }
        else{
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }

    public function show(){
        $users = User::where('type','Delivery')->get();
        //dd($user);
        return view('dashboard.allMap', compact('users'));
    }
    

}//end of controller
