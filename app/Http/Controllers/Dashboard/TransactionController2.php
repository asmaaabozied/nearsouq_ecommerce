<?php

namespace App\Http\Controllers\Dashboard;


use App\DataTables\OrderDatatables;
use App\DataTables\transactionDatatables;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\TradersDue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Lang;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Response;
use DB;

class TransactionController2 extends Controller
{


    /*----------------------------------------------------
|| Name     : show all Transaction                     |
|| Tested   : Done                                    |
|| using  : datatables                                      |
 ||                                    |
     -----------------------------------------------------*/

    public function index(transactionDatatables $orderDatatables)
    {
        if (auth()->user()->hasPermission('read_orders')) {

            return $orderDatatables->render('dashboard.orders', [
                'title' => trans('site.Transaction'),
                'model' => 'Transaction',
                'count' => $orderDatatables->count()
            ]);
        } else
            session()->flash('success', __('site.notaccesspermisssions'));
        return redirect(url('/dashboard'));

    }//end of index

    /*----------------------------------------------------
 || Name     : search all data inside orders                     |
 || Tested   : Done                                    |
   ||                                     |
   ||                                    |
    -----------------------------------------------------*/

    public function TransactionFile(Request $request)
    {


        $request->validate([
            'order_id' => 'required',
            'file_name' => 'required',
        ]);
//        DB::beginTransaction();
//        try {
            $request_data = $request->all();

            foreach ($request->order_id as $key => $order) {


                $orderdetail = OrderDetail::where('order_id', $order)->first();


                $orderdetail->update(['active' => 'PAID']);

                $trade = TradersDue::create(
                    [
                        'order_details_id' => $orderdetail->id,
                        'employee_id' => Auth::id()

                    ]);

                if ($request->hasFile('file_name')) {
                    $image = $request->file('file_name');
                    $destinationPath = 'uploads/shops/profiles/';
                    $extension = $image->getClientOriginalExtension(); // getting image extension
                    $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                    $image->move($destinationPath, $name); // uploading file to given
                    $trade->file_name = $name;
                    $trade->save();
                }


            }

            if ($orderdetail) {
                flash(__('site.added_successfully'))->success();
                return back();
//        session()->flash('success', __('site.added_successfully'));

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

    public
    function create()
    {


    }//end of create


    /*----------------------------------------------------
|| Name     : delete data from database orders                    |
|| Tested   : Done                                    |
||                                     |
||                                    |
-----------------------------------------------------*/


    public
    function destroy($id)
    {

        $page = Order::find($id);


        $result = $page->delete();

        if ($result) {
            flash(__('site.deleted_successfully'))->success();

//        session()->flash('success', __('site.deleted_successfully'));
        } else {
            flash(__('site.delete_faild'))->success();

//            session()->flash('error', __('site.delete_faild'));
        }

        return back();

    }//end of destroy


}//end of controller
