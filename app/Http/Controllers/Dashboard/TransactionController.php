<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\OrderDatatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\TransactionDatatables;
use App\Models\Transaction;
use Illuminate\Support\Facades\App;
use Session;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransactionDatatables $transactionDatatables)
    {
        if (auth()->user()->hasPermission('read_transactions')) {

            //dd(Session::get('shop_id'));
            return $transactionDatatables->render('dashboard.datatable', [
                'title' => trans('site.transactions'),
                'model' => 'Transaction',
                'count' => $transactionDatatables->count()
            ]);
        }
        else{
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function Gettransactions($id){
        //dd(App::getLocale());
        if (auth()->user()->hasPermission('read_transactions')) {

            $transactions = Transaction::where('transactions.shop_id',$id)
            ->join('transaction_name','transaction_name.id','=','name_id')
            ->join('users','users.id','=','transactions.user_id')
            ->select('transactions.*','transaction_name.name_'.App::getLocale() .' as name','users.name as user_name')
            ->get();

           // dd($transactions);
            return view('dashboard.showTransactions', compact('transactions'));

        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }
}
