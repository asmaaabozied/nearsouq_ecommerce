<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\UserDatatables;

use App\Models\Merchant;
use App\Models\Shop;
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
use DB;

class UserController extends Controller
{

    /*----------------------------------------------------
      || Name     : show all users                     |
      || Tested   : Done                                    |
      || using  : datatables                                      |
       ||                                    |
           -----------------------------------------------------*/


    public function index(UserDatatables $userDatatables)
    {
        if (auth()->user()->hasPermission('read_users')) {


            return $userDatatables->render('dashboard.datatable', [
                'title' => trans('site.users'),
                'model' => 'users',
                'count' => $userDatatables->count()
            ]);
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of index


    /*----------------------------------------------------
         || Name     : open pages show using id                     |
         || Tested   : Done                                    |
         ||                                     |
          ||                                    |
          -----------------------------------------------------*/


    public function show($id)
    {
        if (auth()->user()->hasPermission('read_users')) {

            $user = User::find($id);

            $roles = Role::all();

            return view('dashboard.users.show', compact('user', 'roles'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }

    public function showshop($id)
    {
        if (auth()->user()->hasPermission('read_users')) {


            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
                // $shops = Shop::where('employe_id', Auth::id())->get();
                $shops = auth()->user()->shops()->get();
                $shopselected=Merchant::where('user_id',$id)->pluck('shop_id')->toArray();
            } else {
                $shops = Shop::all();
                  $shopselected=Merchant::where('user_id',$id)->pluck('shop_id')->toArray();

            }


            return view('dashboard.users.showshop', compact('shops','shopselected'));
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }

    }


    /*----------------------------------------------------
        || Name     : open pages create                     |
        || Tested   : Done                                    |
        ||                                     |
         ||                                    |
         -----------------------------------------------------*/


    public
    function create()
    {
        if (auth()->user()->hasPermission('create_users')) {

            $roles = Role::all();
            return view('dashboard.users.create', compact('roles'));
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of create

    /*----------------------------------------------------
        || Name     : SaveShopUser         |
        || Tested   : Done                                    |
        ||                                     |
        ||                                    |
           -----------------------------------------------------*/
    public function SaveShopUser(Request $request)
    {

        foreach ($request->shop_id as $shop) {

            $shops = Merchant::create([
                'user_id' => Auth::id(),
                'shop_id' => $shop
            ]);

        }

        if ($shops) {


            flash(__('site.added_successfully'))->success();
        }
        return redirect(route('dashboard.users.index'));


    }


    /*----------------------------------------------------
      || Name     : store data into database users          |
      || Tested   : Done                                    |
      ||                                     |
      ||                                    |
         -----------------------------------------------------*/

    public
    function store(Request $request)
    {

        $request->validate([

            'email' => 'required|email|string|unique:users',
            'phone' => 'required|string|unique:users',

//            'password' => 'required',
            'password' => 'required|confirmed',
//            'roles' => 'required'
        ],
            [
                'password.regex' => __("site.password_regex"),
//                'roles.required' => __("site.roles_required"),
            ]
        );

//
//        DB::beginTransaction();
//        try {
            $request_data = $request->except(['password_confirmation', 'permissions']);
            $shop_id = Session::get('shop_id') ?? $request->shop_id;

            // To Make User Active
            $request_data['status'] = 1;
            //   $request_data['type'] =Role::wherein('id',$request->roles)->first()->name ?? '';
            $request_data['shop_id'] = $shop_id;
            $request_data['password'] = bcrypt($request->password);

            $user = User::create($request_data);

            if ($request->hasFile('image')) {
//                $thumbnail = $request->file('image');
//                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
//                Image::make($thumbnail)->resize(100, 100)->save(public_path('/uploads/' . $filename));
//                $user->image = $filename;
//                $user->save();

                UploadImage('uploads',$user,$request);
            }
            if ($request->roles) {
                // $user->attachRole('admin');
                $user->syncRoles($request->roles);
            }

            if (auth()->user()) {


                if (auth()->user()->hasRole('Vendor')) {

                    $vendor = [
                        "2"
                    ];
                    $user->syncRoles($vendor);
                }


            }

            if ($user) {

                flash(__('site.added_successfully'))->success();
                return redirect()->route('dashboard.users.index');

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


    public
    function edit(User $user)
    {
        if (auth()->user()->hasPermission('update_users')) {

            $roles = Role::all();


            return view('dashboard.users.edit', compact('user', 'roles'));
        } elseif(auth()->user()->hasPermission('update_profile')){
            if($user->id==auth()->user()->id){
                
                     $roles = Role::all();


            return view('dashboard.users.edit', compact('user', 'roles'));   
                
            }  else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
     
            
        }
        
        else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of user


    /*----------------------------------------------------
     || Name     : update data into database using users        |
     || Tested   : Done                                    |
       ||                                     |
        ||                                    |
           -----------------------------------------------------*/


    public
    function update(Request $request, User $user)
    {
        $request->validate([
            'email' => ['required', Rule::unique('users')->ignore($user->id),],
            'phone' => ['required', Rule::unique('users')->ignore($user->id),],
            'password' => 'nullable|confirmed',


        ]);
//        DB::beginTransaction();
//        try {
            $request_data = $request->except(['permissions']);
            //   $request_data['type'] =Role::wherein('id',$request->roles)->first()->name ?? '';
            if (!empty($request->password)) {
                $request_data['password'] = bcrypt($request->password);

            }else{
                $request_data = $request->except(['permissions','password']);
            }


            $user->update($request_data);
            if ($request->hasFile('image')) {
//                $thumbnail = $request->file('image');
//                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
//                Image::make($thumbnail)->resize(100, 100)->save(public_path('/uploads/' . $filename));
//                $user->image = $filename;
//                $user->save();

                UploadImage('uploads',$user,$request);
            }

            if (isset($request->roles)) $user->syncRoles($request->roles);
            if ($user) {
                flash(__('site.updated_successfully'))->success();
      return redirect()->route('dashboard.users.index');
//            session()->flash('success', __('site.updated_successfully'));
            } else {
                flash(__('site.update_faild'))->success();
                return redirect()->route('dashboard.users.index');

//            session()->flash('error', __('site.update_faild'));
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
    }//end of update

    /*----------------------------------------------------
 || Name     : delete data into database using users        |
 || Tested   : Done                                    |
 ||                                     |
 ||                                    |
   -----------------------------------------------------*/


    public
    function destroy(User $user)
    {
        if ($user->id == 1) {
            flash(__('site.Notdeleted_successfully'))->success();

//            session()->flash('success', __('site.Notdeleted_successfully'));
            return back();

        } else {

            $result = $user->delete();
            if ($result) {
                flash(__('site.deleted_successfully'))->success();

//                session()->flash('success', __('site.deleted_successfully'));
            } else {
                flash(__('site.delete_faild'))->success();

//                session()->flash('error', __('site.delete_faild'));
            }
            return back();
        }
    }//end of destroy


    /*----------------------------------------------------
  || Name     : active or block  data into database using roles        |
  || Tested   : Done                                    |
  ||                                     |
  ||                                    |
    -----------------------------------------------------*/
    public
    function block($user_id)
    {
        $info = User::find($user_id);
        $status = ($info->status == 0) ? 1 : 0;
        $info->status = $status;
        $info->save();
        flash(__('site.updated_successfully'))->success();

//        session()->flash('success', __('site.updated_successfully'));
        return back();

        //Revoke User With Status =0;
        if ($status == 0) {
            DB::table('oauth_access_tokens')
                ->where('user_id', $user_id)
                ->delete();
        }

    }//end of update


    public
    function logout()
    {

        Auth::logout();

        return back();
    }

}//end of controller
