<?php

namespace App\Http\Controllers\api\v1\VendorApp;


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
        Device::where('device_id', $request->device_id)->where('user_id', Auth::id())->update(['login_status' => 'SIGNOUT']);
        return $this->responseWithoutMessageJson(1, $user);
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
            if (isset($request->onesignal_id) && isset($request->_token)) {
                $user->onesignal_id = $request->onesignal_id;
                $user->_token = $request->_token;
                $user->status = 1;
                $user->save();
            }
            $other_devices = Device::where('user_id', $user->id)->where('login_status', 'LOGIN')->where('device_id', '!=', $request->device_id)->get();


            $users = new UserResource($user);
            $token = $user->createToken('MyApp')->accessToken;
            $device = Device::where('device_id', $request->device_id)->where('user_id', $user->id)->first();
            //dd($device);
            $oauth_access_token = DB::table('oauth_access_tokens')->where('user_id', $user->id)->latest()->limit(1)->first();
            if ($device) {
                $device->update(['oauth_access_tokens_id' => $oauth_access_token->id, 'user_id' => $user->id, 'login_status' => 'LOGIN', 'last_login_date' => Carbon::today(), 'one_signal_id' => $request->onesignal_id]);
            } else {
                ApiHelper::newDevice($request->brand_name, $oauth_access_token->id, $request->platform, $user->id, $request->device_id, 'LOGIN', $request->onesignal_id, $request->version_no);
            }

            $shops = auth()->user()->shops;
            return $this->responseWithoutMessageJson(1, ['token' => $token, 'user' => $users, 'other_devices' => $other_devices, 'shops' => $shops]
            );
        } else {
            return $this->responseJson(0, __('site.messages.user_loginInvalid'));
        }
    }


    /*------------------------------------------
    || Name     : delete address from addresses |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/


}
