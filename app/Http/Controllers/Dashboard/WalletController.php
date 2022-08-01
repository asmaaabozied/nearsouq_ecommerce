<?php

namespace App\Http\Controllers\Dashboard;


use App\DataTables\WalletDataTable;
use App\Models\Shop;
use App\Models\Wallet;
use App\Models\Walletlog;
use App\User;
use Illuminate\Support\Facades\Session;
use Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Database\QueryException;
use App\DataTables\WalletlogDataTable;
use Response;
use DB;
class WalletController extends Controller
{


    /*----------------------------------------------------
      || Name     : show all wallets                     |
      || Tested   : Done                                    |
      || using  : datatables                                      |
       ||                                    |
           -----------------------------------------------------*/

    public function index(WalletDataTable $walletDataTable)
    {
        if (auth()->user()->hasPermission('read_wallets')) {

            return $walletDataTable->render('dashboard.wallet', [
                'title' => trans('site.wallets'),
                'model' => 'wallets',
                'count' => $walletDataTable->count()
            ]);
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of index


    /*----------------------------------------------------
         || Name     : open popmodal to show balance                      |
         || Tested   : Done                                    |
         ||                                     |
          ||                                    |
          -----------------------------------------------------*/

    public function openmodal(Request $request)
    {

        $wallent = Wallet::where('id', $request->id)->first();


        $balancenew = $wallent->balance + $request->new_balance;

        $wallent->update(['balance' => $balancenew]);


        $logs = Walletlog::where('user_id', $wallent->user_id)->first();

        if ($logs) {
            $logs->update(['comment' => $request->comment]);


            session()->flash('success', __('site.added_successfully'));

        }


        return redirect(route('dashboard.wallets.index'));


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
        if (auth()->user()->hasPermission('create_wallets')) {

            $users = User::get();
            $shops = Shop::get();
            return view('dashboard.wallets.create', compact('users', 'shops'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }

    }//end of create


    /*----------------------------------------------------
    || Name     : store data into database Wallet          |
    || Tested   : Done                                    |
    ||                                     |
    ||                                    |
       -----------------------------------------------------*/

    public
    function store(Request $request)
    {
//
//        DB::beginTransaction();
//        try {
            $wallet = Wallet::create($request->except('_token', '_method', 'image'));


            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $destinationPath = 'uploads/shops/wallets/';
                $extension = $image->getClientOriginalExtension(); // getting image extension
                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                $image->move($destinationPath, $name); // uploading file to given
                $wallet->image = $name;
                $wallet->save();
            }


            if ($wallet) {
                flash(__('site.added_successfully'))->success();
                return redirect(route('dashboard.wallets.index'));

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
    function edit(WalletlogDataTable $dataTable ,$id)
    {
        if (auth()->user()->hasPermission('update_wallets')) {

            $wallet = Wallet::find($id);



//            return view('dashboard.wallets.edit', compact('wallet'));

            return $dataTable->render('dashboard.wallets.edit', [
                'title' => trans('site.wallets'),
                'wallet' => $wallet,

            ]);

        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }



 public
    function show(WalletlogDataTable $dataTable ,$id)
    {
        if (auth()->user()->hasPermission('update_wallets')) {

            $wallet = Wallet::find($id);



//            return view('dashboard.wallets.edit', compact('wallet'));

            return $dataTable->render('dashboard.wallets.edit', [
                'title' => trans('site.wallets'),
                'wallet' => $wallet,

            ]);

        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }
    /*----------------------------------------------------
      || Name     : update data into database using Wallet        |
      || Tested   : Done                                    |
        ||                                     |
         ||                                    |
            -----------------------------------------------------*/

    public function update($id, Request $request)
    {
//        DB::beginTransaction();
//        try {
            $wallet = Wallet::find($id);
            $wallet->update($request->except('_token', '_method', 'image'));

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $destinationPath = 'uploads/shops/wallets/';
                $extension = $image->getClientOriginalExtension(); // getting image extension
                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                $image->move($destinationPath, $name); // uploading file to given
                $wallet->image = $name;
                $wallet->save();
            }


            if ($wallet) {
                flash(__('site.updated_successfully'))->success();
                return redirect(route('dashboard.wallets.index'));

//            session()->flash('success', __('site.updated_successfully'));

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
    || Name     : delete data into database using roles        |
   || Tested   : Done                                    |
     ||                                     |
      ||                                    |
        -----------------------------------------------------*/


    public
    function destroy($id)
    {
        $wallet = Wallet::find($id);

        $result = $wallet->delete();

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
