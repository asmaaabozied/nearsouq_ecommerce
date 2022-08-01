<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\OrderDetailDatatables;
use App\DataTables\OrdersHistoryDatatables;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Resources\OrderDetailResource;
use App\Models\Product;
use App\User;
use App\Helpers\SiteHelper;
use DB;
use App\Models\Notification;
use App\Models\Device;
use App\Models\Transaction;
use App\Helpers\ApiHelper;
use App\Models\Reason;
class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrderDetailDatatables $orderDetailDatatables, Request $request)
    {
        if (auth()->user()->hasPermission('read_orders')) {

            //dd($request);
            $order = Order::where('orders.id', $request->id)
                ->join('users', 'users.id', '=', 'user_id')
                ->join('addresses', 'addresses.id', '=', 'address_id')
                //->join('payment_methods','payment_methods.id','=','payment_type')
                ->select('users.*', 'addresses.*', 'orders.*')->first();
//dd($order);
            $orderDetails = OrderDetail::where('order_id', $request->id)->get();
            $reasons = Reason::get();
            //dd($orderDetails);
            //return view('dashboard.orderDetail.index')->with(['orders'=>$order,'orderDetails'=>$orderDetails]);
            //dd($order);
            return $orderDetailDatatables->with(['order_id' => $request->id, 'order' => $order])->render('dashboard.orderDetail.index', [
                'title' => trans('site.orderDetail'),
                'model' => 'orderDetail',
                'count' => $orderDetailDatatables->count(),
                'order' => $order,
                'reasons' => $reasons
            ]);
        } else {

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (auth()->user()->hasPermission('update_orders')) {

            $orderDetail = OrderDetail::find($id);
            $products = Product::all();
            return view('dashboard.orderDetail.edit', compact('orderDetail', 'products'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $orderDetail = OrderDetail::find($id);
        $order = Order::find($orderDetail->order_id);
        if (!empty($request->status)) {
            //print_r($request->status."====".$id);die();
            $result = $orderDetail->update(['status' => $request->status]);
            ApiHelper::addToOrderHistory($orderDetail->order_id, $orderDetail->id, $orderDetail->shop_id, $orderDetail->product_id, $request->status, Auth::id());

            if($request->status === 'CANCELED' || $request->status === 'CANCELLED_ACCEPTED'){
                
                    if($order->payment_status === 'NOT_PAID'){
                        $orderDetail->update(['status' => 'CANCELLED_ACCEPTED', 'reason_id' => $request->reason_id]);
                        ApiHelper::addToOrderHistory($orderDetail->order_id, $orderDetail->id, $orderDetail->shop_id, $orderDetail->product_id, 'CANCELLED_ACCEPTED', Auth::id());

                    }elseif($order->payment_status === 'PAID'){
                        //dd('here');
                        $orderDetail->update(['status' => 'CANCELLED_ACCEPTED', 'reason_id' => $request->reason_id]);
                        ApiHelper::addToWallet(Auth::id(),$orderDetail->discount_price ? $orderDetail->discount_price : $orderDetail->price,'site.messages.return_order_value', NULL, $orderDetail->id );
                        ApiHelper::addToOrderHistory($orderDetail->order_id, $orderDetail->id, $orderDetail->shop_id, $orderDetail->product_id, 'CANCELLED_ACCEPTED', Auth::id());
                    }
            }elseif($request->status === 'RETURNED'){
                    if($orderDetail->status === 'APPROVED_BY_CAPTAIN'|| $orderDetail->status === 'SHIPPED' || $orderDetail->status === 'DELIVERED'){
                        $orderDetail->update(['status' => 'RETURNED_ACCEPTED']);
                        ApiHelper::addToWallet(Auth::id(),$orderDetail->discount_price ? $orderDetail->discount_price : $orderDetail->price,'site.messages.return_order_value', NULL, $orderDetail->id );
                        ApiHelper::addToOrderHistory($orderDetail->order_id, $orderDetail->id, $order_detail->shop_id, $orderDetail->product_id, 'RETURNED_ACCEPTED', Auth::id());
                    }
                }
            }
            elseif(!empty($request->captain_id)){
                $result = $orderDetail->update(['captain_id' => $request->captain_id,'status'=>'ASSIGNED_BY_CAPTAIN']);
                if ($result) {
                    session()->flash('success', __('site.updated_successfully'));
                } else {
                    session()->flash('error', __('site.update_faild'));
                }
            }
            
            if($order->payment_status === 'PAID' && ($orderDetail->status === 'READY'|| $orderDetail->status === 'RECEIVED')){
                ApiHelper::addToWallet(Auth::id(),$order->delivery_cost,'site.messages.return_delivery_value', $orderDetail->id, NULL );
            }
            if ($result) {
                session()->flash('success', __('site.updated_successfully'));
            } else {
                session()->flash('error', __('site.update_faild'));
            }
        
            if($request->status === 'NOT_DELIVERED' ){
                $orderDetail->update(['reason_id' => $request->reason_id]);
            }
        
        if(!empty($request->status)){
        $user = Order::where('id', $orderDetail->order_id)->first();
        $client = User::find($user->user_id);
        }elseif(!empty($request->captain_id)){
            $client = User::find($request->captain_id);
        }
        if($client){
            //dd($client);
        
            $text = 'site.'.strtolower($request->status);
            $title = 'site.messages.order_status_changed';
            
            $type = "ORDER";
            $user_id = $client->id;
            $environment = 'mobile';
            //dd('hi');
            //$response = SiteHelper::sendMessage($onesignal_id, $text, $title,'mobile',  $type, $id);
            $uid = uniqid();

            Notification::create([
                'title' => $title,
                'message' => $text,
                'user_id' => $user_id,
                'type' => 'ORDER',
                'order_id' => $id,
                'delete' => 0,
                'show' => 1,
                'read' => 0,
            ]);
        
        //dd($user_id);
        $onesignal_ids = Device::where('user_id',$user_id)->get();
            //dd($onesignal_ids);
            if (count($onesignal_ids) > 0) {
                foreach($onesignal_ids as $onesignal_id){
                    //dd($onesignal_id->one_signal_id);
                    if(isset($onesignal_id->one_signal_id)){
            $response = SiteHelper::sendMessage($onesignal_id->one_signal_id, $text, $title, $environment, $type, $client->id);
            //dd($response);
                    }
            }
        }
        
        }
        
        
        if($request->status === 'DELIVERED'){
            $transaction1 = new Transaction();
            $transaction1->name_id = 2;
            $transaction1->credit = $orderDetail->price;
            $transaction1->final_balance = 0;
            $transaction1->shop_id = $orderDetail->shop_id;
            $transaction1->user_id = Auth::user()->id;
            $transaction1->transaction_date = Carbon::today();
            $transaction1->order_id = $orderDetail->order_id;
            $transaction1->save();

            $transaction2 = new Transaction();
            $transaction2->name_id = 3;
            $transaction2->credit = $orderDetail->commsion_value;
            $transaction2->final_balance = 0-$orderDetail->commsion_value;
            $transaction2->shop_id = $orderDetail->shop_id;
            $transaction2->user_id = Auth::user()->id;
            $transaction2->transaction_date = Carbon::today();
            $transaction2->order_id = $orderDetail->order_id;
            $transaction2->save();
        }
        session()->flash('success', __('site.updated_successfully'));
        return \Redirect::route('dashboard.order.details', [$orderDetail->order_id]);

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
        public function history($id, OrdersHistoryDatatables $ordershistory){
        if (auth()->user()->hasPermission('read_orders')) {

            $ordershistory->order_details_id = $id;
            return $ordershistory->render('dashboard.datatable', [
                'title' => trans('site.orders_history'),
                'model' => 'order',
                'count' => $ordershistory->count(),
                'order_details_id' => $id
            ]);
        }
        else{
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }
}
