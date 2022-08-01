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
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }

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
                'users.phone as user_phone',
                'users.name as user_name',
                'order_details.status as status',
                'order_details.id as id',
                DB::raw("sum(order_details.price) as price"),
                DB::raw("sum(order_details.vat) as vat"),
                DB::raw("sum(order_details.commsion) as commsion")
            )
            ->join('order_details','order_details.order_id','=','orders.id')
            ->join('shops','shops.id','=','order_details.shop_id')
            //->join('addresses','addresses.id','=','order_details.address_id')
            ->join('users','users.id','=','orders.user_id')
            ->where('order_details.id','=',$request['order_id'])
            ->where('order_details.shop_id','=',$request['shop_id'])
            ->orderBy('distance_in_km')
            ->first();
            $sql['user_address'] = Address::find($sql->address_id);
            $sql['shop'] = Shop::find($sql->shop_id);
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
            'users.phone as user_phone',
            'users.name as user_name',
            'order_details.status as status',
            'order_details.id as id'
            )
            ->join('order_details','order_details.order_id','=','orders.id')
            ->join('shops','shops.id','=','order_details.shop_id')
            //->join('addresses','addresses.id','=','order_details.address_id')
            ->join('users','users.id','=','orders.user_id')
            ->where('orders.confirmed', 'TRUE')
            ->where(function ($q) {
                $q->where('order_details.status','=','NOT_DELIVERED')->orWhere('order_details.status','=','READY');
            })->groupBy('order_details.shop_id','orders.user_id','orders.id')
            ->having('distance_in_km','<=', '100')
            ->orderBy('distance_in_km')
            ->get();

        foreach($sql as $order){
            $order['user_address'] = Address::find($order->address_id);
            $order['shop'] = Shop::find($order->shop_id);
            //$order['shop'] = Shop::where('id',$order->shop_id)->select('id as shop_id','brand_name_'. app()->getLocale() . ' as brand_namee','image','longitude','latitude','address')->get();
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
            'order_id'=> 'required|exists:order_details,id',
            'operation' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }

        $order = OrderDetail::find($request->order_id);
        if(($order->status === 'READY' ||  $order->status === 'NOT_DELIVERED')&& $request->operation === 'APPROVED_BY_CAPTAIN'){
            $order->update(['status'=>$request->operation, 'captain_id'=>Auth::user()->id]);
            return $this->responseJson(1, __(''));
        }elseif(($order->status !== 'READY' ||  $order->status !== 'NOT_DELIVERED')&& $request->operation === 'APPROVED_BY_CAPTAIN'){
            return $this->responseJson(1, __('site.statusNotCorrect'));
        }
        elseif($order->status === 'APPROVED_BY_CAPTAIN' && $request->operation === 'SHIPPED'){
            $order->update(['status'=>$request->operation, 'captain_id'=>Auth::user()->id]);
            return $this->responseJson(1, __(''));
        }elseif($order->status !== 'APPROVED_BY_CAPTAIN' && $request->operation === 'SHIPPED'){
            return $this->responseJson(1, __('site.statusNotCorrect'));
        }
        elseif($order->status === 'SHIPPED' && ($request->operation === 'DELIVERED'|| $request->operation === 'NOT_DELIVERED')) {
            $order->update(['status' => $request->operation, 'captain_id'=>Auth::user()->id]);

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

                $order->update(['reason_id'=>$request->reason_id]);
            }
            $order->update(['status' => $request->operation, 'captain_id'=>Auth::user()->id]);
            return $this->responseJson(1, __(''));
        }elseif($order->status !== 'SHIPPED' && ($request->operation === 'DELIVERED'|| $request->operation === 'NOT_DELIVERED')) {
            return $this->responseJson(1, __('site.statusNotCorrect'));
        }
        response()->json(['status' => 1]);
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
                'users.phone as user_phone',
                'users.name as user_name',
        'order_details.status as status',
        'order_details.id as id'
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
            $order['shop'] = Shop::find($order->shop_id);
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
            $order['shop'] = Shop::find($order->shop_id);
        }

        return response()->json(['status' => 1, 'message' => __(''), 'orders' => $sql]);
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
