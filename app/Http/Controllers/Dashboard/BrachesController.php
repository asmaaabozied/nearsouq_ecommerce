<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ShopSetting;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Mall;
use App\Models\Shop;
use App\Models\Page;
use App\User;
use Illuminate\Support\Facades\Session;
use Response;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
class BrachesController extends Controller
{

    public function saveshopuser(Request $request)
    {

        Session::put('shop_id', $request->shop_id);

        return redirect('/dashboard');


    }


    /*----------------------------------------------------
    || Name     : open pages shop register         |
    || Tested   : Done                                    |
    ||                                     |
    ||                                    |
   -----------------------------------------------------*/
    public function Register()
    {

   if (auth()->user()) {

        return view('dashboard.authregister');
        
        }else{
                  return view('dashboard.register');
  
        }

    }

    /*----------------------------------------------------
    || Name     : get all brances         |
    || Tested   : Done                                    |
    ||                                     |
    ||                                    |
       -----------------------------------------------------*/

    public function brances()
    {


        $shops = Auth::user()->shops->first();


        return view('dashboard.brances', compact('shops'));

    }

    /*----------------------------------------------------
        || Name     : store data into database brances          |
        || Tested   : Done                                    |
        ||                                     |
        ||                                    |
           -----------------------------------------------------*/

