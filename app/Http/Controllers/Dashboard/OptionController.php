<?php

namespace App\Http\Controllers\Dashboard;


use App\DataTables\OptionDatatables;
use App\Models\Option;
use App\Models\Variant;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Database\QueryException;
use Hash;
use Response;
use DB;
class OptionController extends Controller
{

    /*----------------------------------------------------
    || Name     : show all options                     |
      || Tested   : Done                                    |
    || using  : datatables                                      |
       ||                                    |
           -----------------------------------------------------*/
    public function index(OptionDatatables $optionDatatables)
    {
        if (auth()->user()->hasPermission('read_options')) {


            return $optionDatatables->render('dashboard.datatable', [
                'title' => trans('site.options'),
                'model' => 'options',
                'count' => $optionDatatables->count()
            ]);
        }
        session()->flash('success', __('site.notaccesspermisssions'));
        return redirect(url('/dashboard'));
    }//end of index


    /*----------------------------------------------------
   || Name     : open pages create                     |
   || Tested   : Done                                    |
   ||                                     |
    ||                                    |
    -----------------------------------------------------*/
    function str_random($length = 4)
    {
        return Str::random($length);
    }

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }

    public
    function create()
    {
        if (auth()->user()->hasPermission('create_options')) {

            return view('dashboard.options.create');
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }

    }//end of create


    /*----------------------------------------------------
  || Name     : store data into database Option          |
 || Tested   : Done                                    |
   ||                                     |
    ||                                    |
        -----------------------------------------------------*/

    public function show($id)
    {

        if (auth()->user()->hasPermission('read_options')) {

            $option = Option::find($id);

            $result = $option->delete();

            if ($result) {
                session()->flash('success', __('site.deleted_successfully'));
            }
            return back();
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }

    public
    function store(Request $request)
    {

        $shop_id = Session::get('shop_id') ?? '';

//        DB::beginTransaction();
//        try {
            $option = Option::create($request->except('_token', '_method', 'vname_ar', 'vname_en', 'extra_price', 'images') + ['shop_id' => $shop_id]);
            foreach ($request->vname_ar as $key => $value) {


                $variant = Variant::create([
                    'name_ar' => $request['vname_ar'][$key],
                    'name_en' => $request['vname_en'][$key],
                    'extra_price' => $request['extra_price'][$key],
                    'option_id' => $option->id


                ]);
                if ($request->file('images')) {

                    $imagess = $request->file('images');


                    // foreach ($imagess as $image) {


                    $destinationPath = 'uploads/shops/options/';
                    $extension = $request['images'][$key]->getClientOriginalExtension(); // getting image extension
                    $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                    $request['images'][$key]->move($destinationPath, $name); // uploading file to given
                    $variant->image = $name;

                    $variant->save();

                    // }

                }


            }


            if ($option) {
                flash(__('site.added_successfully'))->success();
                return redirect(route('dashboard.options.index'));

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
    }


    /*----------------------------------------------------
    || Name     : redirect to edit pages          |
    || Tested   : Done                                    |
    ||                                     |
   ||                                    |
     -----------------------------------------------------*/


    public
    function edit($id)
    {
        if (auth()->user()->hasPermission('update_options')) {

            $option = Option::find($id);


            return view('dashboard.options.edit', compact('option'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }

    /*----------------------------------------------------
   || Name     : update data into database using Option        |
   || Tested   : Done                                    |
     ||                                     |
      ||                                    |
         -----------------------------------------------------*/

    public function update($id, Request $request)
    {
//        DB::beginTransaction();
//        try {
            $option = Option::find($id);

            $result = $option->update($request->except('_token', '_method', 'vname_ar', 'vname_en', 'extra_price', 'images'));

            if (!empty($request->vname_ar)) {
                Variant::where('option_id', $option->id)->delete();

                foreach ($request->vname_ar as $key => $value) {


                    $variant = Variant::create([
                        'name_ar' => $request['vname_ar'][$key],
                        'name_en' => $request['vname_en'][$key],
                        'extra_price' => $request['extra_price'][$key],
                        'option_id' => $option->id


                    ]);

                    if ($request->file('images')) {

                        $imagess = $request->file('images');


                        // foreach ($imagess as $image) {


                        $destinationPath = 'uploads/shops/options/';
                        $extension = $request['images'][$key]->getClientOriginalExtension(); // getting image extension
                        $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                        $request['images'][$key]->move($destinationPath, $name); // uploading file to given
                        $variant->image = $name;

                        $variant->save();

                        // }

                    }
                }


            }


            if ($result) {
                flash(__('site.updated_successfully'))->success();
                return redirect(route('dashboard.options.index'));


//            session()->flash('success', __('site.updated_successfully'));
            } else {
                flash(__('site.update_faild'))->success();

//            session()->flash('error', __('site.update_faild'));
            }

            return redirect(route('dashboard.options.index'));

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
   || Name     : delete data into database using Option        |
   || Tested   : Done                                    |
   ||                                     |
   ||                                    |
     -----------------------------------------------------*/

    public
    function destroy($id)
    {
        $res = Variant::where('option_id', $id)->delete();

        $option = Option::find($id);

        $result = $option->delete();

        if ($result) {
            flash(__('site.deleted_successfully'))->success();

//            session()->flash('success', __('site.deleted_successfully'));
        } else {
            flash(__('site.delete_faild'))->success();

//            session()->flash('error', __('site.delete_faild'));
        }
        return back();

    }//end of destroy


}//end of controller
