<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Http\Resources\VersionResource;
use App\Models\Setting;
use App\Models\Versions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class SettingController extends Controller
{
    /*----------------------------------------------------
    || Name     : get setting to version in applications  |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function getSetting(Request $request)
    {
        $build_no = $request->input('build_no');
        $type = $request->input('type');
        $os = $request->input('os');
        $ip = \request()->ip();
        $data = Location::get($ip);
        if (!empty($build_no && $type && $os)) {
            $setting = Setting::where('type', $type)
                ->where('status',1)
                ->orderBy('created_at')
                ->get();
            $versions = Versions::where('build_no', $request->build_no)
                ->where('type', $request->type)
                ->where('os',$request->os)
                ->whereDate('expiry_date', '>', Carbon::now())
                ->first();
            foreach($setting as $set){
                if($set->param === 'splash_screen_image' && str_contains($set->value ,'http' ) !== true ){
                    if($set->value != null){
                    $set->value = asset('uploads/splash_screen/' . $set->value);
                    }
                }
            }
            $version = new VersionResource($versions);
            $settings = SettingResource::collection($setting);
            if ($versions) {
                return response()->json(['status' => 1,'versionInfo' => $version, 'settings' => $settings,'locationUser'=>$data]);
            } else {
                return response()->json(['status' => 1,'versionInfo' => $version, 'settings' => $settings,'locationUser'=>$data]);
            }
        }else{
            return response()->json(['status' => 0, 'message' => __('site.messages.error')]);
        }
    }

     public function ipInfo($ip = NULL, $purpose = "location", $deep_detect = TRUE)
     {
         $output = NULL;
         if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
             $ip = $_SERVER["REMOTE_ADDR"];
             if ($deep_detect) {
                 if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                 if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                     $ip = $_SERVER['HTTP_CLIENT_IP'];
             }
         }
         // $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
         $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
         $continents = array(
             "AF" => "Africa",
             "AN" => "Antarctica",
             "AS" => "Asia",
             "EU" => "Europe",
             "OC" => "Australia (Oceania)",
             "NA" => "North America",
             "SA" => "South America"
         );
         if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
             $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
             if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                 switch ($purpose) {
                     case "location":
                         $output = array(
                             "city"           => @$ipdat->geoplugin_city,
                             "state"          => @$ipdat->geoplugin_regionName,
                             "country"        => @$ipdat->geoplugin_countryName,
                             "country_code"   => @$ipdat->geoplugin_countryCode,
                             "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                             "continent_code" => @$ipdat->geoplugin_continentCode,
                         );
                         break;
                     case "address":
                         $address = array($ipdat->geoplugin_countryName);
                         if (@strlen($ipdat->geoplugin_regionName) >= 1)
                             $address[] = $ipdat->geoplugin_regionName;
                         if (@strlen($ipdat->geoplugin_city) >= 1)
                             $address[] = $ipdat->geoplugin_city;
                         $output = implode(", ", array_reverse($address));
                         break;
                     case "city":
                         $output = @$ipdat->geoplugin_city;
                         break;
                     case "state":
                         $output = @$ipdat->geoplugin_regionName;
                         break;
                     case "region":
                         $output = @$ipdat->geoplugin_regionName;
                         break;
                     case "country":
                         $output = @$ipdat->geoplugin_countryName;
                         break;
                     case "countrycode":
                         $output = @$ipdat->geoplugin_countryCode;
                         break;
                 }
             }
         }
         return $output;
     }
}
