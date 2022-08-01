<?php

namespace App\Http\Controllers\Dashboard;




use Alert;

use App\DataTables\ReasonDataTable;
use App\Models\Reason;
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

class ReasonController extends Controller
{


    /*----------------------------------------------------
    || Name     : show all Banners                     |
    || Tested   : Done                                    |
    || using  : datatables                                      |
    ||                                    |
    -----------------------------------------------------*/
    public function index(ReasonDataTable $bannerDatatables)
    {
        if (auth()->user()->hasPermission('read_reasons')) {



            return $bannerDatatables->render('dashboard.datatable', [
                'title' => trans('site.reasons'),
                'model' => 'reasons',
                'count' => $bannerDatatables->count()
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
        if (auth()->user()->hasPermission('create_reasons')) {



            return view('dashboard.reasons.create');

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
            $reason = Reason::create($request_data);


            if ($reason) {

                flash(__('site.added_successfully'))->success();
                return redirect()->route('dashboard.reasons.index');


            }


    }//end of store


    /*----------------------------------------------------
    || Name     : redirect to edit pages          |
   || Tested   : Done                                    |
    ||                                     |
    ||                                    |
       -----------------------------------------------------*/

    public function edit($id)
    {

        if (auth()->user()->hasPermission('update_reasons')) {


            $reason = Reason::find($id);
            return view('dashboard.reasons.edit', compact('reason'));
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
            $reason = Reason::find($id);

            $request_data = $request->all();

            $result = $reason->update($request_data);



            if ($result) {
                flash(__('site.updated_successfully'))->success();
                return redirect()->route('dashboard.reasons.index');

            } else {

                session()->flash('error', __('site.update_faild'));
            }
            return redirect()->route('dashboard.reasons.index');

    }


    /*----------------------------------------------------
      || Name     : delete data into database using banner        |
     || Tested   : Done                                    |
      ||                                     |
      ||                                    |
         -----------------------------------------------------*/

    public function destroy($id)
    {
        $reason = Reason::find($id);
        $result = $reason->delete();
        if ($reason) {
            flash(__('site.deleted_successfully'))->success();

//            session()->flash('success', __('site.deleted_successfully'));
        } else {
            flash(__('site.delete_faild'))->success();

//            session()->flash('error', __('site.delete_faild'));
        }
        return back();
    }



}//end of controller
