<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ShopDatatables;

use App\DataTables\MallDatatables;
use App\Models\Mall;
use App\Models\category;
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

class MallController extends Controller
{


    public function index(MallDatatables $mallDatatables)
    {

        if (auth()->user()->hasPermission('read_malls')) {

            return $mallDatatables->render('dashboard.datatable', [
                'title' => trans('site.malls'),
                'model' => 'malls',
                'count' => $mallDatatables->count()
            ]);
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of index

    public function create()
    {
        if (auth()->user()->hasPermission('create_malls')) {

            $categories = category::all();
            return view('dashboard.malls.create', compact('categories'));

        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of create

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            // 'owner_name' => 'required|string',
            // 'owner_phone' => 'required|numeric',
            'desc_ar' => 'required|string',
            'desc_en' => 'required|string',
            // 'email' => 'required|email|string',
            // 'contact_number' => 'required|numeric',
            'mall_category_id' => 'required',
            'visible' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'address' => 'required|string'
        ]);
//        DB::beginTransaction();
//        try {
            $request_data = $request->all();
            $request_data['created_by'] = Auth()->user()->id;
            $mall = Mall::create($request_data);

            if ($request->hasFile('image')) {
//                $thumbnail = $request->file('image');
//                $destinationPath = 'uploads/shops/malls/';
//                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
//                //Image::make($thumbnail)->resize(100, 100)->save(public_path('/uploads/' . $filename));
//                $thumbnail->move($destinationPath, $filename);
//                $mall->image = $filename;
//                $mall->save();
                UploadImage('uploads/shops/malls',$mall,$request);

            }
            if ($mall) {

                flash(__('site.added_successfully'))->success();

//        session()->flash('success', __('site.added_successfully'));
                return redirect()->route('dashboard.malls.index');
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

    public function edit($id)
    {
        if (auth()->user()->hasPermission('update_malls')) {

            $categories = category::all();
            $mall = Mall::find($id);
            $mall->user_name = User::where('id', $mall->created_by)->first();
            return view('dashboard.malls.edit', compact('mall', 'categories'));
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of edit

    public function update(Request $request, Mall $mall)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            // 'owner_name' => 'required|string',
            // 'owner_phone' => 'required|numeric',
            'desc_ar' => 'required|string',
            'desc_en' => 'required|string',
            // 'email' => 'required|email|string',
            // 'contact_number' => 'required|numeric',
            'mall_category_id' => 'required',
            'visible' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'address' => 'required|string'
        ]);

        $request_data = $request->all();
//        DB::beginTransaction();
//        try {
            $result = $mall->update($request_data);
            if ($request->hasFile('image')) {
//                $thumbnail = $request->file('image');
//                $destinationPath = 'uploads/shops/malls/';
//                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
//                //Image::make($thumbnail)->resize(100, 100)->save(public_path('/uploads/' . $filename));
//                $thumbnail->move($destinationPath, $filename);
//                $mall->image = $filename;
//                $mall->save();
                UploadImage('uploads/shops/malls',$mall,$request);
            }
            if ($result) {
                flash(__('site.updated_successfully'))->success();
                return redirect()->route('dashboard.malls.index');

//            session()->flash('success', __('site.updated_successfully'));
            } else {
                flash(__('site.update_faild'))->success();

//            session()->flash('error', __('site.update_faild'));
            }
            return redirect()->route('dashboard.malls.index');

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

    public function destroy($id)
    {
        $mall = Mall::find($id);
        if (!$mall) {
            flash(__('site.delete_faild'))->success();
            return back();
        }
        $result = $mall->delete();
        if ($result) {
            flash(__('site.deleted_successfully'))->success();

//            session()->flash('success', __('site.deleted_successfully'));
        } else {
            flash(__('site.delete_faild'))->success();

//            session()->flash('error', __('site.delete_faild'));
        }
        return back();
    }

}//end of controller
