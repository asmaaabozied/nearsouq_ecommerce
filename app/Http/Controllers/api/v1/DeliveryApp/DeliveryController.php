<?php

namespace App\Http\Controllers\api\v1\DeliveryApp;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lang;
use Illuminate\Support\Facades\Auth;
use LaravelLocalization;
use App\Models\Reason;
use App\Http\Resources\OrderDetailResource;
use DB;
use App\User;
use App\Helpers\SiteHelper;
use App\Models\Notification;
use App\Models\Address;
use App\Models\Shop;
use App\Models\Transaction;
use App\Models\ShopSetting;
use Carbon\Carbon;
use App\Helpers\ApiHelper;
use App\Models\CustomerTransaction;

class orderReport
        {
            public $order_total;
            public $delivery_total;
            public $order_id;
        } 
class DeliveryController extends Controller
{
    
    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }

    /*--------------------------------------------------------------
    || Name     : show orders according to status or keyword        |
    || Tested   : Done                                              |
    || parameter:                                                   |
    || Info     : type                                              |
    ---------------------------------------------------------------*/
    public function index(Request $request){
        $orders =[];
        $rule = [
            'status' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $status = $request->status;
        $keyword = $request->keyword;

        $sql = OrderDetail::query()
            ->join('orders','orders.id','=','order_details.order_id')
            ->select('order_details.*','orders.code');


        if($status){
            if($status === "READY"){
                $orders = $sql->where('order_details.status','READY')->paginate(10);
            }
            elseif($status === "RECEIVED"){
                $orders = $sql->where('order_details.status','RECEIVED')->where('delivered_by',Auth::id())->paginate(10);
            }elseif($status === "SHIPPED"){
                $orders = $sql->where('order_details.status','SHIPPED')->paginate(10);
            }else{
                return response()->json(['status' => 1, 'message' => __('No Orders in this status')]);
            }
        }
        if($keyword){
            $orders = $sql->where('name_ar', 'like', '%' . $keyword . '%')
            ->orWhere('name_en', 'like', '%' . $keyword . '%')->paginate(10);
        }else{
            $orders = $sql->paginate(10);
        }
        if($request->order_id){
            $orders = $sql->where('order_details.id',$request->order_id)->first();
            return response()->json(['status' => 1, 'message' => __(''), 'orders' => $orders]);
        }
        $orders = OrderDetailResource::collection($orders);
        return response()->json(['status' => 1, 'message' => __(''), 'orders' => $orders]);
    }

    /*--------------------------------------------------------------
    || Name     : change status after delivered                     |
    || Tested   : Done                                              |
    || parameter:                                                   |
    || Info     : type                                              |
    ---------------------------------------------------------------*/
    public function changeStatusDelivered(Request $request){
        $rule = [
            'order_id' => 'required|exists:order_details,id',
            'delivery' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $order = OrderDetail::find($request->order_id);
        //dd($order);
        $status = '';
        if($request->delivery === "true"){
            $status = "DELIVERED";
            $order->update(['status' => 'DELIVERED']);
        }elseif($request->delivery === "false"){
            $status = "NOT_DELIVERED";
            $order->update(['status' => 'NOT_DELIVERED','reason_id' => $request->reason_id]);
        }
        $user = Order::where('id',$order->order_id)->pluck('user_id');
        $client = User::findOrFail($user);
        if ($client[0]->onesignal_id != null) {
            $text = $request->order_id . $status;
            $title = $request->order_id;
            $onesignal_id = $client[0]->onesignal_id;
            $type = "ORDER";
            $id=$client[0]->id;

            $response = SiteHelper::sendMessage($onesignal_id,$text,$title,$type, $id);
            $imageid = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
            $uid = uniqid();

            Notification::create([
                'title' => $title,
                'message' => $text, 
                'user_id' => $id,
                'image' => $imageid,
                'delete' => 0,
                'show' => 1,
                'read' => 0,
            ]);
        return response()->json(['status' => 1,'message' => __('Status Changed Successfully')]);
    }
}

    /*--------------------------------------------------------------
    || Name     : get list of delivery return reasons               |
    || Tested   : Done                                              |
    || parameter:                                                   |
    || Info     : type                                              |
    ---------------------------------------------------------------*/
    public function deliveryReasons(){
        $reasons = Reason::where('type','DeliveryApp')->orderBy('created_at','DESC')->get();

        return $this->responseWithoutMessageJson(1,$reasons);
    }
    
    /*--------------------------------------------------------------
    || Name     : get order Details                                 |
    || Tested   : Done                                              |
    || parameter:                                                   |
    || Info     : type                                              |
    ---------------------------------------------------------------*/
    public function orderDetails(Request $request){
        $rule = [
            'order_id' => 'required|exists:order_details,id',
            'shop_id' => 'required|exists:shops,id',
            'operation' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $price_all = 0;
        $commsion_all =0;
        $delivery = User::where('id',auth()->id())->first();
        $sql = Order::query()
        ->select( DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$delivery->longitude'))
                * COS(RADIANS(shops.longitude))
                * COS(RADIANS(shops.latitude)
                - RADIANS('$delivery->latitude'))
                + SIN(RADIANS('$delivery->longitude'))
                * SIN(RADIANS(shops.longitude)))) ,2)
                as distance_in_km"),
            'order_details.shop_id',
            DB::raw("count(order_details.id) as count_of_product"),
            'shops.brand_name_ar',
            'shops.address as shop_address',
            'orders.*',
            'orders.id as order_id',
        'users.phone as user_phone',
        'users.name as user_name',
        'order_details.status as status'
        )
        ->join('order_details','order_details.order_id','=','orders.id')
        ->join('shops','shops.id','=','order_details.shop_id')
        //->join('addresses','addresses.id','=','order_details.address_id')
        ->join('users','users.id','=','orders.user_id')
        ->where('order_details.order_id','=',$request['order_id'])
        ->where('order_details.shop_id','=',$request['shop_id'])
        ->where('order_details.status',$request->operation);
        
        /*$sql->where(function ($sql){
        $sql->where('order_details.status','READY')
        ->orWhere('captain_id','=',auth()->id());
        });*/
        
        $sql = $sql->groupBy('order_details.shop_id','orders.user_id','orders.id')
        ->orderBy('distance_in_km')
        ->first();
            //return $sql;
            if(!empty($sql)){
            $sql['current_price'] = 0;  $sql['current_commsion'] = 0 ; $sql['count_of_product'] =0;
            $payment = ShopSetting::where('shop_id',$request['shop_id'])->pluck('payment');
    
                $transactions_credit = Transaction::where('shop_id',$request['shop_id'])->where('name_id',3)->where('final_balance','<',0)->sum('credit');
                $transaction_debit = Transaction::where('shop_id',$request['shop_id'])->where('name_id',4)->sum('debit');
        
                $sql['old_commsion'] = 0 - ($transaction_debit - $transactions_credit);
        
                $transactions_merchant = Transaction::where('shop_id',$request['shop_id'])->where('name_id',3)->where('final_balance','>',0)->where('credit','>',0)->sum('final_balance');
                $transactions_merchant_credit = Transaction::where('shop_id',$request['shop_id'])->where('name_id',2)->where('final_balance','=',0)->sum('credit');
        
                $sql['old_merchant'] = $transactions_merchant ;
        //return $sql;
        $orderDetails = OrderDetail::where('order_id',$request['order_id'])->where('shop_id',$request['shop_id'])->where('status', $request->operation);
        /*$orderDetails->where(function ($orderDetails){
        $orderDetails->where('order_details.status','READY')
        ->orWhere('captain_id','=',auth()->id());
        });*/
        $orderDetails = $orderDetails->get();
            
                $sql['user_address'] = Address::find($sql->address_id);
                $shop = Shop::where('id',$request['shop_id'])->without('Products')->first();
                //return $sql;
                foreach($orderDetails as $order){
                if($order->discount_price != NULL){
                    $sql['current_price'] += $order->discount_price;
                }else{
                    $sql['current_price'] += $order->price;
                }
                $sql['current_commsion'] += $order->commsion_value;

                //check if the customer paid the delivery cost 
                $customer_transaction = CustomerTransaction::where('order_id',$order->order_id)->where('payment_name','DELIVERY')->first();
                if($customer_transaction == NULL){
                    $sql['total'] = $sql['current_price'] + $sql['delivery_cost'];
                }else{
                    $sql['total'] = $sql['current_price'] ;
                }               
                //total value to get or pay to merchant according the amount
                //$sql['merchant_will_get'] += $order->merchant_will_get - $sql['old_commsion'] + $sql['old_merchant'];
                $sql['merchant_will_get'] += $order->merchant_will_get;
                $sql['count_of_product'] +=1;
            }
                
            $shop['products'] = OrderDetailResource::collection($orderDetails);
            //array_push($data, $sql);
            $sql['shop'] = $shop;
            }
        return response()->json(['status' => 1, 'message' => __(''), 'order' => $sql]);
    }

    /*----------------------------------------------------
    || Name     : TO GET NEAREST ORDERS TO USER           |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function nearestDelivery( Request $request){
        $rule = [
            'longitude' => 'required',
            'latitude' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }

        //storing delivery new location 
        $delivery = User::where('id',auth()->id())->first();
        $delivery->update(['latitude'=>$request->latitude, 'longitude'=>$request->longitude]);
        
        $sql = Order::query()
            ->select( DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$request->longitude'))
                    * COS(RADIANS(shops.longitude))
                    * COS(RADIANS(shops.latitude)
                    - RADIANS('$request->latitude'))
                    + SIN(RADIANS('$request->longitude'))
                    * SIN(RADIANS(shops.longitude)))) ,2)
                    as distance_in_km"),
                'order_details.shop_id',
                DB::raw("count(order_details.id) as count_of_product"),
                'shops.brand_name_ar',
                'shops.address as shop_address',
                'orders.*',
                'orders.id as order_id',
            'users.phone as user_phone',
            'users.name as user_name',
            'order_details.status as status'
            //'order_details.id as id'
            )
            ->join('order_details','order_details.order_id','=','orders.id')
            ->join('shops','shops.id','=','order_details.shop_id')
            //->join('addresses','addresses.id','=','order_details.address_id')
            ->join('users','users.id','=','orders.user_id')
            ->where('orders.confirmed', 'TRUE')
            ->where(function ($q) {
                $q->where('order_details.status','=','READY');
            })->groupBy('order_details.shop_id','orders.user_id','orders.id')
            //->having('distance_in_km','<=', '100')
            ->orderBy('distance_in_km')
            ->get();

        foreach($sql as $order){
                $order['user_address'] = Address::find($order->address_id);
                $shop = Shop::where('id',$order->shop_id)->without('Products')->first();
                $order_details = OrderDetail::where('order_id',$order->order_id)->where('shop_id',$shop->id)->get();
                $shop['products'] = OrderDetailResource::collection($order_details);
                $order['shop'] = $shop;
            }
        //$orders = OrderResource::collection($sql);
        return response()->json(['status' => 1, 'message' => __(''), 'orders' => $sql]);
    }
    /*----------------------------------------------------
    || Name     : change order status to shipped          |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/

    public function changeStatus(Request $request){
        $rule = [
            'shop_id' => 'required|exists:shops,id',
            'order_id'=> 'required|exists:orders,id',
            'operation' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        //return $this->responseJson(1, __($request->order_id));
        $orders = OrderDetail::where('order_id',$request->order_id)
        ->join('orders','order_details.order_id','=','orders.id')
        ->where('shop_id',$request->shop_id)
        ->select('order_details.*','orders.payment_type','orders.user_id', 'orders.delivery_cost')
        ->get();
        $price_all = 0;
        $commsion_all = 0;
        $merchant_will_get_all = 0;
        $transaction = false;
        $return_transaction = false;
        $status = false;
        foreach($orders as $order){
        if(($order->status === 'READY' ||  $order->status === 'NOT_DELIVERED')&& $request->operation === 'APPROVED_BY_CAPTAIN'){
            $order->update(['status'=>$request->operation, 'captain_id'=>Auth::user()->id]);
            ApiHelper::addToOrderHistory($order->order_id, $order->id, $order->shop_id, $order->product_id, $request->operation, Auth::id());
                $status = true;
        }
        elseif(($order->status === 'APPROVED_BY_CAPTAIN' || $order->status === 'ASSIGNED_BY_CAPTAIN') && $request->operation === 'SHIPPED'){
            $order->update(['status'=>$request->operation, 'captain_id'=>Auth::user()->id]);
            ApiHelper::addToOrderHistory($order->order_id, $order->id, $order->shop_id, $order->product_id, $request->operation, Auth::id());
            $status= true;
            $payment = ShopSetting::where('shop_id',$request->shop_id)->pluck('payment');
                //dd($payment);
                if($order->discount_price){
                    $price_all += $order->discount_price;
                }else{
                    $price_all += $order->price;
                }
                $commsion_all += $order->commsion_value;
                $merchant_will_get_all += $order->merchant_will_get;
            $transaction = true;
        }
        elseif(($order->status === 'SHIPPED' ||  $order->status === 'NOT_DELIVERED')&& ($request->operation === 'DELIVERED'|| $request->operation === 'NOT_DELIVERED')) {
            $status = true;
            if($request->operation === 'DELIVERED'){
                $order->update(['status' => $request->operation, 'captain_id'=>Auth::user()->id]);
                ApiHelper::createCustomerTrans($order->shop_id, $order->order_id, $order->id, $order->captain_id ? $order->captain_id : NULL, $order->user_id ? $order->user_id : NULL, $order->payment_type, 'ORDER', $order->discount_price ? $order->discount_price : $order->price);
                $customer_transaction = CustomerTransaction::where('order_id',$order->order_id)->where('payment_name','DELIVERY')->first();
                if($customer_transaction == NULL){
                    ApiHelper::createCustomerTrans($order->shop_id, $order->order_id, NULL, $order->captain_id ? $order->captain_id : NULL, $order->user_id ? $order->user_id : NULL, $order->payment_type, 'DELIVERY', $order->delivery_cost);
                }
            }

            if($request->operation === 'NOT_DELIVERED'){
                $rule = [
                    'reason_id' => 'required|exists:reason,id',
                ];

                $customMessages = [
                    'required' => __('validation.attributes.required'),
                ];

                $validator = validator()->make($request->all(), $rule, $customMessages);

                if ($validator->fails()) {
                    return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
                }

                $order->update(['reason_id'=>$request->reason_id, 'status' => $request->operation, 'captain_id'=>Auth::user()->id]);
            }
            ApiHelper::addToOrderHistory($order->order_id, $order->id, $order->shop_id, $order->product_id, $request->operation, Auth::id());
            //return $this->responseJson(1, __(''));
        }
        elseif($request->operation === 'RETURNED' && !($order->status === 'RETURNED' || $order->status === 'RETURNED_ACCEPTED')){
            $order->update(['status' => 'RETURNED_ACCEPTED', 'updated_by'=>Auth::user()->id]);
            $status = true;
            ApiHelper::addToOrderHistory($order->order_id, $order->id, $order->shop_id, $order->product_id, 'RETURNED_ACCEPTED', Auth::id());
            $payment = ShopSetting::where('shop_id',$request->shop_id)->pluck('payment');
            if($order->discount_price){
                $price_all += $order->discount_price;
            }else{
                $price_all += $order->price;
            }
            $commsion_all += $order->commsion_value;
            $merchant_will_get_all += $order->merchant_will_get;
                $return_transaction = true;
        }
        
    }
    if($status == false) {
        return $this->responseJson(1, __('site.statusNotCorrect'.$status));
    }
    $user = Order::where('id',$order->order_id)->pluck('user_id');
        $client = User::findOrFail($user);
        if ($client[0]->onesignal_id != null) {
            $text = 'site.'.strtolower($request->operation);
            $title = 'site.messages.order_status_changed';
            $onesignal_id = $client[0]->onesignal_id;
            $order_id = $order->id;
            $type = "ORDER";
            $id=$client[0]->id;

            $response = SiteHelper::sendMessage($onesignal_id,$text,$title,$type, $id);
            $imageid = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
            $uid = uniqid();

            Notification::create([
                'title' => $title,
                'message' => $text, 
                'user_id' => $id,
                'image' => $imageid,
                'type' => $type,
                'order_id' => $order->id,
                'delete' => 0,
                'show' => 1,
                'read' => 0,
            ]);
        }
        
    if($transaction == true){
        $payment = ShopSetting::where('shop_id',$request->shop_id)->pluck('payment');
        if(count($payment) > 0){
        $old = $this->createTransaction($payment, $price_all, $commsion_all, $merchant_will_get_all,$request->shop_id,$request->order_id, $request);
        //return $old;
        $data['old_commsion'] = $old['old_commsion'];
        $data['old_merchant'] = $old['old_merchant'];
        
        if($payment[0] === 'prompt'){
            $data['current_commsion'] = $commsion_all;
        }
        $data['current_price'] = $price_all;
        return $this->responseJson(1, __(''), $data);
        }
    }
    if($return_transaction == true){
        $payment = ShopSetting::where('shop_id',$request->shop_id)->pluck('payment');
        if(count($payment) > 0){
        if($payment[0] === 'prompt'){
            $this->createReturnTransaction($payment, $price_all, $commsion_all, $merchant_will_get_all,$request->shop_id,$request->order_id);
        }
    }
    }
    return $this->responseJson(1, __(''));
    }

    /*------------------------------------------------------
    || Name     : create transactions if order is delivered |
    || Tested   : Done                                      |
    || parameter:                                           |
    || Info     : type                                      |
    -------------------------------------------------------*/
    public function createTransaction($payment, $price_all, $commsion_all, $merchant_will_get_all, $shop_id, $order_id, $request){
        $transaction1 = new Transaction();
        // $transaction2 = new Transaction();
        
        $transactions_credit = Transaction::where('shop_id',$shop_id)->where('name_id',3)->where('final_balance','<',0)->sum('credit');
        $transaction_debit = Transaction::where('shop_id',$shop_id)->where('name_id',4)->sum('debit');

        $old_commsion = 0 - ($transaction_debit - $transactions_credit);

        $transactions_merchant = Transaction::where('shop_id',$shop_id)->where('name_id',3)->where('final_balance','>',0)->where('credit','>',0)->sum('final_balance');
        $transactions_merchant_credit = Transaction::where('shop_id',$shop_id)->where('name_id',2)->where('final_balance','=',0)->sum('credit');

        $old_merchant = $transactions_merchant ;
        if($old_commsion != 0){
            // $transaction_old = new Transaction();
            // $transaction_old->debit = $old_commsion;
            // $transaction_old->name_id = 4;
            // $transaction_old->final_balance = 0;
            // $transaction_old->shop_id = $shop_id;
            // $transaction_old->user_id = Auth::user()->id;
            // $transaction_old->transaction_date = Carbon::today();
            // $transaction_old->order_id = $order_id;
            // $transaction_old->save();
             $this->saveTransaction($old_commsion,4,0,$shop_id,$order_id );
        }
        
                    if($payment[0] === 'prompt'){
                        
                        $transaction3 = new Transaction();

                        // $transaction = new Transaction();
                        // $transaction->debit = $merchant_will_get_all;
                        // $transaction->name_id = 1;
                        // $transaction->final_balance = $merchant_will_get_all ;
                        // $transaction->shop_id = $shop_id;
                        // $transaction->user_id = Auth::user()->id;
                        // $transaction->transaction_date = Carbon::today();
                        // $transaction->order_id = $order_id;
                        // $transaction->save();
                        
                          $result = $this->saveTransaction($merchant_will_get_all,1,$merchant_will_get_all,$shop_id,$order_id );
                        
                        $transaction1->credit = $merchant_will_get_all ;
                        $transaction1->name_id = 2;
                        $transaction1->final_balance = 0;
                        $transaction1->shop_id = $shop_id;
                        $transaction1->user_id = Auth::user()->id;
                        $transaction1->transaction_date = Carbon::today();
                        $transaction1->order_id = $order_id;
                        $transaction1->save();
                        if ($request->hasFile('image')) {
                        UploadImage('uploads/shops/invoicesImages',$transaction1,$request);
                        }

                        // $transaction2->credit = $commsion_all;
                        // $transaction2->name_id = 3;
                        // $transaction2->final_balance = 0-$commsion_all;
                        // $transaction2->shop_id = $shop_id;
                        // $transaction2->user_id = Auth::user()->id;
                        // $transaction2->transaction_date = Carbon::today();
                        // $transaction2->order_id = $order_id;
                        // $transaction2->save();
                          $result = $this->saveTransaction($commsion_all,3, 0-$commsion_all,$shop_id,$order_id );

                        // $transaction3->debit = $commsion_all;
                        // $transaction3->name_id = 4;
                        // $transaction3->final_balance = 0;
                        // $transaction3->shop_id = $shop_id;
                        // $transaction3->user_id = Auth::user()->id;
                        // $transaction3->transaction_date = Carbon::today();
                        // $transaction3->order_id = $order_id;
                        // $transaction3->save();
                          $result = $this->saveTransaction($commsion_all,4, 0,$shop_id,$order_id );

                        
                    }elseif($payment[0] === 'post'){
                        // $transaction = new Transaction();
                        // $transaction->debit = $merchant_will_get_all;
                        // $transaction->name_id = 1;
                        // $transaction->final_balance = $merchant_will_get_all ;
                        // $transaction->shop_id = $shop_id;
                        // $transaction->user_id = Auth::user()->id;
                        // $transaction->transaction_date = Carbon::today();
                        // $transaction->order_id = $order_id;
                        // $transaction->save();
                          $result = $this->saveTransaction($merchant_will_get_all,1,$merchant_will_get_all,$shop_id,$order_id );

                        // $transaction1->credit = $commsion_all;
                        // $transaction1->name_id = 3;
                        // $transaction1->final_balance = $merchant_will_get_all;
                        // $transaction1->shop_id = $shop_id;
                        // $transaction1->user_id = Auth::user()->id;
                        // $transaction1->transaction_date = Carbon::today();
                        // $transaction1->order_id = $order_id;
                        // $transaction1->save();
                          $result = $this->saveTransaction($commsion_all,3,$merchant_will_get_all,$shop_id,$order_id );

                        // $transaction2->credit = $merchant_will_get_all;
                        // $transaction2->name_id = 2;
                        // $transaction2->final_balance = 0;
                        // $transaction2->shop_id = $shop_id;
                        // $transaction2->user_id = Auth::user()->id;
                        // $transaction2->transaction_date = Carbon::today();
                        // $transaction2->order_id = $order_id;
                        // $transaction2->save();
                        $result = $this->saveTransaction($merchant_will_get_all,2,0,$shop_id,$order_id );

                    }
                    $old = ["old_commsion"=>$old_commsion, "old_merchant"=>$old_merchant];
                    
                    return $old;
     }
     
     protected function saveTransaction($debit,$name_id,$final_balance,$shop_id,$order_id ){
            $transaction_old = new Transaction();
            $transaction_old->debit = $debit;
            $transaction_old->name_id = $name_id;
            $transaction_old->final_balance = $final_balance;
            $transaction_old->shop_id = $shop_id;
            $transaction_old->user_id = Auth::user()->id;
            $transaction_old->transaction_date = Carbon::today();
            $transaction_old->order_id = $order_id;
            $transaction_old->save();
            return ;
         
     }
     /*------------------------------------------------------
    || Name     : create transactions if order is returned  |
    || Tested   : Done                                      |
    || parameter:                                           |
    || Info     : type                                      |
    -------------------------------------------------------*/
     public function createReturnTransaction($payment, $price_all, $commsion_all, $merchant_will_get_all, $shop_id, $order_id){
        // $transaction1 = new Transaction();
        // $transaction2 = new Transaction();
        // $transaction3 = new Transaction();
        // $transaction4 = new Transaction();
        // $transaction1->debit = $commsion_all;
        // $transaction1->name_id = 5;
        // $transaction1->final_balance = $commsion_all;
        // $transaction1->shop_id = $shop_id;
        // $transaction1->user_id = Auth::user()->id;
        // $transaction1->transaction_date = Carbon::today();
        // $transaction1->order_id = $order_id;
        // $transaction1->save();
        
                 $result = $this->saveTransaction($commsion_all,5,$commsion_all,$shop_id,$order_id );


        // $transaction2->credit = $commsion_all;
        // $transaction2->name_id = 6;
        // $transaction2->final_balance = 0;
        // $transaction2->shop_id = $shop_id;
        // $transaction2->user_id = Auth::user()->id;
        // $transaction2->transaction_date = Carbon::today();
        // $transaction2->order_id = $order_id;
        // $transaction2->save();
                $result = $this->saveTransaction($commsion_all,6,0,$shop_id,$order_id );

        // $transaction3->credit = $merchant_will_get_all;
        // $transaction3->name_id = 5;
        // $transaction3->final_balance = $merchant_will_get_all;
        // $transaction3->shop_id = $shop_id;
        // $transaction3->user_id = Auth::user()->id;
        // $transaction3->transaction_date = Carbon::today();
        // $transaction3->order_id = $order_id;
        // $transaction3->save();
              $result = $this->saveTransaction($merchant_will_get_all,5,$merchant_will_get_all,$shop_id,$order_id );

        // $transaction4->debit = $merchant_will_get_all;
        // $transaction4->name_id = 5;
        // $transaction4->final_balance = 0;
        // $transaction4->shop_id = $shop_id;
        // $transaction4->user_id = Auth::user()->id;
        // $transaction4->transaction_date = Carbon::today();
        // $transaction4->order_id = $order_id;
        // $transaction4->save();
                $result = $this->saveTransaction($merchant_will_get_all,5,0,$shop_id,$order_id );

     }
     
    /*----------------------------------------------------
    || Name     : To view the captain's requests          |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function captainOrders(Request $request){
        $rule = [
            'operation' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $sql = Order::query()
            ->select('order_details.shop_id',
            DB::raw("count(order_details.id) as count_of_product"),
                'shops.brand_name_ar',
                'orders.*',
                'orders.id as order_id',
                'users.phone as user_phone',
                'users.name as user_name',
        'order_details.status as status'
        //'order_details.id as id'
            )->join('order_details','order_details.order_id','=','orders.id')
            ->join('shops','shops.id','=','order_details.shop_id')
            ->join('users','users.id','=','orders.user_id')
            ->where('order_details.captain_id',Auth::user()->id)
            ->where('order_details.status',$request->operation)
            ->groupBy('order_details.shop_id','orders.user_id','orders.id')
            ->orderBy('order_details.shop_id')
            ->get();

        foreach($sql as $order){
                $order['user_address'] = Address::find($order->address_id);
                $shop = Shop::where('id',$order->shop_id)->without('Products')->first();
                $order_details = OrderDetail::where('order_id',$order->order_id)->where('shop_id',$shop->id)->get();
                $shop['products'] = OrderDetailResource::collection($order_details);
                $order['shop'] = $shop;
            }

        return response()->json(['status' => 1, 'message' => __(''), 'orders' => $sql]);
    }
    
    /*-------------------------------------------------------
    || Name     : To get the ready orders for the same user  |
    || Tested   : Done                                       |
    || parameter:                                            |
    || Info     : type                                       |
    --------------------------------------------------------*/
    public function relatedOrders(Request $request){
        $rule = [
            'order_id' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $user_id = OrderDetail::where('order_details.id',$request['order_id'])
            ->join('orders','orders.id','=','order_details.order_id')->first();
        $sql = Order::query()
            ->select( DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$request->longitude'))
                    * COS(RADIANS(shops.longitude))
                    * COS(RADIANS(shops.latitude)
                    - RADIANS('$request->latitude'))
                    + SIN(RADIANS('$request->longitude'))
                    * SIN(RADIANS(shops.longitude)))) ,2)
                    as distance_in_km"),
            'order_details.shop_id',
                DB::raw("count(order_details.id) as count_of_product"),
                'shops.brand_name_ar',
                'orders.*',
                'orders.id as order_id',
                'users.phone as user_phone',
                'users.name as user_name',
                'order_details.status as status',
                'order_details.id as id'
            )->join('order_details','order_details.order_id','=','orders.id')
            ->join('shops','shops.id','=','order_details.shop_id')
            ->join('users','users.id','=','orders.user_id')
            ->where('orders.user_id','=',$user_id->user_id)
            ->where('order_details.status','READY')
            ->groupBy('order_details.shop_id','orders.user_id','orders.id')
            ->orderBy('order_details.shop_id')
            ->get();

        foreach($sql as $order){
            $order['user_address'] = Address::find($order->address_id);
            $shop = Shop::where('id',$order->shop_id)->without('Products')->first();
            $order_details = OrderDetail::where('order_id',$order->order_id)->where('shop_id',$shop->id)->get();
            $shop['products'] = OrderDetailResource::collection($order_details);
            $order['shop'] = $shop;
        }

        return response()->json(['status' => 1, 'message' => __(''), 'orders' => $sql]);
    }
    
    /*----------------------------------------------------
    || Name     : get delivery Report                     |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function deliveryReport(Request $request){
        $rule = [
            'from' => 'required',
            'to' => 'required',
            'captain_id' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }

        $orders = OrderDetail::where('captain_id',$request->captain_id)
        ->where('delivery_date','>=',$request->from)
        ->where('delivery_date','<=',$request->to)
        ->where('order_details.status','DELIVERED')
        ->join('orders','order_details.order_id','=','orders.id')
        ->select('order_details.*','orders.delivery_cost')
        ->get();
        
        $orderReport = NULL;
        $reports = array();
        $total = 0;
        $total_delivery =0;
        foreach($orders as $order){
            $merchant_will_get = $order->merchant_will_get;
            $commsion_value = $order->commsion_value;
            $delivery_cost = $order->delivery_cost;
            $report = new orderReport();
            $report->order_total = round($order->merchant_will_get+$order->commsion_value,0,PHP_ROUND_HALF_DOWN);
            $report->delivery_total = $order->delivery_cost;
            $report->order_id = $order->id;
            
            array_push($reports, $report);
            $total += $merchant_will_get+$commsion_value;
            $total_delivery = $delivery_cost;
        }

        return response()->json(['status' => 1, 'message' => __(''), 'orders' => $reports, 'total'=>round($total,0,PHP_ROUND_HALF_DOWN), 'total_delivery'=>round($total_delivery,0,PHP_ROUND_HALF_DOWN)]);
    }

    /*----------------------------------------------------
    || Name     : get paginate                            |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
