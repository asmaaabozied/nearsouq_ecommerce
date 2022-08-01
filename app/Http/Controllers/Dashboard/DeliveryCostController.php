<?php

namespace App\Http\Controllers\Dashboard;


use App\DataTables\DeliveryCostDataTable;
use App\Models\DeliveryCost;
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
use function GuzzleHttp\Promise\all;

class DeliveryCostController extends Controller
{

    /*----------------------------------------------------
      || Name     : show all users                     |
      || Tested   : Done                                    |
      || using  : datatables                                      |
       ||                                    |
           -----------------------------------------------------*/


    public function index(DeliveryCostDataTable $deliveryCostDataTable)
    {
        if (auth()->user()->hasPermission('read_users')) {


            return $deliveryCostDataTable->render('dashboard.datatable', [
                'title' => trans('site.deliverycost'),
                'model' => 'deliverycost',
                'count' => $deliveryCostDataTable->count()
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

            $delivery = DeliveryCost::find($id);


            return view('dashboard.deliverycost.show', compact('delivery'));
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

            return view('dashboard.deliverycost.create');
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of create


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

                'min_distance' => 'required',
                'max_distance' => 'required',

                'price' => 'required',

            ]
        );

//
//        DB::beginTransaction();
//        try {
        $request_data = $request->all();


        $delivery = DeliveryCost::create($request_data);


        if ($delivery) {

            flash(__('site.added_successfully'))->success();
            return redirect()->route('dashboard.deliverycost.index');


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
    function edit($id)
    {
        if (auth()->user()->hasPermission('update_users')) {

            $delivery = DeliveryCost::find($id);


            return view('dashboard.deliverycost.edit', compact('delivery'));
        } else {

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
    function update(Request $request, $id)
    {
        $request->validate([

                'min_distance' => 'required',
                'max_distance' => 'required',

                'price' => 'required',

            ]
        );
//        DB::beginTransaction();
//        try {
        $request_data = $request->all();

        $delivery = DeliveryCost::find($id);

        $delivery->update($request_data);


        if ($delivery) {
            flash(__('site.updated_successfully'))->success();
            return redirect()->route('dashboard.deliverycost.index');
        } else {
            flash(__('site.update_faild'))->success();
            return redirect()->route('dashboard.deliverycost.index');

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
    function destroy($id)
    {


        $delivery = DeliveryCost::find($id);
        $result = $delivery->delete();
        if ($result) {
            flash(__('site.deleted_successfully'))->success();


            return back();
        }
    }//end of destroy


}//end of controller
