<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Cart_product_option;
use App\Models\Deliverycalculator;
use App\Models\Option;
use App\Models\Order;
use App\Models\Order_products_option;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\Variant;
use App\Models\Favorite;
use App\Models\DeliveryRelation;
use App\Models\DeliveryCost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelLocalization;
use DB;
use App\Models\Reason;
use App\User;
use App\Models\Address;
use PDF;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;
use Prgayman\Zatca\Facades\Zatca;
use Prgayman\Zatca\Utilis\QrCodeOptions;
use App\Helpers\SiteHelper;
use App\Models\Notification;
use Illuminate\Support\Facades\App;
use App\Models\Transaction;
use App\Helpers\ApiHelper;
use App\Models\DeliveryOffer;

class OrderController extends Controller
{

    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }

    /*------------------------------------------------
    || Name     : return the reasons for cancel order |
    || Tested   : Done                                |
    || parameter:                                     |
    || Info     : type                                |
    -------------------------------------------------*/
    public function listOfReason()
    {
        $reasons = Reason::where('type', 'CustomerApp')->orderBy('created_at', 'DESC')->get();

        return $this->responseWithoutMessageJson(1, $reasons);
    }

    /*--------------------------------------------
    || Name     : show order details for auth user |
    || Tested   : Done                             |
    || parameter:                                  |
    || Info     : type                             |
    ---------------------------------------------*/
    public function showOrder(Request $request)
    {
        $rule = [
            'id' => 'required|exists:orders,id',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $orders = Order::where('user_id', Auth::id())->where('id', $request->id)->with(['address', 'PaymentType', 'orderDetails'])->first();
        $orderss = new OrderResource($orders);

        return response()->json(['status' => 1, 'order' => $orderss]);
    }

    /*--------------------------------------------
    || Name     : list of all orders to auth user |
    || Tested   : Done                            |
    || parameter:                                 |
    || Info     : type                            |
    ---------------------------------------------*/
    public function listOfOrder()
    {
        $orders = Order::where('user_id', Auth::id())->with(['address', 'PaymentType', 'orderDetails'])->orderBy('created_at', 'DESC')->get();
        $orderss = OrderResource::collection($orders);

        return $this->responseWithoutMessageJson(1, $orderss);
    }

    /*----------------------------------------------------
    || Name     : get list of orders filtering by status  |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function listOfOrderWithType(Request $request)
    {
        $rule = [
            'type' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $orders = Order::where('user_id', Auth::id())->where('status', $request->type)->with(['address', 'PaymentType', 'orderDetails'])->orderBy('created_at', 'DESC')->get();
        $orderss = OrderResource::collection($orders);
        return $this->responseWithoutMessageJson(1, $orderss);
    }

    /*------------------------------------------
    || Name     : cancel order of auth user     |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function canceledOrder(Request $request)
    {
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
        $operation = $request->operation;
        if (isset($request->order_id)) {
            $order = Order::find($request->order_id);
            $orderdetails = OrderDetail::where('order_id', $request->order_id)->get();
            if($operation === 'canceled'){
                $order->update(['status' => 'canceled']);
                foreach($orderdetails as $orderdetail){
                    $orderdetail->update(['status' => 'canceled', 'reason_id' => $request->reason_id]);
                }
            }elseif($operation === 'returned'){
                $order->update(['status' => 'returned']);
                foreach($orderdetails as $orderdetail){
                $orderdetail->update(['status' => 'returned']);
                }
            }
            
        }
        if (isset($request->order_detail_id)) {
            $orderdetails = OrderDetail::where('id', $request->order_detail_id)->first();
            if($operation === 'canceled'){
                $orderdetails->update(['status' => 'canceled', 'reason_id' => $request->reason_id]);
            }elseif($operation === 'returned'){
                $orderdetails->update(['status' => 'returned']);
            }
        }
        return response()->json(['status' => 1]);
    }


    /*------------------------------------------
    || Name     : confirm order of auth user    |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function confirmOrder(Request $request)
    {
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
        if (isset($request->order_id)) {
            $order = Order::find($request->order_id);
            $order->update(['confirmed' => 'TRUE',
                'payment_type' => "CASH",
                'payment_status' => 'NOT_PAID']);
        }

        return response()->json(['status' => 1]);
    }

    /*------------------------------------------
    || Name     : add Order                     |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function addOrder($locale, Request $request)
    {
        $rule = [
            'address_id' => 'required',
            'capon_id' => 'nullable',
            'delivery' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
// $deliverycalculator= Deliverycalculator::select(DB::raw('SUM(cost) As cost'), DB::raw('SUM(distance) As distance'))
//          ->where('user_id',  Auth::id())
//          ->get();


// return $this->responseJson(1, __($distance."++++ cost=".$delivery_cost));
        if($request->delivery == 1){
            $delivery_cost= Deliverycalculator::where('user_id',Auth::id())->sum('cost');
            $distance= Deliverycalculator::where('user_id',Auth::id())->sum('distance');
            $delivery_offer = 0;
            $carts = Cart::where('user_id', Auth::id())->with('variants')->get();
                if (!$carts->isEmpty()) {
                    $array = ApiHelper::totalCart();
                    if(is_array($array)){
                    $total_cart =  $array['total'];
                    

                $today = Carbon::today();
                $cart_offer = DeliveryOffer::where('total_cart','<=',$total_cart)->where('start_date','<=',$today)->where('end_date','>=',$today)->where('status','enable')->first();

                $delivery_offer = DeliveryOffer::where('total_delivery','<=',$delivery_cost)->where('start_date','<=',$today)->where('end_date','>=',$today)->where('status','enable')->first();
                
                if($cart_offer){
                    $data['total_offer'] = $delivery_cost * $cart_offer->discount_percentage / 100;
                    $delivery_cost = $delivery_cost - $data['total_offer'];
                    $delivery_offer = $cart_offer->discount_percentage;
                }elseif($delivery_offer && !$cart_offer){
                    $data['total_offer'] = $delivery_cost * $delivery_offer->discount_percentage / 100;
                    $delivery_cost = $delivery_cost - $data['total_offer'];
                    $delivery_offer = $delivery_offer->discount_percentage;
                }
            }else{
                return response()->json(['status' => 2, 'message' => __('site.productnotavailable')]);
            } 
        }

        //---احسبي توتال الطلب 
        // ---قارني القيمة تبع الطب مع جدول العروض الخاصة بالتوصيل 
        // لو كان في عرض انقصي قيمة العرض من تكلفة التوصيل الحالية
        // الجزئية دي كلها حطيها تحت 
        }else{
        $delivery_cost = 0;
        $distance = 0;
        $delivery_offer = 0;
        }

// save data in request into table orders
        $order = Order::create([
            'address_id' => $request->address_id,
            'delivery_cost' => $delivery_cost,
            'delivery_distance_in_km' =>$distance,
            'total' => 0,
            'subtotal' => 0,
            'capon_id' => $request->capon_id,
            'bill_number' => 'SOUQ_' . rand(1111, 9999),
            'payment_type' => "CASH",
            'user_id' => Auth::id(),
            'delivery' => $request->delivery,
            'code' => rand(1000, 9999),
        ]);
        $ordersArray = [];
        $ordersForPdf = [];
        $items = Cart::where('user_id', Auth::id())->get();

        
        
        if (isset($items) && !$items->isEmpty()) {
            $total = 0;
            $subtotal = 0;
            $cost = 0;
            $distance = 0;
            // filter all data into carts
            foreach ($items as $product) {
                $shopId = $product['shop_id'];
                $productId = $product['product_id'];
                $ids = Cart_product_option::where('product_id', $productId)->first();
                // if ($ids && $request->delivery == 1) {
                //     $costs = Deliverycalculator::where('cart_id', $ids->cart_id)->sum('cost');
                //     $distances = Deliverycalculator::where('cart_id', $ids->cart_id)->sum('distance');
                // } else {
                //     $costs = 0;
                //     $distances = 0;
                // }
                // $cost += 01;
                // $distance += $distances;
                $variantId = $ids->variant_id ?? '';
                $optionId = $ids->option_id ?? '';
                $qty = $product['quantity'];
                $product_data = Product::where('id', $productId)->first();
                $variant = Variant::where('id', $variantId)->first();
                $option = Option::where('id', $optionId)->first();

                $check_products = ShopProduct::where('shop_id', $shopId)->where('product_id', $productId)->first();
                $shop = Shop::where('id', $shopId)->first();

                if ($check_products->quantity >= $qty) {
                    // if ($product_data->discount_price > 0) {
                    //     $subtot = (($product_data->discount_price + $product_data->variants()) * $qty) / (1 + $shop->vat / 100);
                    //     $sum = ($product_data->discount_price + $product_data->variants()) * $qty;
                    // } else {
                    //     $subtot = (($product_data->price + $product_data->variants()) * $qty) / (1 + $shop->vat / 100);
                    //     $sum = ($product_data->price + $product_data->variants()) * $qty;
                    // }
                    // $total += $sum;
                    // $subtotal += $subtot;
                    if ($product_data->discount_price > 0) {
                        $product_total_price = (($product_data->discount_price + $product_data->variants()) * $qty);
                        $amount_without_vat = $product_total_price / (1 + $shop->vat / 100);
                        $amount_without_commsion = $amount_without_vat / (1 + $shop->commission / 100);
                    } else {
                        $product_total_price = (($product_data->price + $product_data->variants()) * $qty);
                        $amount_without_vat = $product_total_price / (1 + $shop->vat / 100);
                        $amount_without_commsion = $amount_without_vat / (1 + $shop->commission / 100);
                    }
                    $commission = $shop->commission;
                    $commission_value = $amount_without_vat - $amount_without_commsion;
                    $vat = $shop->vat;
                    $vat_value = $product_total_price - $amount_without_vat;
                    $merchant_will_get = $amount_without_commsion + $vat_value;
                    
                    
                    $total += $product_total_price;
                    $subtotal += $product_total_price-$vat_value;
                    
                    // save data into table orderdetails
                    $orders = OrderDetail::create([
                        'product_id' => $productId,
                        'order_id' => $order->id,
                        'quantity' => $qty,
                        'price' => $product_data->price,
                        'discount_price' => $product_data->discount_price ,
                        'vat' => $vat,
                        'vat_value' => $vat_value,
                        'name_ar' => $product_data->name_ar,
                        'name_en' => $product_data->name_en,
                        'image' => $product_data->image,
                        'shop_id' => $shopId,
                        'commsion' => $commission,
                        'commsion_value' => $commission_value,
                        'merchant_will_get' => $merchant_will_get,
                        'delivery_date' => Carbon::now()
                    ]);
// if variants request save into database
                    if (!empty($product['variants'])) {
                        foreach ($product['variants'] as $variantt) {
                            $variantss = Variant::where('id', $variantt['id'])->first();
                            $optionss = Option::where('id', $variantt['option_id'])->first();
                            $ords = Order_products_option::create([
                                'variant_name_ar' => $variantss->name_ar,
                                'order_detail_id' => $orders->id,
                                'variant_name_en' => $variantss->name_en,
                                'variant_id' => $variantt['id'],
                                'option_id' => $variantt['option_id'],
                                'extra_price' => $variantt['extra_price'],
                                'option_name_ar' => $optionss->name_ar,
                                'option_name_en' => $optionss->name_en,
                            ]);
                        }
                    }
                    $user = User::where('shop_id',$shopId)->pluck('id');
                    $client = User::findOrFail($user);
                    //dd($client);
                    if ($client[0]->onesignal_id != null) {
                        $text = __('site.messages.new_order_noti');
                        $title = __('site.new_order');
                        $onesignal_id = $client[0]->onesignal_id;
                        $type = "order";
                        $order_id = $order->id;
                        $id=$client[0]->id;
                        //dd($onesignal_id);
                        //$onesignal_id = "cd94829e-6384-40b4-8399-2732ed21451c";

                        $response = SiteHelper::sendMessage($onesignal_id,$text,$title,'web',$type, $id);
                        $imageid = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
                        $uid = uniqid();

                        Notification::create([
                            'title' => $title,
                            'message' => $text, 
                            'user_id' => $id,
                            'image' => $imageid,
                            'order_id' => $order_id,
                            'type' => "order",
                            'delete' => 0,
                            'show' => 1,
                            'read' => 0,
                        ]);
                        //print_r($response);die();
                    }
                    $check_products->update(['quantity' => $check_products->quantity - $qty]);
                } else {
                    return $this->responseJson(2, __('site.productnotavailable'));
                }

                array_push($ordersArray, $orders);
                array_push($ordersForPdf, $orders);
            }
            
            $shop_ids = [];
            $orders_for_one_shop = [];
            foreach($ordersArray as $order_detail){
                if(in_array($order_detail->shop_id, $shop_ids)){
                    foreach($orders_for_one_shop as $order_for_one_shop){
                        if($order_for_one_shop->shop_id === $order_detail->shop_id){
                            $order_for_one_shop->price += $order_detail->price;
                        }
                    }
                }else{
                    array_push($shop_ids, $order_detail->shop_id);
                    array_push($orders_for_one_shop,$order_detail);
                }
            }

            //return $orders_for_one_shop;
           foreach($orders_for_one_shop as $order_for_one_shop){
               $transaction = new Transaction();
               $transaction->debit = $order_for_one_shop->price;
               $transaction->name_id = 1;
               $transaction->final_balance = $order_for_one_shop->price;
               $transaction->shop_id = $order_for_one_shop->shop_id;
               $transaction->user_id = Auth::user()->id;
               $transaction->transaction_date = Carbon::today();
               $transaction->order_id = $order->id;
               $transaction->save();
           }
            
            
            $col = array_column($ordersForPdf, "shop_id");
            array_multisort($col, SORT_ASC, $ordersForPdf);
// update into table order to total
            // $order->update(['total' => $total, 'subtotal' => $subtotal, 'delivery_cost' => $cost, 'delivery_distance_in_km' => $distance]);
            $order->update(['total' => $total, 'subtotal' => $subtotal, 'delivery_cost' => $delivery_cost, 'delivery_offer' => $delivery_offer]);

            $cartItem = Cart::where('user_id', Auth::id())->first();
            $Deliverycalculator = Deliverycalculator::where('cart_id', $cartItem->id)->delete();

            $deliveryrelation = DB::table('delivery_relation')->where('cart_id', $cartItem->id)->delete();
            $cartoption = Cart_product_option::where('cart_id', $cartItem->id)->first();
            if (!empty($cartoption)) {
                Cart_product_option::where('cart_id', $cartItem->id)->delete();
            }

            Cart::where('user_id', Auth::id())->delete();

            $purchaser = User::find(Auth::id());
            $address = Address::find($order->address_id);
            foreach ($ordersForPdf as $orderPdf) {
                $orderPdf['seller'] = Shop::find($orderPdf->shop_id);
                $orderPdf['product'] = Product::find($orderPdf->product_id);
                $orderPdf['address'] = Address::find($orderPdf->address_id);
                $orderPdf['total_without_tax'] = $orderPdf->price * $orderPdf->quantity;
                $orderPdf['total_tax'] = $orderPdf->total_without_tax * $orderPdf['seller']->vat / 100;
                $orderPdf['total_with_tax'] = $orderPdf->total_without_tax + $orderPdf->total_tax;
                $displayQRCodeAsBase64 = GenerateQrCode::fromArray([
                    new Seller($orderPdf['seller']->name),
                    new TaxNumber($order->bill_number),
                    new InvoiceDate(date('Y-m-d\TH:i:s', strtotime($order->created_at))),
                    new InvoiceTotalAmount(number_format((float)$order->total_with_tax, 2, '.', '')),
                    new InvoiceTaxAmount(number_format((float)$order->total_tax, 2, '.', ''))
                ])->render();

            }
            $dataForPdf1 = [
                'purchaser' => $purchaser,
                'created_at' => $order->created_at,
                'payment_type' => $order->payment_type,
                'orders' => $ordersForPdf,
                'bill_number' => $order->bill_number,
                'sub_total' => $order->subtotal,
                'total' => $order->total,
                'qr' => $displayQRCodeAsBase64,
                'address' => $address,
                'id' => $order->id,
            ];

            $pdf1Path = 'uploads/invoices/order.1.' . time() . '' . rand(11111, 99999) . '.pdf';
            $pdf2Path = 'uploads/invoices/order.2.' . time() . '' . rand(11111, 99999) . '.pdf';

            PDF::loadView('pdf.order1', $dataForPdf1, [], [
                'title' => 'Another Title',
                'margin_top' => 0,
                'default_font_size' => 12,
                'autoArabic' => true
            ])->save($pdf1Path);

            PDF::loadView('pdf.order2', $dataForPdf1, [], [
                'title' => 'Another Title',
                'margin_top' => 0,
                'default_font_size' => 12,
                'autoArabic' => true
            ])->save($pdf2Path);

            $pdf1 = $pdf1Path;
            $pdf2 = $pdf2Path;

            $arr = array("pdf1" => $pdf1,
                "pdf2" => $pdf2);


            if ($order->payment_type == "PAID") {
                array_push($ordersArray, $arr);
                $order->update(['pdf1' => $pdf1,
                        'pdf2' => $pdf2]
                );
            } else {
                $arr = array("pdf1" => NULL,
                    "pdf2" => NULL);
                array_push($ordersArray, $arr);
                $order->update(['pdf1' => NULL,
                        'pdf2' => NULL]
                );
            }

                    
            return $this->responseWithoutMessageJson(1, $ordersArray);
        } else {
            return $this->responseJson(1, __(''));
        }
    }

    /*----------------------------------------------------------
    || Name     : get all total in deliverycost using auth user |
    || Tested   : Done                                          |
    || parameter:                                               |
    || Info     : type                                          |
    -----------------------------------------------------------*/
    public function TotalDeliveryCost(Request $request)
    {
        $user_latitude = $request->latitude;
        $user_longitude = $request->longitude;
        $delivery_calculators = Deliverycalculator::where('user_id', Auth::id())->get();
        //dd($delivery_calculators);
        if (!empty($delivery_calculators)) {
            $total_cost = 0;
            foreach ($delivery_calculators as $delivery_calculator) {
                $shop_id = $delivery_calculator->shop_id;
                $shop_latitude = Shop::find($shop_id)->latitude;
                $shop_longitude = Shop::find($shop_id)->longitude;
                // $lat2 = $delivery_calculator->latitude;
                // $lon2 = $delivery_calculator->longitude;
                if ($user_latitude == $shop_latitude && $user_longitude == $shop_longitude) {
                    $total_cost += $delivery_calculator->cost;
                } else {
                   
                    $distance = round($this->distance($user_latitude, $user_longitude, $shop_latitude, $shop_longitude));
                    $delivery = DeliveryCost::where('min_distance', '<=', $distance)->where('max_distance', '>=', $distance)->first();
                    if ($delivery) {
                        $costs = $distance * $delivery->price;
                        $checked_delivery = Deliverycalculator::where('cart_id', $delivery_calculator->cart_id)->where('user_id', Auth::id())->first();
                        if ($checked_delivery) {
                            $checked_delivery->update(['cost' => $costs, 'distance' => $distance]);
                            $total_cost += $costs;
                        }
                    } else {
                        return $this->responseJson(1, __('site.deliverycostnotavailable'));
                    }
                }
            }
            //dd($total_cost);
            $data['total_cost'] = $total_cost;
            $carts = Cart::where('user_id', Auth::id())->with('variants')->get();
        if (!$carts->isEmpty()) {
            $array = ApiHelper::totalCart();
            if(is_array($array)){
                        $total_cart =  $array['total'];
                        $today = Carbon::today();
                    $cart_offer = DeliveryOffer::where('total_cart','<=',$total_cart)->where('start_date','<=',$today)->where('end_date','>=',$today)->where('status','enable')->first();
            
                    $delivery_offer = DeliveryOffer::where('total_delivery','<=',$total_cost)->where('start_date','<=',$today)->where('end_date','>=',$today)->where('status','enable')->first();
                    
                    if($cart_offer){
                        $data['total_offer'] = $total_cost * $cart_offer->discount_percentage / 100;
                        $data['total_after_offer'] = $total_cost - $data['total_offer'];
                    }elseif($delivery_offer && !$cart_offer){
                        $data['total_offer'] = $total_cost * $delivery_offer->discount_percentage / 100;
                        $data['total_after_offer'] = $total_cost - $data['total_offer'];
        }
            }
                     
        }

        
                // احسب المجموع الخاص بالمنتجات الفي العربة
        //قارن المجموع بعروض التوصيل المتاحة حاليا في جدول عروض التوصيل 
        // في حال وجود عرض للتوصيل انقص العرض من تكلفة التوصيل الحالية
        // ارجاع تكلفة التوصيل ونسبة الخصم في حال وجود خصم 
        //{
        // delivery: 30
        // discount : 10
        // total_delivery : 20
        //}
        
        
        // التعديل التالي حيكون في سيرفيس انشاء الطلب 
            
            return $this->responseWithoutMessageJson(1, $data);
        } else {
            return $this->responseJson(1, __('site.nodataincart'));
        }
    }

    /*-----------------------------------------------------------------------------
    || Name     : get distance bettween user and shop using latitude and longitude |
    || Tested   : Done                                                             |
    || parameter:                                                                  |
    || Info     : type                                                             |
    ------------------------------------------------------------------------------*/
    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 1;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $dist = $dist * 60 * 1.1515 * 1.609344;

            return ($dist);
        }
    }

    /*---------------------------------------------
    || Name     : add carts with items in database |
    || Tested   : Done                             |
    || parameter:                                  |
    || Info     : type                             |
    ----------------------------------------------*/
    public function addCart(Request $request)
    {
        $rule = [
            'products' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $ordersArray = [];
        $notFoundProducts = collect();


        // use filter all request products to save into database
        foreach ($request['products'] as $product) {
            $shopId = $product['shop_id'];
            $productId = $product['product'];
            //Make sure that this product is already added to the cart or not
            $cartProduct = Cart::where('product_id', $productId)
                ->where('shop_id', $shopId)
                ->where('user_id', Auth::id())
                ->first();
            /*
            To verify the existence of the same product and the same variants in
            the database in order to calculate the quantity of the product, whether
            (modifying the previous quantity in addition to the new quantity or using
            the quantity entered by the user)
            */
            if ($cartProduct != null && $cartProduct->exists) {
                $vatsId = [];
                if (isset($product['variants'])) {
                    foreach ($product['variants'] as $vat) {
                        $vatId = $vat['variant_id'];
                        array_push($vatsId, $vatId);
                    }
                }
                //Make sure that one of these variants is registered in Database
                $cartOptions = Cart_product_option::where('product_id', $productId)
                    ->where('cart_id', $cartProduct->id)
                    ->whereIn('variant_id', $vatsId)
                    ->get();
                //In the event that there is the same product and the same variants in the data base, increase the quantity of the product only
                //Otherwise, record the quantity of the product entered from the application
                if (isset($cartOptions) && !$cartOptions->isEmpty()) {
                    $qty = $cartProduct->quantity + $product['quantity'];
                } else {
                    $qty = $product['quantity'];
                }
            } else {
                $qty = $product['quantity'];
            }

            // save all request in table shopproducts
            $shopProduct = ShopProduct::where('shop_id', $shopId)
                ->where('product_id', $productId)
                ->first();
            $lat1 = $request->latitude;
            $lon1 = $request->longitude;
            $lat2 = Shop::find($shopId)->latitude;
            $lon2 = Shop::find($shopId)->longitude;
            $distance = round($this->distance($lat1, $lon1, $lat2, $lon2));
            $cost = DeliveryCost::where('min_distance', '<=', $distance)->where('max_distance', '>=', $distance)->first();
            if ($cost) {
                if ($shopProduct && $shopProduct->quantity >= $qty) {
                    // if conditon true update cart in new value from table carts
                    if (isset($cartOptions) && !$cartOptions->isEmpty()) {
                        $carts = Cart::updateOrCreate(['product_id' => $productId, 'user_id' => Auth::id(), 'shop_id' => $shopId], [
                            'product_id' => $productId,
                            'quantity' => $qty,
                            'user_id' => Auth::id(),
                            'shop_id' => $shopId,
                        ]);

                    } else {
                        $carts = Cart::create([
                            'product_id' => $productId,
                            'quantity' => $product['quantity'],
                            'user_id' => Auth::id(),
                            'shop_id' => $shopId,
                        ]);
                    }
// if variants in request store in database
                    if (isset($product['variants'])) {
                        foreach ($product['variants'] as $variant) {
                            $option_id = Variant::where('id', $variant['variant_id'])->first()->option_id;
                            if (isset($cartOptions) && !$cartOptions->isEmpty()) {
                                $cart = Cart_product_option::updateOrCreate(['product_id' => $productId, 'variant_id' => $variant['variant_id'], 'cart_id' => $carts->id, 'option_id' => $option_id], ['product_id' => $productId, 'option_id' => $option_id, 'cart_id' => $carts->id, 'variant_id' => $variant['variant_id']
                                ]);
                            } else {
                                $cart = Cart_product_option::create(['product_id' => $productId, 'option_id' => $option_id, 'cart_id' => $carts->id, 'variant_id' => $variant['variant_id']]);
                            }
                        }
                    }
                    array_push($ordersArray, $carts);
                } else {
                    $notFoundProducts->push($productId);
                }
            } else {
                return $this->responseJson(1, __('site.deliverycostnotavailable'));
            }
        }
        if (count($notFoundProducts) > 0) {
            return $this->responseJson(1, __('site.productnotavailable'));
        }
        // save delivery calculte into database
        foreach ($ordersArray as $value) {
            $shopchecked = Deliverycalculator::where('user_id', $value->user_id)->where('shop_id', $value->shop_id)->first();
            $mall_id = Shop::find($value->shop_id)->mall_id;
            if ($shopchecked) {
                $dataDelivery = Deliverycalculator::updateOrCreate(['shop_id' => $value->shop_id, 'user_id' => $value->user_id], ['shop_id' => $value->shop_id, 'user_id' => $value->user_id,
                    'latitude' => $request->latitude, 'longitude' => $request->longitude, 'cost' => $distance * $cost->price,
                    'mall_id' => $mall_id, 'cart_id' => $value->id, 'distance' => $distance]);
            } else {
                $deliverychecked = Deliverycalculator::where('user_id', $value->user_id)->where('mall_id', $mall_id)->first();
                if ($deliverychecked) {
                    $dataDelivery = Deliverycalculator::updateOrCreate(['mall_id' => $mall_id, 'user_id' => $value->user_id], ['shop_id' => $value->shop_id, 'user_id' => $value->user_id,
                        'latitude' => $request->latitude, 'longitude' => $request->longitude, 'cost' => $distance * $cost->price,
                        'mall_id' => $mall_id, 'cart_id' => $value->id, 'distance' => $distance]);
                } else {
                    $dataDelivery = Deliverycalculator::create(['shop_id' => $value->shop_id, 'user_id' => $value->user_id,
                        'latitude' => $request->latitude, 'longitude' => $request->longitude, 'cost' => $distance * $cost->price,
                        'mall_id' => $mall_id, 'cart_id' => $value->id, 'distance' => $distance]);
                }
            }
            DeliveryRelation::create(['deliverycalculator_id' => $dataDelivery->id, 'cart_id' => $value->id]);
        }
        return $this->responseWithoutMessageJson(1, $dataDelivery);
    }

    /*---------------------------------------------
    || Name     : delete items from the cart       |
    || Tested   : Done                             |
    || parameter:                                  |
    || Info     : type                             |
    ----------------------------------------------*/
    public function deleteCart(Request $request)
    {
        $rule = [
            'id' => 'required|exists:carts,id',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        // $carts = Cart::where('id', $request->id)->first();
        $relation = DB::table('delivery_relation')->where('cart_id', $request->id)->first();
        $deliveryrelation = DB::table('delivery_relation')->where('cart_id', $request->id)->delete();
        
        
        

        $relationCount = DB::table('delivery_relation')->where('deliverycalculator_id', $relation->deliverycalculator_id)->count();
        if($relationCount == 0)
        $cart = Deliverycalculator::where('id', $relation->deliverycalculator_id)->delete();
        
        
        $cart = Cart::where('id', $request->id)->delete();
        $carts = Cart_product_option::where('cart_id', $request->id)->delete();

        // return $this->responseJson(1, __('site.messages.success'));
        return $this->responseJson(1, __(''));

    }

    /*----------------------------------------------
    || Name     : show item's cart id for auth user |
    || Tested   : Done                              |
    || parameter:                                   |
    || Info     : type                              |
    -----------------------------------------------*/
    public function showCart()
    {
        $carts = Cart::where('user_id', Auth::id())->with('variants')->get();
        if ($carts->isEmpty()) {
            return response()->json(['status' => 1, 'data' => null]);
        } else {
            $array = ApiHelper::totalCart();
            if(is_array($array)){
                        $total_cart =  $array['total'];
                    }
                    else{
                        return response()->json(['status' => 2, 'message' => __('site.productAddfavourirte')]);
                    } 
            $cart = CartResource::collection($array['carts']);

            $data = array("total" => $array['total'], 'subtotal' => $array['subtotal']);
            return response()->json(['status' => 1, 'cart' => $data, 'cart_details' => $cart]);
        }
    }
}
