<?php

use Intervention\Image\Facades\Image;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\Mail;
use App\Mail\Verification;
//use Hash;
use Carbon\Carbon;
define('Paginate_number',10);
define('Limit',10);
define('Maps' , ['create', 'read', 'update', 'delete']);
define('Mapss' , ['read']);


function UploadImage($path,$model,$request){

  $image = $request->file('image');
    $filenames = $image->getClientOriginalName();
    $mytime =Carbon::now();
    //   $filename = $mytime->toDateTimeString()."_".$filenames->hashName();

     $filename = "_".md5($filenames);
    //Fullsize
    $image->move(base_path() . '/'.$path.'/', $filename);

    $image_resize = Image::make(base_path() . '/'.$path.'/' . $filename);
    // $image_resize->resize(1080, 1080);
    // $image_resize->insert(base_path('/images/logo.png'), 'bottom-right', 2, 2)->save(base_path($path.'/' . $filename));
    $model->image = $filename;
    $model->save();
}


function validationErrorsToString($errArray) {
    $valArr = array();
    foreach ($errArray->toArray() as $key => $value) {
        $newVal=(isset($valArr[$value[0]]))?$valArr[$value[0]].',' :'';
        $key=__('validation.attributes.'.$key);
        $valArr[$value[0]]=(!empty($valArr[$value[0]]))? $newVal.$key:$key ;

    }
    if(!empty($valArr)){
        $errorArr=array();
        foreach ($valArr as $errorMsg => $attributes) {

            $errorArr[]=__('validation.attributes.field')." (".$attributes.") ".$errorMsg;
        }
        $errStrFinal = implode(',', $errorArr);
    }
    return $errStrFinal;
}



function createTopic($topic,$id) {
  $url = 'https://iid.googleapis.com/iid/v1/'.$id.'/rel/topics/'.$topic;
  $key = env("FIREBASE_SERVER_KEY");
  $headers = array('Authorization: key=' . $key,'Content-type: Application/json','Content-Length: 0');
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_POST, true );
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
  $result = curl_exec ( $ch );
  curl_close ( $ch );
  return $result;
}

function removeFileByPath($path){
    try {unlink($path);}
    catch ( \Throwable $e ) {error_log("try to delete Image : ".$e);}
    // try { File::delete($path);}
    //catch ( \Throwable $e ) {error_log("try to delete File : ".$e);}

}//end of removeFile

?>
