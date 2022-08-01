<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ShopDatatables;

use App\DataTables\MallDatatables;
use App\Models\Mall;
use App\Models\category;
use App\Models\Notification;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Database\QueryException;
use App\Helpers\SiteHelper;
use DB;
use Response;
use App\Models\Device;

class NotificationController extends Controller
{
    public function index()
    {

    }//end of index
    
    public function changenotificationsstatus(Request $request){
        
       
        $notifications = Notification::get();
        foreach($notifications as $notification){
            $notification->update(['status'=>'TRUE']);
        }
        
         return response()->json(['success' =>true,], 200);
    }

    public function create()
    {
        $users = User::all();
        return view('dashboard.notifications.create', compact('users'));
    }//end of create

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'user_id' => 'required',
            //'all' => 'required',
        ]);

        $request_data = $request->all();
        //dd($client);
        $text = $request_data['message'];
        $title = $request_data['title'];
        $type = "NOTIFY";
        $environment = "mobile";
        
        //dd('hi');
        foreach ($request_data['user_id'] as $user) {
            $user = User::find($user);
             $imageid = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
                $uid = uniqid();
                $notification = Notification::create([
                    'title' => $title,
                    'message' => $text,
                    'type' => $type,
                    'user_id' => $user->id,
                    //'image' => $imageid,
                    'delete' => 0,
                    'show' => 1,
                    'read' => 0,
                ]);
                if ($request->hasFile('image')) {
        
                UploadImage('uploads/notifications',$notification,$request);
                    
                }
            $onesignal_ids = Device::where('user_id',$user->id)->get();
            //dd($onesignal_ids);
            if (count($onesignal_ids) > 0) {
                //dd($user->onesignal_id);
                foreach($onesignal_ids as $onesignal_id){
                    if(isset($onesignal_id->one_signal_id)){
                        //dd($onesignal_id->one_signal_id);
            $response = SiteHelper::sendMessage($onesignal_id->one_signal_id, $text, $title, $environment, $type, $user->id, url('uploads/notifications/'.$notification->image));
            //dd($response);
                    }
            }
        }
        }
        flash(__('site.notified_successfully'))->success();

//        session()->flash('success', __('site.notified_successfully'));
        return redirect()->route('dashboard.welcome');
    }//end of store

    public function edit($id)
    {

    }//end of edit

    public function update(Request $request)
    {

    }

    public function destroy($id)
    {

    }

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }

    function readNotification(){
        // $notifications = Notification::where('user_id',Auth::user()->id)
        // ->where('delete',0)
        // ->orderBy('read','DESC')
        // ->get();
           $notifications = Notification::where('delete',0)
        ->orderBy('read','DESC')
        ->get();
        foreach($notifications as $notification){
            $notification->update(['read'=>1]);
              $notification->update(['status'=>'TRUE']);
        }
        $notification_count = Notification::where('user_id',Auth::user()->id)->where('read',0)->where('delete',0)->count();
        $data['notification_count'] = $notification_count;
        session(['notification_count' => $notification_count, 'notifications'=> $notifications]);

        return $data;
    }

function saveOneSigalId(Request $request){
        $user_device = new Device();
        $user_device->one_signal_id = $request->oneSignalID;
        $user_device->login_status = 'LOGIN';
        $user_device->user_id = Auth::id();
        $user_device->last_login_date = Carbon::today();
        $user_device->platform = 'WEB';
        
        $user_device->save();
        return $user_device;
    }
}//end of controller
