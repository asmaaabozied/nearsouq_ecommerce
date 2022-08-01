<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ShopDatatables;

use App\DataTables\shopsDatatables;
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


use App\Models\ShopSetting;




use Illuminate\Support\Facades\DB;
use App\Models\Mall;

use App\Models\Page;


use Response;
use Carbon\Carbon;


class ShopController extends Controller
{
    /*----------------------------------------------------
     || Name     : update shopid into sesssions        |
     || Tested   : Done                                    |
     ||                                     |
     ||                                    |
        -----------------------------------------------------*/

    public function updateshopsession($id)
    {

        Session()->put('shop_id', $id);
        return back();


    }

    // public function CheckedUserShop()
    // {


    //     $users = User::get();


    //     foreach ($users as $user) {

    //         $shop = Shop::where('name_ar', $user->name)->first();

    //         if ($shop) {
    //             $user->update(['shop_id' => $shop->id]);
    //             $shop->update(['owner_id' => $user->id]);

    //             Merchant::create(['shop_id' => $shop->id, 'user_id' => $user->id]);
    //             $vendor = [
    //                 "2"
    //             ];
    //             $user->syncRoles($vendor);

    //         }
    //     }
    //     if ($shop) {

    //         session()->flash('success', __('site.added_successfully'));
    //     }
    //     return redirect('/dashboard');

    // }

    public function brances(shopsDatatables $datatables)
    {
        return $datatables->render('dashboard.datatable', [
            'title' => trans('site.brances'),
            'model' => 'brances',
            'count' => $datatables->count()
        ]);

    }


    /*----------------------------------------------------
|| Name     : show all shops                     |
  || Tested   : Done                                    |
|| using  : datatables                                      |
   ||                                    |
       -----------------------------------------------------*/

