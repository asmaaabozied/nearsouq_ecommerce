<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\OrderDatatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\TransactionPageDatatables;
use App\Models\Transaction;
use Illuminate\Support\Facades\App;
use Session;
class TransactionsPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransactionPageDatatables $transactionPageDatatables)
    {
        if (auth()->user()->hasPermission('read_transactions')) {

            //dd(Session::get('shop_id'));
            return $transactionPageDatatables->render('dashboard.datatable', [
                'title' => trans('site.transactions'),
                'model' => 'Transaction',
                'count' => $transactionPageDatatables->count()
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
}
