<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\BannerDataTable;
use App\DataTables\ShopDatatables;

use App\Models\Banner;
use Alert;
use App\Models\Shop;
use App\User;
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
//use Symfony\Component\HttpFoundation\Session\Flash; // rewrite this line

class BannerController extends Controller
{


    /*----------------------------------------------------
    || Name     : show all Banners                     |
    || Tested   : Done                                    |
    || using  : datatables                                      |
    ||                                    |
    -----------------------------------------------------*/
    public function index(BannerDataTable $bannerDatatables)
    {
        if (auth()->user()->hasPermission('read_banners')) {

//            print_r(session()->has('success'));die();

            return $bannerDatatables->render('dashboard.datatable', [
                'title' => trans('site.banners'),
                'model' => 'banners',
                'count' => $bannerDatatables->count()
            ]);

        } else {
//            Alert::warning('Warning', __('site.notaccesspermisssions'));

//            print_r(session()->has('success'));die();

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
        if (auth()->user()->hasPermission('create_banners')) {

            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {

                $shops = Shop::where('shop_id', Session::get('shop_id'))->get();

                $users = User::where('shop_id', Session::get('shop_id'))->get();

            } else {
                $shops = Shop::get();
                $users = User::get();


            }

            return view('dashboard.banners.create', compact('shops', 'users'));

        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of create


    /*----------------------------------------------------
    || Name     : store data into database banners          |
      || Tested   : Done                                    |
         ||                                     |
       ||                                    |
        -----------------------------------------------------*/

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',

        ]);
//        DB::beginTransaction();
//        try {
            $request_data = $request->all();
            $Banner = Banner::create($request_data);

//            if ($request->hasFile('image')) {
//                $thumbnail = $request->file('image');
//
//                $destinationPath = 'uploads/shops/banners/';
//                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
//                Image::make($thumbnail)->insert(public_path('/images/logo.png'), 'top-right', 20, 20)->resize(10, 10)->save(public_path('/uploads/shops/banners/' . $filename));
//
//
////                $thumbnail->save(public_path('/uploads/shops/banners'));
//                $thumbnail->move($destinationPath, $filename);
//
//
//                $Banner->image = $destinationPath . $filename;
//                $Banner->save();
//            }
            if ($request->hasFile('image')) {
               UploadImage('uploads/shops/banners',$Banner,$request);

            }


//
            if ($Banner) {
//            Alert::success('Success', __('site.added_successfully'));
//            Flash::success('The post was updated!');
                flash(__('site.added_successfully'))->success();
//        return redirect()->route('dashboard.banners.index')->with('success', __('site.added_successfully'));
                return redirect()->route('dashboard.banners.index');
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

    public function edit($id)
    {

        if (auth()->user()->hasPermission('update_banners')) {

            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {

                $shops = Shop::where('shop_id', Session::get('shop_id'))->get();

                $users = User::where('shop_id', Session::get('shop_id'))->get();

            } else {
                $shops = Shop::get();
                $users = User::get();


            }
            $banner = Banner::find($id);
            return view('dashboard.banners.edit', compact('banner', 'shops', 'users'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of edit


    /*----------------------------------------------------
     || Name     : update data into database using banner        |
       || Tested   : Done                                    |
            ||                                     |
                ||                                    |
        -----------------------------------------------------*/


    public function update(Request $request, $id)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',

        ]);
//        DB::beginTransaction();
//        try {
            $banner = Banner::find($id);

            $request_data = $request->all();

            $result = $banner->update($request_data);
//            if ($request->hasFile('image')) {
//                $thumbnail = $request->file('image');
//                $destinationPath = 'uploads/shops/banners/';
//                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
//
//                //Image::make($thumbnail)->resize(100, 100)->save(public_path('/uploads/' . $filename));
//                $thumbnail->move($destinationPath, $filename);
//                $banner->image = $destinationPath . $filename;
//                $banner->save();
//            }

        if ($request->hasFile('image')) {

            UploadImage('uploads/shops/banners',$banner,$request);

        }
            if ($result) {
                flash(__('site.updated_successfully'))->success();
                return redirect()->route('dashboard.banners.index');

            } else {

                session()->flash('error', __('site.update_faild'));
            }
            return redirect()->route('dashboard.banners.index');
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
      || Name     : delete data into database using banner        |
     || Tested   : Done                                    |
      ||                                     |
      ||                                    |
         -----------------------------------------------------*/

    public function destroy($id)
    {
        $banner = Banner::find($id);
        $result = $banner->delete();
        if ($result) {
            flash(__('site.deleted_successfully'))->success();

//            session()->flash('success', __('site.deleted_successfully'));
        } else {
            flash(__('site.delete_faild'))->success();

//            session()->flash('error', __('site.delete_faild'));
        }
        return back();
    }

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }

}//end of controller
