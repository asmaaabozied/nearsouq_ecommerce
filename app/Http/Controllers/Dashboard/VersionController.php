<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\BannerDataTable;

use App\DataTables\VersionDataTable;
use App\Models\Versions;
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

class VersionController extends Controller
{


    /*----------------------------------------------------
    || Name     : show all Banners                     |
    || Tested   : Done                                    |
    || using  : datatables                                      |
    ||                                    |
    -----------------------------------------------------*/
    public function index(VersionDataTable $versionDataTable)
    {
        if (auth()->user()->hasPermission('read_versions')) {

            return $versionDataTable->render('dashboard.datatable', [
                'title' => trans('site.versions'),
                'model' => 'versions',
                'count' => $versionDataTable->count()
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

        if (auth()->user()->hasPermission('create_versions')) {


            return view('dashboard.versions.create');
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of create




   public
    function block($user_id)
    {
        $info = Versions::find($user_id);
        $status = ($info->status == 0) ? 1 : 0;
        $info->status = $status;
        $info->save();
        flash(__('site.updated_successfully'))->success();

//        session()->flash('success', __('site.updated_successfully'));
        return back();

   
      

    }//end of update

    /*----------------------------------------------------
    || Name     : store data into database banners          |
      || Tested   : Done                                    |
         ||                                     |
       ||                                    |
        -----------------------------------------------------*/

    public function store(Request $request)
    {
        $request->validate([
            'version_no' => 'required|string',
            'build_no' => 'required',

            'release_date' => 'required',
            'expiry_date' => 'required',

        ]);
//        DB::beginTransaction();
//        try {
            $request_data = $request->all();
            $version = Versions::create($request_data);

            if ($version) {
                flash(__('site.added_successfully'))->success();
                return redirect()->route('dashboard.versions.index');

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
        if (auth()->user()->hasPermission('update_versions')) {

            $version = Versions::find($id);
            return view('dashboard.versions.edit', compact('version'));
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
            'version_no' => 'required|string',
            'build_no' => 'required',

            'release_date' => 'required',
            'expiry_date' => 'required',

        ]);
//        DB::beginTransaction();
//        try {
            $version = Versions::find($id);

            $request_data = $request->all();

            $result = $version->update($request_data);

            if ($result) {
                flash(__('site.updated_successfully'))->success();
                return redirect()->route('dashboard.versions.index');

//            session()->flash('error', __('site.updated_successfully'));
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
      || Name     : delete data into database using banner        |
     || Tested   : Done                                    |
      ||                                     |
      ||                                    |
         -----------------------------------------------------*/

    public function destroy($id)
    {
        $version = Versions::find($id);
        $result = $version->delete();
        if ($result) {
            flash(__('site.deleted_successfully'))->success();

//            session()->flash('error', __('site.deleted_successfully'));
        }
        return back();
    }

}//end of controller
