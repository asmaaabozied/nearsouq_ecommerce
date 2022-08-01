<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\SettingDatatables;
use App\DataTables\ShopDatatables;

use App\Models\Setting;

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

class SettingsController extends Controller
{
    public function index(SettingDatatables $settingDatatables)
    {
        if (auth()->user()->hasPermission('read_settings')) {

            return $settingDatatables->render('dashboard.datatable', [
                'title' => trans('site.settings'),
                'model' => 'settings',
                'count' => $settingDatatables->count()
            ]);
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of index

    public function create()
    {
        if (auth()->user()->hasPermission('create_settings')) {

            return view('dashboard.settings.create');
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of create

    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'param' => 'required|string',
            'type' => 'required|string',
        ]);
//        DB::beginTransaction();
//        try {
            $request_data['param'] = $request['param'];
            $request_data['type'] = $request['type'];
            $request_data['status'] = $request['status'];

            if (isset($request['valueText']) && $request['valueText'] != NULL) {
                $request_data['value'] = $request['valueText'];
            } else {
                if ($request->hasFile('valueImage')) {
                    $thumbnail = $request->file('valueImage');
                    $destinationPath = 'uploads/splash_screen';
                    $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
                    //Image::make($thumbnail)->resize(100, 100)->save(public_path('/uploads/' . $filename));
                    $thumbnail->move($destinationPath, $filename);
                    $request_data['value'] = $filename;
                }
            }
            $setting = Setting::create($request_data);
            if ($setting) {

                flash(__('site.added_successfully'))->success();

//        session()->flash('success', __('site.added_successfully'));
                return redirect()->route('dashboard.settings.index');

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
        if (auth()->user()->hasPermission('update_settings')) {

            $setting = Setting::find($id);
            return view('dashboard.settings.edit', compact('setting'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of edit

    public function update(Request $request, $id)
    {
        //dd($request);
//        DB::beginTransaction();
//        try {
            $request_data = [];

            if ($request->hasFile('value')) {
                $thumbnail = $request->file('value');
                $destinationPath = 'uploads/settings';
                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
                //Image::make($thumbnail)->resize(100, 100)->save(public_path('/uploads/' . $filename));
                $thumbnail->move($destinationPath, $filename);
                $request_data['value'] = $filename;
            } else {
                $request_data['value'] = $request['value'];
            }
            $request_data['status'] = $request['status'];
            $setting = Setting::find($id);
            $result = $setting->update($request_data);

            if ($result) {

                flash(__('site.updated_successfully'))->success();
                return redirect()->route('dashboard.settings.index');

//            session()->flash('success', __('site.updated_successfully'));
            } else {

                flash(__('site.update_faild'))->success();

//            session()->flash('error', __('site.update_faild'));
            }
            return redirect()->route('dashboard.settings.index');

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
        $setting = Setting::find($id);
        $result = $setting->delete();
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
