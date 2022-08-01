<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ShopDatatables;

use App\DataTables\CategoryDatatables;
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
use Hash;
use DB;
class CategoryController extends Controller
{

    /*----------------------------------------------------
|| Name     : show all categories                     |
 || Tested   : Done                                    |
   || using  : datatables                                      |
   ||                                    |
        -----------------------------------------------------*/
    public function index(CategoryDatatables $categoryDatatables)
    {
        if (auth()->user()->hasPermission('read_categories')) {
            return $categoryDatatables->render('dashboard.datatable', [
                'title' => trans('site.category'),
                'model' => 'categories',
                'count' => $categoryDatatables->count()
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
        if (auth()->user()->hasPermission('create_categories')) {
            $categories = category::where('parent', NULL)->get();
            return view('dashboard.categories.create', compact('categories'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of create


    /*----------------------------------------------------
  || Name     : store data into database category          |
 || Tested   : Done                                    |
       ||                                     |
    ||                                    |
   -----------------------------------------------------*/

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'status' => 'required',
        ]);
//        DB::beginTransaction();
//        try {
            $request_data = $request->all();
            $request_data['created_by'] = Auth()->user()->id;
            $category = category::create($request_data);

            if (isset($request->parent)) {
                $category->parent = $request->parent;
                $category->save();
            }
            if ($request->hasFile('image')) {
//                $thumbnail = $request->file('image');
//                $destinationPath = 'images/categories/';
//                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
//                $thumbnail->move($destinationPath, $filename);
//                $category->image = $filename;
//                $category->save();
                UploadImage('uploads/shops/categories',$category,$request);
            }
            if($category){
                flash(__('site.added_successfully'))->success();

//        session()->flash('success', __('site.added_successfully'));
                return redirect()->route('dashboard.categories.index');
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
        
        if (auth()->user()->hasPermission('update_categories')) {

            $categories = category::all();
            $category = category::find($id);
            $category->user_name = User::where('id', $category->created_by)->first();
            
            $image=$category->image_path;
       
            return view('dashboard.categories.edit', compact('category','image', 'categories'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of edit


    /*----------------------------------------------------
    || Name     : update data into database using category        |
      || Tested   : Done                                    |
     ||                                     |
         ||                                    |
             -----------------------------------------------------*/

    public function update(Request $request, category $category)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'status' => 'required',
        ]);
//        DB::beginTransaction();
//        try {
            $request_data = $request->all();

            $result = $category->update($request_data);
            if ($request->hasFile('image')) {
//                $thumbnail = $request->file('image');
//                $destinationPath = 'images/categories/';
//                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
//                $thumbnail->move($destinationPath, $filename);
//                $category->image = $filename;
//                $category->save();
                UploadImage('uploads/shops/categories',$category,$request);
            }
            if ($result) {
                flash(__('site.updated_successfully'))->success();

//            session()->flash('success', __('site.updated_successfully'));
            } else {
                flash(__('site.update_faild'))->success();

//            session()->flash('error', __('site.update_faild'));
            }
            return redirect()->route('dashboard.categories.index');

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
        $category = category::find($id);
        $result = $category->delete();
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
