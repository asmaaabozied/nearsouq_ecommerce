<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletlogResource;
use App\Models\Address;
use App\Models\Wallet;
use App\Models\Walletlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Validator;
use Carbon\Carbon;
use Lang;
use LaravelLocalization;
use App\User;
use DB;
use Mail;
use Illuminate\Support\Facades\Hash;
use App\PasswordReset;
use App\Models\Device;
use App\Helpers\ApiHelper;
use Mailgun\Mailgun;
use App\Models\VerificationCode;
class AuthController extends Controller
{
    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }

    /*------------------------------------------
    || Name     : update profile for Auth User  |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/

    public function updateprofile(Request $request)
    {
        $rule = [
            'email' => 'max:254|email|required',
            'name' => 'required',
            'phone' => 'required|min:9',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        
        }
        $another_user_email = User::where('id','!=',Auth::id())->where('email',$request->email)->get();
        //dd(count($another_user_email));
        if(count($another_user_email)!=0){
            return response()->json(['status' => 422, 'message' => 'email should be unique'], 422);
        }
        $user = User::findorfail(Auth::id());
        if ($request->hasFile('image')) {
            $picture_name = 'uploads/shops' . '/' . time() . str_shuffle('abcdef') . '.' . $request->file('image')->getClientOriginalExtension();
            Image::make($request->file('image'))->save(public_path("$picture_name"));
            $request->request->set('image', $picture_name);
            $user->image = $picture_name;
        }
        $user->name = isset($request->name) ? $request->name : $user->name;
        $user->email = isset($request->email) ? $request->email : $user->email;
        $user->phone = isset($request->phone) ? $request->phone : $user->phone;
        $user->password = bcrypt($request->password);

        $user->save();

        $users = new UserResource($user);

        return $this->responseWithoutMessageJson(1, $users);
    }
    
   /*------------------------------------------
    || Name     : show profile for auth user    |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
     public function showprofile()
    {
        $users = Auth::user();
        $user = new UserResource($users);

        return $this->responseWithoutMessageJson(1,$user);
    }

   /*------------------------------------------
    || Name     : logout for auth user          |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function logout(Request $request)
    {
        //dd($request->input('devices_id'));
        
        $user = auth()->user()->token()->revoke();
        Device::where('device_id',$request->device_id)->where('user_id',Auth::id())->update(['login_status'=>'SIGNOUT']);
        return $this->responseWithoutMessageJson(1,$user);
    }

    /*------------------------------------------
    || Name     : reset password for auth user  |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function send_verification_code(Request $request)
    {
        $rule = [
            //'email' => 'required|email',
            'operation' => 'required',
            'email' => 'required_without:phone',
            'phone' => 'required_without:email',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $waiting_seconds = time() + 60;
        $time_left = $waiting_seconds - time();
        if(isset($request->email)){
            $user = User::where('email', $request->email)->where('email','!=',NULL)->first();
        }elseif(isset($request->phone)){
            $user = User::Where('phone',$request->phone)->where('phone','!=',NULL)->first();
        }
        //dd(env('APP_ENV'));
        //dd($user);
        if ($user) {
            $code = rand(1111, 9999);
            //$update = $user->update(['code' => $code]);

                $sms = __('site.messages.user_Verified') . $code;
                
                if(isset($request->email)){
                    $verification_code = VerificationCode::where('email',$request->email)->first();
                }elseif(isset($request->phone)){
                    $verification_code = VerificationCode::where('email',$request->phone)->first();
                }
//dd($verification_code);
                if($verification_code != NULL){
                    //dd(Carbon::parse( $verification_code->created_at )->diffInDays( Carbon::now() ));
                    if(Carbon::parse( $verification_code->created_at )->diffInDays( Carbon::now() ) < 1){
                        if($verification_code->attempt_count + 1 > 3){
                            return $this->responseJson(0, __('site.messages.try_after_some_times'));
                        }
                        //dd('here');
                        $new_attempt_count = $verification_code->attempt_count+1;
                        $sec = $time_left;
                        $verification_code->update(['attempt_count'=> $new_attempt_count]);
                    }
                    $verification_code->update(['code'=>$code, 'created_at'=>Carbon::now()]);
                }
                

                if($verification_code == NULL){
                    $verification_code = new VerificationCode();
                    $verification_code->email = $request->email;
                    $verification_code->phone = $request->phone;
                    $verification_code->attempt_count = 1;
                    $verification_code->code = $code;
                    $verification_code->save();
                    $sec = $time_left;
                }

                if($request->operation === 'EMAIL'){
                    ApiHelper::sendEmail($user->email, 'Verfication Code', $sms);
                }elseif($request->operation === 'SMS'){
                    $this->sendSms($user->phone, $sms);
                }

                $data = array("code" => $code, "time_left" => $time_left);

                return $this->responseJson(1,NULL, $data);
            
        } else {
            return $this->responseJson(0, __('site.messages.invaliddata'));
        }
    }

    /*------------------------------------------
    || Name     : reset password for auth user  |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function resetpassword(Request $request)
    {
        $rule = [
            'code' => 'required',
            'new_password' => 'required|min:6',
            'email' => 'required_without:phone',
            'phone' => 'required_without:email',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        if(isset($request->email)){
            $user = VerificationCode::where('email', $request->email)->where('email','!=',NULL)->first();
        }elseif(isset($request->phone)){
            $user = VerificationCode::Where('phone',$request->phone)->where('phone','!=',NULL)->first();
        }
        if($user){
            if ($user->code == $request->code) {
                if(Carbon::parse( $user->created_at )->diffInSeconds( Carbon::now() ) < 60){
                    $user->fill([
                        'password' => Hash::make($request->new_password)
                    ])->save();
                    return $this->responseJson(1, __(''));
                }else{
                    return $this->responseJson(0, __('site.messages.user_codeExpired'));
                }
                
            } else {
                return $this->responseJson(0, __('site.messages.user_codeInvalid'));
            }
        }
    }

    /*------------------------------------------
    || Name     : send sms for reset password   |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function sendSms($phone, $body)
    {
        if ($phone == NULL || $body == NULL) {
            return response(['error' => '1', 'msg' => 'Please enter all mandatory fields']);
        }
        $body = urlencode($body);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://basic.unifonic.com/wrapper/sendSMS.php?userid=nearsouq2@gmail.com&password=nearsouq123AA1$&msg=$body&to=$phone&sender=Near%20Souq&encoding=UTF8");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $headers = array();
        $headers[] = "Accept: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = 'Error:' . curl_error($ch);
            return response(['error' => '1', 'msg' => $result]);
        }
        curl_close($ch);
        return response(['error' => '0', 'msg' => $result]);
    }

    /*------------------------------------------
    || Name     : change password               |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function changepassword(Request $request)
    {
        $user = Auth::user();
        $rule = [
            'old_password' => 'required',
            'new_password' => 'required|different:password|min:6',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        if (Hash::check($request->input('old_password'), $user->password)) {
            $user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();
            return $this->responseJson(1, __('site.messages.resetpassword'));
        } else {
            return $this->responseJson(0, __('site.messages.error'));
        }
    }

    /*------------------------------------------
    || Name     : register in application       |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function register(Request $request)
    {
        $rule = [
            'email' => 'max:254|unique:users|email|required',
            'name' => 'required',
            'phone' => 'required|min:9',
            'password' => 'required|min:6',
            'c_password' => 'required_with:password|same:password',
            'type' => 'required',
            'device_id' => 'required',
            'brand_name' => 'required',
            'platform' => 'required',
            'version_no' => 'required'
        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

          //  return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
         //$last_name = explode(" ", $request->name);
        $user = User::create([
            'name' => $request->name,
           // 'last_name' => $last_name[1] ?? NULL,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            // 'type' => $request->type,
            'status' => 1,
        ]);
        
        
        if($request->type='Delivery')
    
        {
             $role = [
                    "6"
                ];

        }else{
            $role = [
                    "12"
                ];
            
            
        }
        
        $user->syncRoles($role);

        
        $user->save();

            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'status' => 1,
            ]);
        if(isset($request->onesignal_id) && isset($request->_token)){
            $user->onesignal_id = $request->onesignal_id;
            $user->_token = $request->_token;
            $user->save();
        }
        $token = $user->createToken('MyApp')->accessToken;

        $device = Device::where('device_id',$request->device_id)->where('user_id',$user->id)->first();
        $oauth_access_token = DB::table('oauth_access_tokens')->where('user_id',$user->id)->latest()->limit(1)->first();
        //dd($oauth_access_token->id);
        if($device){
            $device->update(['oauth_access_tokens_id'=>$oauth_access_token->id,'user_id'=>$user->id, 'login_status'=>'LOGIN', 'last_login_date'=>Carbon::today()]);
        }else{
            ApiHelper::newDevice($request->brand_name,$oauth_access_token->id,$request->platform,$user->id,$request->device_id,'LOGIN',$request->onesignal_id, $request->version_no);
             }
        return $this->responseWithoutMessageJson(1,
            ['token' => $token, 'data' => $user]
        );
    }

    /*--------------------------------------------
    || Name     : signIn with email and password  |
    || Tested   : Done                            |
    || parameter:                                 |
    || Info     : type                            |
    ---------------------------------------------*/
    public function login(Request $request)
    {
        $rule = [
            'email' => 'required',
            'password' => 'required',
            'device_id' => 'required',
            'brand_name' => 'required',
            'platform' => 'required',
            'version_no' => 'required'
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $password = $request->password;
        $email = $request->email;
        // print_r($password);die();
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            if(isset($request->onesignal_id) && isset($request->_token)){
                $user->onesignal_id = $request->onesignal_id;
                $user->_token = $request->_token;
                $user->status = 1;
                $user->save();
            }
            if($user->hasRole('Delivery')){
                Device::where('user_id',$user->id)->where('device_id','!=',$request->device_id)->delete();
            }
            $other_devices = Device::where('user_id',$user->id)->where('login_status','LOGIN')->where('device_id','!=',$request->device_id)->get();


            $users = new UserResource($user);
            $token = $user->createToken('MyApp')->accessToken;
            $device = Device::where('device_id',$request->device_id)->where('user_id',$user->id)->first();
            //dd($device);
        $oauth_access_token = DB::table('oauth_access_tokens')->where('user_id',$user->id)->latest()->limit(1)->first();
            if($device){
                $device->update(['oauth_access_tokens_id'=>$oauth_access_token->id,'user_id'=>$user->id, 'login_status'=>'LOGIN', 'last_login_date'=>Carbon::today(),'one_signal_id'=>$request->onesignal_id]);
            }else{
                ApiHelper::newDevice($request->brand_name,$oauth_access_token->id,$request->platform,$user->id,$request->device_id,'LOGIN',$request->onesignal_id, $request->version_no);
                 }
            return $this->responseWithoutMessageJson(1,['token' => $token, 'user' => $users, 'other_devices' => $other_devices]
            );
        } else {
            return $this->responseJson(0, __('site.messages.user_loginInvalid'));
        }
    }

    /*------------------------------------------
    || Name     : add address to auth user      |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function addAddress(Request $request)
    {
        $rule = [
            'first_name' => 'required',
            'phone' => 'required|min:10',
            'longitude' => 'required',
            'latitude' => 'required',
            'default_address' => 'required'
        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        //================Delete previous default addresses====================
        if($request->default_address === "TRUE"){
            $values = Address::where('default_address',  '=', "TRUE")->where('user_id', '=', Auth::id())->update(['default_address'=>"FALSE"]);
        }

        $data = Address::create([
            'user_id' => Auth::id(),
            'first_name' =>  $request->first_name,
            'comment' => $request->comment,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'street' => $request->street,
            'type' => $request->type,
            'phone' => $request->phone,
            'neighborhood' => $request->neighborhood,
            'default_address'=> $request->default_address
        ]);
        $user = Auth::user();
        $user->update(['address'=>$request->first_name.' - '.$request->last_name. ' - '.$request->comment, 'longitude'=>$request->longitude, 'latitude'=>$request->latitude]);

        return $this->responseWithoutMessageJson(1,$data);
    }

    /*------------------------------------------
    || Name     : update address for auth user  |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function updateAddress(Request $request)
    {
        $rule = [
            'address_id' => 'required',
            'first_name' => 'required',
            'phone' => 'required|min:10',
            'longitude' => 'required',
            'latitude' => 'required',
            'default_address' => 'required'
        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        //================Delete previous default addresses====================
        if($request->default_address === "TRUE"){
            $values = Address::where('default_address',  '=', "TRUE")->where('user_id', '=', Auth::id())->update(['default_address'=>"FALSE"]);
        }

        $data = Address::updateOrCreate(['user_id' => Auth::id(),
            'id'=> $request->address_id], [
            'user_id' => Auth::id(),
            'first_name' =>  $request->first_name,
            'comment' => $request->comment,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'street' => $request->street,
            'type' => $request->type,
            'phone' => $request->phone,
            'neighborhood' => $request->neighborhood,
            'default_address'=> $request->default_address
        ]);

        return $this->responseWithoutMessageJson(1,$data);
    }

    /*---------------------------------------------
    || Name     : show all addresses to auth user  |
    || Tested   : Done                             |
    || parameter:                                  |
    || Info     : type                             |
    ----------------------------------------------*/
    public function showAddress()
    {
        $addresses = Address::where('user_id', Auth::id())->with('user')->get();
        $addresse = AddressResource::collection($addresses);

        return $this->responseWithoutMessageJson(1,$addresse);
    }

    /*------------------------------------------
    || Name     : delete address from addresses |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function deleteAddress(Request $request)
    {
        $rule = [
            'address_id' => 'required',
        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $deleted = Address::where('id', $request->address_id)->delete();
        if($deleted){
             return $this->responseWithoutMessageJson(1,$deleted);
        }else{
               return $this->responseWithoutMessageJson(1,$deleted);
        }
    }
    
    /*------------------------------------------
    || Name     : delete account for auth user  |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function deleteAccount(Request $request){
        $user = Auth::user();
        $rule = [
            'password' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        if (Hash::check($request->input('password'), $user->password)) {
        $deleted = User::where('id', Auth::id())->delete();
        }else{
            return $this->responseJson(0, __('site.messages.error'));
        }
        if($deleted){
             return $this->responseWithoutMessageJson(1,$deleted);
        }else{
               return $this->responseWithoutMessageJson(1,$deleted);
        }
    }
    /*------------------------------------------
    || Name     : get wallet of auth user       |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function getWallet(Request $request){
        $wallet = Wallet::where('user_id', Auth::user()->id)->where('status',1)->first();
        $walletlogs = Walletlog::where('user_id',Auth::user()->id)
        ->join('payment_methods', 'walletlogs.payment_method','=','payment_methods.id')
        ->select('walletlogs.*','payment_methods.name_'. app()->getLocale() .' as payment_method')
        ->paginate(10);
        $wallet['logs'] = WalletlogResource::collection($walletlogs);
        return response()->json(['status' => 1, 'message' => __(''), 'wallet' => $wallet]);
    }

    public function deleteSessions(Request $request){
        if($request->input('delete_all') == 1){
            $devices = Device::where('user_id',Auth::id())->delete();
            $tokens = DB::table('oauth_access_tokens')->where('user_id',Auth::id())->delete();
        }
        if($request->input('delete') == 1){
            foreach($request->input('devices_id') as $device_id){
                //dd( Auth::id());
                $device = Device::where('device_id',$device_id)->where('user_id',Auth::id())->first();
                //dd($device);
                DB::table('oauth_access_tokens')->where('id',$device->oauth_access_tokens_id)->delete();
                Device::where('device_id',$device_id)->where('user_id',Auth::id())->delete();
            }
        }

        return $this->responseWithoutMessageJson(1,'success');
    }

    function getDaysBetween2Dates(DateTime $date1, DateTime $date2, $absolute = true)
    {
        $interval = $date2->diff($date1);
        // if we have to take in account the relative position (!$absolute) and the relative position is negative,
        // we return negatif value otherwise, we return the absolute value
        return (!$absolute and $interval->invert) ? - $interval->days : $interval->days;
    }
   
}
