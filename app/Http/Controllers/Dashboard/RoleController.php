<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\RoleDatatables;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Response;
use DB;
use Validator;
class RoleController extends Controller
{

    /*----------------------------------------------------
     || Name     : show all roles                     |
     || Tested   : Done                                    |
     || using  : datatables                                      |
      ||                                    |
          -----------------------------------------------------*/
    public function index(RoleDatatables $roleDatatables)
    {
        if (auth()->user()->hasPermission('read_roles')) {

            return $roleDatatables->render('dashboard.datatable', [
                'title' => trans('site.roles'),
                'model' => 'roles',
                'count' => $roleDatatables->count()
            ]);
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }


    }//end of index


    /*----------------------------------------------------
    || Name     : open pages create                     |
    || Tested   : Done                                    |
    ||                                     |
     ||                                    |
     -----------------------------------------------------*/

    public function create()
    {
        if (auth()->user()->hasPermission('create_roles')) {

            $models = ['versions','deliveries', 'users', 'roles', 'wallets','reasons', 'shops', 'products', 'options', 'pages', 'orders', 'categories', 'malls', 'banners', 'settings', 'notifications', 'transactions', 'userReports','notificationproducts'];
            $maps = ['create', 'update', 'read', 'delete'];

            $mapss = Mapss;
            return view('dashboard.roles.create', compact('models', 'maps', 'mapss'));

        } else {


            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of create


    /*----------------------------------------------------
    || Name     : store data into database role          |
    || Tested   : Done                                    |
    ||                                     |
    ||                                    |
       -----------------------------------------------------*/

    public function store(Request $request)
    {
        Validator::extend('without_spaces', function($attr, $value){
    return preg_match('/^\S*$/u', $value);
});
        $request->validate([
            // 'name' => 'required|unique:roles',
              'name' => 'required|without_spaces|unique:roles|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
            'display_name' => 'required|without_spaces|unique:roles|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
//            'permissions' => 'required|min:1'
        ]);
//        DB::beginTransaction();
//        try {
            $request_data = $request->all();

            $role = role::create($request_data);
            


            if ($request->has('permissions'))
            
            

                $role->syncPermissions($request->permissions);
               
                
                
            if ($role) {

                flash(__('site.added_successfully'))->success();
                return redirect()->route('dashboard.roles.index');

//            session()->flash('success', __('site.added_successfully'));

            }
//            DB::commit();
//        } catch (\Exception $e) {
//            ///Roll the db back if something happened
//            DB::rollback();
//            return response([
//                'status' => 'error',
//                // 'error' => $e->getMessage(),
//                'message' => trans('site.Try_again_something_went_wrong.'),
//            ], 500);
//        }
//        return back();

    }//end of store


    /*----------------------------------------------------
 || Name     : redirect to edit pages          |
 || Tested   : Done                                    |
 ||                                     |
||                                    |
  -----------------------------------------------------*/

    public function edit(role $role)
    {
        if (auth()->user()->hasPermission('update_roles')) {

            $models = ['versions','deliveries', 'users', 'roles', 'wallets','reasons', 'shops', 'products', 'options', 'pages', 'orders', 'categories', 'malls', 'banners', 'settings', 'notifications', 'transactions', 'userReports','notificationproducts'];
            $maps = ['create', 'update', 'read', 'delete'];
            $mapss = Mapss;
            return view('dashboard.roles.edit', compact('role', 'models', 'maps', 'mapss'));
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of role


    /*----------------------------------------------------
     || Name     : update data into database using roles        |
     || Tested   : Done                                    |
       ||                                     |
        ||                                    |
           -----------------------------------------------------*/

    public function update(Request $request, role $role)
    {
                Validator::extend('without_spaces', function($attr, $value){
    return preg_match('/^\S*$/u', $value);
});
        $request->validate([
           'name' => 'required|without_spaces|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
            'display_name' => 'required|without_spaces|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
//            'permissions' => 'required|min:1'
        ]);
//        DB::beginTransaction();
//        try {
            $request_data = $request->all();


            $role->update($request_data);

            if ($request->has('permissions'))
                $role->syncPermissions($request->permissions);
            if ($role) {
                flash(__('site.updated_successfully'))->success();

//            session()->flash('success', __('site.updated_successfully'));
            } else {
                flash(__('site.update_faild'))->success();

//            session()->flash('error', __('site.update_faild'));
            }
            return redirect()->route('dashboard.roles.index');
//            DB::commit();
//        } catch (\Exception $e) {
//            ///Roll the db back if something happened
//            DB::rollback();
//            return response([
//                'status' => 'error',
//                // 'error' => $e->getMessage(),
//                'message' => trans('site.Try_again_something_went_wrong.'),
//            ], 500);
//        }
//        return back();

    }//end of update


    /*----------------------------------------------------
   || Name     : delete data into database using roles        |
   || Tested   : Done                                    |
   ||                                     |
   ||                                    |
     -----------------------------------------------------*/


    public function destroy(role $role)
    {
        if ($role->mandatory == true) {
            flash(__('site.Notdeleted_successfully'))->success();

//            session()->flash('success', __('site.Notdeleted_successfully'));

        } else {

            $result = $role->delete();
            if ($result) {
                flash(__('site.deleted_successfully'))->success();

//                session()->flash('success', __('site.deleted_successfully'));
            } else {
                flash(__('site.delete_faild'))->success();

//                session()->flash('error', __('site.delete_faild'));
            }
        }


        return redirect()->route('dashboard.roles.index');

    }//end of destroy

}//end of controller