    public function index(ShopDatatables $shopDatatables)
    {
        if (auth()->user()->hasPermission('read_shops')) {

            //  return auth()->user()->shops()->where('parent_id', null)->get();
            return $shopDatatables->render('dashboard.datatable', [
                'title' => trans('site.shop'),
                'model' => 'shops',
                'count' => $shopDatatables->count()
            ]);
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of index




    public function SaveShop(Request $request)

    {
        // return $request;

        $this->validate($request, [
            'name_ar' => 'required',
            // 'g-recaptcha-response'=>'required',
            'name_en' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'addressType' => 'required'
        ]);
//        DB::beginTransaction();
//        try {
            $email = $request->input('email');
            $typeReg = $request->input('typeReg');
            //------------changed by Abdalhaleem -----------------------------------


            if ($request->input('addressType') == 'inside_mall') {
                if ($request->mall_id) {
                    $mall = Mall::find($request->mall_id);
                    $request->merge(['latitude' => $mall->latitude, 'longitude' => $mall->longitude]);
                }
            } else
                $request->merge(['latitude' => $request->input('latitude'), 'longitude' => $request->input('longitude')]);
            //----------------------------------------------------------------------

            $commission = $request->commission ?? 15;

            $request->merge(['commission' => $commission, 'desc_ar' => $request->input('desc_ar')]);
            $user_id = auth()->user()->id ?? '';
            $user = User::create([
                'name' => $request->input('name_ar'),
                'password' => bcrypt($request->input('password')),
                'phone' => $request->input('telephone'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude')
             

            ]);
  if (auth()->user()) {


            if (auth()->user()->hasRole('Vendor')) {

                $vendor = [
                    "2"
                ];
            } elseif(auth()->user()->hasRole('Merchant')) {
                $vendor = [
                    "3"
                ];

            }elseif(auth()->user()->hasRole('SuperAdmin')){
                
                   $vendor = [
                    "1"
                ];
                
            }

            $user->syncRoles($vendor);

        }


            $shop = Shop::create($request->except('g-recaptcha-response','password', 'image', 're_password', 'email', 'terms', 'telephone', 'addressType', 'commerical_img', 'vat_img') + ['phone' => $request->input('telephone'), 'employe_id' => $user_id, 'owner_id' => $user->id]);
            $user->update(['shop_id' => $shop->id]);
            
                // if (auth()->user()->hasRole('Super Admin')) {
                //       ShopSetting::create(['shop_id' => $shop->id,'payment'=>'prompt','created_by'=>'admin']);
                // }else{
                    
                //       ShopSetting::create(['shop_id' => $shop->id,'payment'=>'prompt','created_by'=>'vendor']);
                // }
            
                       ShopSetting::create(['shop_id' => $shop->id,'payment'=>'prompt']);


            // $data = [
            //     ['user_id' => $user->id, 'shop_id' => $shop->id],
            //     ['user_id' => $user_id, 'shop_id' => $shop->id]
            //     ];

            if ($user_id === '') {
                $data = [
                    ['user_id' => $user->id, 'shop_id' => $shop->id]
                ];
                Merchant::insert($data);
            } else {
                $data = [
                    ['user_id' => $user->id, 'shop_id' => $shop->id],
                    ['user_id' => $user_id, 'shop_id' => $shop->id]
                ];
                Merchant::insert($data);
            }


            // Merchant::insert($data);


         if ($request->hasFile('vat_img')) {
              $image=$request->file('vat_img');
                $image = $request->file('vat_img');
                $destinationPath = 'uploads/shops/profiles/';
                $extension = $image->getClientOriginalExtension(); // getting image extension
                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                $image->move($destinationPath, $name); // uploading file to given
                $shop->vat_img = $name;
                $shop->save();
    //             $path='uploads/shops/profiles';
    //              $image = $request->file('vat_img');
    // $filenames = $image->getClientOriginalName();
    // $mytime = Carbon::now();
    // //   $filename = $mytime->toDateTimeString()."_".$filenames->hashName();

    //  $filename = $mytime->toDateTimeString()."_".md5($filenames);
    // //Fullsize
    // $image->move(base_path() . '/'.$path.'/', $filename);

    // $image_resize = Image::make(base_path() . '/'.$path.'/' . $filename);
    // // $image_resize->resize(1080, 1080);
    // // $image_resize->insert(base_path('/images/logo.png'), 'bottom-right', 2, 2)->save(base_path($path.'/' . $filename));
    // $shop->image = $filename;
    // $shop->save();
                

                // UploadImage('uploads/shops/profiles',$shop,$request);
            }

            if ($request->hasFile('commerical_img')) {
                $image=$request->file('commerical_img');
                $image = $request->file('commerical_img');
                $destinationPath = 'uploads/shops/profiles/';
                $extension = $image->getClientOriginalExtension(); // getting image extension
                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                $image->move($destinationPath, $name); // uploading file to given
                $shop->commerical_img = $name;
                $shop->save();
                // UploadImage('uploads/shops/profiles',$shop,$request);
                
    //             $path='uploads/shops/profiles';
                             
    //              $image = $request->file('commerical_img');
    // $filenames = $image->getClientOriginalName();
    // $mytime =Carbon::now();
    // //   $filename = $mytime->toDateTimeString()."_".$filenames->hashName();

    //  $filename = $mytime->toDateTimeString()."_".md5($filenames);
    // //Fullsize
    // $image->move(base_path() . '/'.$path.'/', $filename);

    // $image_resize = Image::make(base_path() . '/'.$path.'/' . $filename);
    // // $image_resize->resize(1080, 1080);
    // // $image_resize->insert(base_path('/images/logo.png'), 'bottom-right', 2, 2)->save(base_path($path.'/' . $filename));
    // $shop->image = $filename;
    // $shop->save();

//
            }
            if ($request->hasFile('image')) {
                $image=$request->file('image');
//                $image = $request->file('image');
//                $destinationPath = 'uploads/shops/profiles/';
//                $extension = $image->getClientOriginalExtension(); // getting image extension
//                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
//                $image->move($destinationPath, $name); // uploading file to given
//                $shop->image = $name;
//                $shop->save();

                UploadImage('uploads/shops/profiles',$shop,$request);

            }
            // print_r($user);print_r($shop);die();
            if ($shop != NULL) {


                if (auth()->user()) {
// print_r("step 1");die();
                    $shop->update(['published' => TRUE, 'type' => 'main']);

                    // session()->put('success', __('site.datasuccess'));
                    // flash(__('site.datasuccess'))->success();

                return redirect(url('/dashboard/register'))->with(['success' => __('site.datasuccess')]);
                    // return redirect(url('/dashboard/register'));

                } else {
                    //   session()->put('success', __('site.datasuccess'));


                    return redirect('/login')->with(['success' => __('site.datasuccess')]);

                }


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

    }





    /*----------------------------------------------------
|| Name     : get brances                    |
|| Tested   : Done                                    |
||                                     |
||                                    |
   -----------------------------------------------------*/

    public function Getbrances($id)
    {
        if (auth()->user()->hasPermission('read_shops')) {

            $shop = Shop::find($id);
            $parents = $shop->ShopParent;
            
            $user=User::find($shop->owner_id);


            return view('dashboard.showbrances', compact('parents', 'shop','user'));

        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }


    public function ShowShopsAuth()
    {
        if (auth()->user()->hasPermission('read_shops')) {

            return view('dashboard.shopuser');
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
        if (auth()->user()->hasPermission('create_shops')) {

        return view('dashboard.authregister');
        
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }

    }//end of create

    /*----------------------------------------------------
 || Name     : open pages edit shop                     |
 || Tested   : Done                                    |
   ||                                     |
   ||                                    |
    -----------------------------------------------------*/


    public
    function edit($id)
    {
            return redirect(url('dashboard/show-shop/'.$id));

        // if (auth()->user()->hasPermission('update_shops')) {

        //     $shop = Shop::find($id);

        //     return view('dashboard.editshop', compact('shop'));
        // } else {
        //     session()->flash('success', __('site.notaccesspermisssions'));
        //     return redirect(url('/dashboard'));

        // }


    }//end of create


    /*----------------------------------------------------
   || Name     : delete data into database using shops&&brances        |
   || Tested   : Done                                    |
   ||                                     |
   ||                                    |
     -----------------------------------------------------*/
    public function destroy($id)
    {
        $shop = Shop::find($id);


        $result = $shop->delete();
        if ($result) {
            flash(__('site.deleted_successfully'))->success();

//            session()->flash('success', __('site.deleted_successfully'));
        } else {
            flash(__('site.delete_faild'))->success();

//            session()->flash('error', __('site.delete_faild'));
        }
        return back();
    }

    public function block($id)
    {
        $info = Shop::find($id);
        $status = ($info->active == 0) ? 1 : 0;
        $published = ($info->published == "TRUE") ? "FALSE" : "TRUE";

        $info->active = $status;
        $info->published = $published;

        $info->save();
        flash(__('site.updated_successfully'))->success();

//        session()->flash('success', __('site.updated_successfully'));
        return back();
    }

}//end of controller
