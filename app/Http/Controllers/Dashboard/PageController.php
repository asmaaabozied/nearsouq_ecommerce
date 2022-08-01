<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\PageDatatable;
use App\DataTables\UserDatatables;


use App\Models\Page;
use Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Response;
use DB;
use Illuminate\Database\QueryException;


class PageController extends Controller
{
    /*----------------------------------------------------
    || Name     : show all pages                     |
    || Tested   : Done                                    |
    || using  : datatables                                      |
     ||                                    |
         -----------------------------------------------------*/

    public function index(PageDatatable $pageDatatable)
    {
        if (auth()->user()->hasPermission('read_pages')) {


            return $pageDatatable->render('dashboard.datatable', [
                'title' => trans('site.pages'),
                'model' => 'pages',
                'count' => $pageDatatable->count()
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

    public
    function create()
    {
        if (auth()->user()->hasPermission('create_pages')) {


            return view('dashboard.pages.create');
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of create


    /*----------------------------------------------------
      || Name     : store data into database Page          |
      || Tested   : Done                                    |
       ||                                     |
        ||                                    |
            -----------------------------------------------------*/

    public
    function store(Request $request)
    {

        $request->validate([


                'slug' => 'required',
                'name_ar' => 'required',
                'name_en' => 'required',
                'description_ar' => 'required',
                'description_en' => 'required',
            ]
        );

//
//        DB::beginTransaction();
//        try {
            $request_data = $request->all();


            $page = Page::create($request_data);


            if ($page) {
                flash(__('site.added_successfully'))->success();
                return redirect()->route('dashboard.pages.index');

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
    function edit($id)
    {
        if (auth()->user()->hasPermission('update_pages')) {

            $page = Page::find($id);


            return view('dashboard.pages.edit', compact('page'));
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of page


    /*----------------------------------------------------
     || Name     : show all details using page_id        |
     || Tested   : Done                                    |
       ||                                     |
        ||                                    |
           -----------------------------------------------------*/

    public
    function show($id)
    {
        if (auth()->user()->hasPermission('read_pages')) {

            $page = Page::find($id);


            return view('dashboard.pages.show', compact('page'));
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of page


    /*----------------------------------------------------
   || Name     : update data into database using Page        |
   || Tested   : Done                                    |
     ||                                     |
      ||                                    |
         -----------------------------------------------------*/
    public
    function update(Request $request, $id)
    {
        $page = Page::find($id);
        $request->validate([


                'slug' => 'required',
                'name_ar' => 'required',
                'name_en' => 'required',
                'description_ar' => 'required',
                'description_en' => 'required',
            ]
        );

//
//        DB::beginTransaction();
//        try {

            $request_data = $request->all();


            $result = $page->update($request_data);


            if ($result) {
                flash(__('site.updated_successfully'))->success();
                return back();
//            session()->flash('success', __('site.updated_successfully'));
            } else {
                flash(__('site.update_faild'))->success();

//            session()->flash('error', __('site.update_faild'));
            }
            return back();
            // return redirect()->route('dashboard.pages.index');
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
      || Name     : delete data into database using roles        |
   | | Tested   : Done                                    |
  ||                                     |
   ||                                    |
    -----------------------------------------------------*/

    public
    function destroy($id)
    {

        $page = Page::find($id);


        if ($page->id == 1 || $page->id == 2 || $page->id == 3) {
//            session()->flash('success', __('site.Notdeleted_successfully'));
            flash(__('site.Notdeleted_successfully'))->success();


        } else {

            $page->delete();

            flash(__('site.deleted_successfully'))->success();
        }

        return back();

    }//end of destroy


}//end of controller
