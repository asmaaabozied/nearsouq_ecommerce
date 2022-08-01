<?php


namespace App\Helpers;


use App\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\Favorite;
use DB;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Carbon\carbon;
use Mail;
use App\Models\Wallet;
use App\Models\Walletlog;
use Mailgun\Mailgun;
use App\Models\OrdersHistory;
use App\Models\CustomerTransaction;

class ApiHelper
{
    public static function totalCart(){
        $carts = Cart::where('user_id', Auth::id())->with('variants')->get();
            $total = 0;
            $subtotal = 0;
            $sum =0;
            $not_found_products = [];
            foreach ($carts as $cart) {
                $productId = $cart['product_id'];
                $product = Product::where('id', $productId)->first();
                $shop = Shop::where('id', $cart['shop_id'])->first();
                $quantity = $cart['quantity'];
                $old_quantity = Cart::where('product_id',$productId)->where('shop_id',$cart['shop_id'])->where('user_id',Auth::id())->where('id','!=',$cart['id'])->sum('quantity');
                $check_products = ShopProduct::where('shop_id', $cart['shop_id'])->where('product_id', $productId)->first();
                if ($check_products->quantity >= $quantity+$old_quantity) {
                    if ($product->discount_price > 0) {
                        $subtot = (($product->discount_price + $product->variants()) * $quantity) / (1 + $shop->vat / 100);
                        $sum = ($product->discount_price + $product->variants()) * $quantity;
                    } else {
                        $subtot = (($product->price + $product->variants()) * $quantity) / (1 + $shop->vat / 100);
                        $sum = ($product->price + $product->variants()) * $quantity;
                    }
                    $total += $sum;
                    $subtotal += $subtot;
                } else {
                    array_push($not_found_products,$cart->id);
                }
                
            }
            //dd($carts);
            
             //delete items from cart----
             //delete from delvery rel cal
              //change message.
            $data = array("carts"=> $carts, "total"=> $total, "subtotal"=>$subtotal);
            if(!empty($not_found_products) || count($carts) == NULL){
                $not_found_carts = Cart::whereIn('id', $not_found_products)->get();
                //dd($not_found_carts);
                foreach($not_found_carts as $not_found_cart){
                    if(Favorite::where('product_id',$not_found_cart->product_id)->where('user_id', Auth::id())->first() == NULL){
                        Favorite::create(['product_id' => $not_found_cart->product_id, 'user_id' => Auth::id()]);
                    }
                }
                Cart::whereIn('id', $not_found_products)->delete();
                $deliveryrelation = DB::table('delivery_relation')->whereIn('id', $not_found_products)->delete();
                $data['IN_FAVORITE'] = "IN_FAVORITE";
                $carts = Cart::where('user_id', Auth::id())->with('variants')->get();
                $data['carts'] = $carts;
                //return response()->json(['status' => 2, 'message' => __('site.productAddfavourirte')]);
            }else{
                $data['IN_FAVORITE'] = NULL;
            }

            return $data;
        }
    
            public static function newDevice($brand_name,$oauth_access_token_id,$platform,$user_id,$device_id,$status,$onesignal_id, $version_no){
            $device = new Device();
            $device->brand_name = $brand_name;
            $device->oauth_access_tokens_id = $oauth_access_token_id;
            $device->platform = $platform;
            $device->user_id = $user_id;
            $device->last_login_date = Carbon::today();
            $device->device_id = $device_id;
            $device->login_status = $status;
            $device->one_signal_id = $onesignal_id;
            $device->version_no = $version_no;

            $device->save();
        }
    
    public static function sendEmail($email, $subject, $body){
       //$mgClient = new Mailgun('b7a3089f0f246544e732797cc0a7c41e-4f207195-53299904');
       $data = array('body'=>$body, 'email'=>$email, 'subject'=>$subject);
       $data = [
        'subject' => $subject,
        'body' => $body
    ];
      // dd($email);

         //$response = \Mail::to($email)->send(new \App\Mail\Verification($data));
         //dd($response);
          // return $response;
          
           /* $domain = "sandboxc0fc433562aa4f6384b8adfb344b6110.mailgun.org";
            $result = $mgClient->sendMessage($domain, array(
            	'from'	=> 'postmaster@sandboxc0fc433562aa4f6384b8adfb344b6110.mailgun.org',
            	'to'	=> $email,
            	'subject' => 'Hello',
            	'text'	=> 'Testing some Mailgun awesomness!'
            ));*/
            
           // dd($result);
    }

    public static function addToWallet($user_id , $new_balance, $message, int $order_id = NULL, int $order_detail_id = NULL){
        $wallet = Wallet::where('user_id',$user_id)->first();
        if($wallet){
            $total_balance = $wallet->balance + $new_balance;
            $wallet->update(['balance'=>$total_balance]);
        }else{
            $wallet = new Wallet();
            $wallet->user_id = $user_id;
            $wallet->balance = $new_balance;
            $wallet->status = 1;

            $wallet->save();
        }
        $wallet_log = new Walletlog();

        $wallet_log->amount = $new_balance;
        $wallet_log->user_id = $user_id;
        $wallet_log->comment = trans($message). ($order_detail_id ? $order_detail_id : $order_id);
        $wallet_log->operation = 'DEPOSIT';

        $wallet_log->save();
    }
    
    public static function addToOrderHistory($order_id, $order_details_id, $shop_id, $product_id, $status, $user_id){
        $ordersHistory = new OrdersHistory();

        $ordersHistory->order_id = $order_id;
        $ordersHistory->order_details_id = $order_details_id;
        $ordersHistory->shop_id = $shop_id;
        $ordersHistory->product_id = $product_id;
        $ordersHistory->status = $status;
        $ordersHistory->user_id = $user_id;
        $ordersHistory->processed_id = DB::table('role_user')->where('user_id',$user_id)->value('role_id');

        //dd($ordersHistory->processed_id);
        $ordersHistory->save();
    }
    
        public static function createCustomerTrans($shop_id, $order_id, $orderdetails_id, $captain_id, $user_id, $payment_type, $payment_name, $amount){
        $customer_trans = new CustomerTransaction();
        $customer_trans->shop_id = $shop_id;
        $customer_trans->order_id = $order_id;
        $customer_trans->order_details_id = $orderdetails_id;
        $customer_trans->captain_id = $captain_id;
        $customer_trans->user_id = $user_id;
        $customer_trans->payment_type = $payment_type;
        $customer_trans->payment_name = $payment_name;
        $customer_trans->amount = $amount;
        
        $customer_trans->save();
    }
}
