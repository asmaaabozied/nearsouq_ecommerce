<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\OrderDatatables;
use App\Http\Controllers\Controller;
use App\DataTables\OrdersHistoryDatatables;
use Illuminate\Http\Request;
use App\DataTables\transactionDatatables;
use App\Models\Order;
use Session;
use Response;
use DB;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index($type, OrderDatatables $orderDatatables)
    {
        if (auth()->user()->hasPermission('read_orders')) {

            //dd($type);
            $orderDatatables->type = $type;
            return $orderDatatables->render('dashboard.datatable', [
                'title' => trans('site.orders_status.'.strtolower($type)),
                'model' => 'order',
                'count' => $orderDatatables->count(),
                'type' => $type
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
    
    public function history($id, OrdersHistoryDatatables $ordershistory){
        if (auth()->user()->hasPermission('read_orders')) {

            $ordershistory->order_id = $id;
            return $ordershistory->render('dashboard.datatable', [
                'title' => trans('site.orders_history'),
                'model' => 'order',
                'count' => $ordershistory->count(),
                'order_id' => $id
            ]);
        }
        else{
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }
}