    public function addbracnch(Request $request)
    {

        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            //   'g-recaptcha-response'=>'required',
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
        $user_id = auth()->user()->id ?? '';
        $commission = $request->commission ?? 15;

        $parent_id = Session::get('shop_id') ?? $request->parent_id;

        $request->merge(['commission' => $commission, 'desc_ar' => $request->input('desc_ar')]);;


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
        $shop = Shop::create($request->except('g-recaptcha-response','shop_id', 'password', 'image', 're_password', 'email', 'terms', 'telephone', 'addressType', 'commerical_img', 'vat_img') + ['phone' => $request->input('telephone'), 'employe_id' => $user_id, 'owner_id' => $user->id, 'vat' => 15, 'parent_id' => $parent_id]);
        
       
                if (auth()->user()->hasRole('Super Admin')) {
                      ShopSetting::create(['shop_id' => $shop->id,'payment'=>'prompt']);
                }else{
                    
                      ShopSetting::create(['shop_id' => $shop->id,'payment'=>'prompt']);
                }
        
        $user->update(['shop_id' => $shop->id]);


        if ($request->hasFile('vat_img')) {
            $destinationPath = 'uploads/shops/profiles/';
            $image = $request->file('vat_img');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given
            $shop->vat_img = $name;
            $shop->save();
        }

        if ($request->hasFile('image')) {
//            $image = $request->file('image');
//            $destinationPath = 'uploads/shops/profiles/';
//            $extension = $image->getClientOriginalExtension(); // getting image extension
//            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
//            $image->move($destinationPath, $name); // uploading file to given
//            $shop->image = $name;
//            $shop->save();

            UploadImage('uploads/shops/profiles',$shop,$request);
        }

        if ($request->hasFile('commerical_img')) {
            $image = $request->file('commerical_img');
            $destinationPath = 'uploads/shops/profiles/';
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given
            $shop->commerical_img = $name;
            $shop->save();
        }


        $shop_check = Shop::find($request->parent_id);


        // $data = [
        //     ['user_id' => $user->id, 'shop_id' => $shop->id],
        //     ['user_id' => $user_id, 'shop_id' => $shop->id]];

        $data = [
            ['user_id' => $user->id, 'shop_id' => $shop->id],
            ['user_id' => $shop_check->owner_id, 'shop_id' => $shop->id]];

        Merchant::insert($data);

        // print_r($user);print_r($shop);die();
        if ($shop != NULL) {
            if (auth()->user()) {

                $shop->update(['published' => TRUE, 'type' => 'branch']);

                flash(__('site.datasuccess'))->success();

//                session()->flash('success', __('site.datasuccess'));

                return back();
//                return redirect(url('/dashboard/register'));

            } else {
                return redirect('/login')->with(['success' => __('site.datasuccess')]);


            }


        }
//            DB::commit();
//        }
//        catch (\Exception $e) {
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
     || Name     : update data into database brances&&shops          |
     || Tested   : Done                                    |
     ||                                     |
     ||                                    |
        -----------------------------------------------------*/

    public function editshop(Request $request, $id)

    {
        $user_id = Auth::id() ?? '';
        $user = auth()->user();

        // updated shops->user->as user importants

//        if (auth()->user()) {
//            $user->update([
//
//                'phone' => $request->input('telephone'),
//
//                'address' => $request->input('address'),
//                'latitude' => $request->input('latitude'),
//                'longitude' => $request->input('longitude'),
//
//
//            ]);
//        }


        //------------changed by Abdalhaleem -----------------------------------

//        DB::beginTransaction();
//        try {
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

            $shop = Shop::find($id);

            $user = User::find($shop->owner_id);


            $user->update([

                'phone' => $request->input('mobilephone'),

                'address' => $request->input('address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),


            ]);
            $user->save();
            $published = ($request->input('published') == 1) ? "TRUE" : "FALSE";
            $data = $shop->update($request->except('vat_img', 'commerical_img', 'image', 'password', 're_password', 'email', 'terms', 'telephone', 'addressType', 'commerical_file', 'tax_file') + ['phone' => $request->input('telephone'), 'active' => $request->input('published'), 'published' => $published, 'employe_id' => $user_id, 'vat' => 15]);


            //------------changed by Abdalhaleem -----------------------------------


            if ($request->hasFile('vat_img')) {
                $image = $request->file('vat_img');
                $destinationPath = 'uploads/shops/profiles/';
                $extension = $image->getClientOriginalExtension(); // getting image extension
                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                $image->move($destinationPath, $name); // uploading file to given
                $shop->vat_img = $name;
                $shop->save();
            }

            if ($request->hasFile('commerical_img')) {
                $image = $request->file('commerical_img');
                $destinationPath = 'uploads/shops/profiles/';
                $extension = $image->getClientOriginalExtension(); // getting image extension
                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                $image->move($destinationPath, $name); // uploading file to given
                $shop->commerical_img = $name;
                $shop->save();
            }

            if ($request->hasFile('image')) {
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
//        if (!empty($user) && !empty($shop)) {
//
//            session()->flash('success', __('site.datasuccess'));
//
//
//        }else{
//            session()->flash('success', __('site.datasuccess'));
//
//
//        }

//        if (Auth::user()) {
//
//            if($shop != NULL){
//                session()->flash('success', __('site.datasuccess'));
//
//                return redirect('/login');
//            }
//
//        } else {
//            return back();
//        }
            if ($data && auth()->user()) {

                flash(__('site.updated_successfully'))->success();
//            session()->flash('success', __('site.datasuccess'));

                return redirect(route('dashboard.shops.index'));

            } else {
                if ($data) {
                    flash(__('site.updated_successfully'))->success();

//                session()->flash('success', __('site.updated_successfully'));
//                session()->flash('success', __('site.datasuccess'));

                    return redirect('/login');

                } else {
                    session()->flash('success', __('site.error'));

                    return back();

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
      || Name     : store data into database shops          |
      || Tested   : Done                                    |
      ||                                     |
      ||                                    |
         -----------------------------------------------------*/



    public function checkCommericalNumber(Request $request)
    {

        $url = "https://api.wathq.sa/v5/commercialregistration/info/" . $request->commerical_number; //1010486258
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "accept: application/json",
            "apiKey: HyauYc5OgUnHYzY52agYEP1a43vpqAth"
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($resp, true);
// return $result;
// var_dump($result);
//=====added by abdalhaleem===========
        $arr = json_decode($resp);
        // print_r($arr->crName."\n");
        // print_r($arr->expiryDate."\n");
        // print_r($arr->status->id."\n");
        // print_r($arr->activities->description."\n");
        // print_r($arr->location->name."\n");
        if (isset($arr->status->id)) {
            $data = array("status" => $arr->status->id,
                "crName" => $arr->crName,
                "expiryDate" => $arr->expiryDate,
                "description" => $arr->activities->description,
                "location" => $arr->location->name);
        } else {
            $data = array("status" => "error");
        }
        echo json_encode($data);
        // die();
//====================================

        // $response = [
        //     'error'   => 1,
        // ];
        // return response()->json($response, 200);
    }

    public function ShopRegister()
    {

        return view('dashboard.shopregister');

    }

    public function termsPage()
    {
        $page = Page::where('slug', 'termcondition')->first();
        return view('dashboard.terms', compact('page'));
    }


}//end of controller

