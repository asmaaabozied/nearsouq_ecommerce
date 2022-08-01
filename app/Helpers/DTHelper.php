<?php


namespace App\Helpers;

use App\Models\OrderDetail;
use App\Models\Wallet;
use App\User;
class DTHelper
{

    public static function dtImageButton($image)
    {


        $html = <<< HTML

    <img src="{{asset('uploads/shops/products/'.$image->image)}}" border="0" width="10" class="img-rounded" align="center" />

HTML;

        return $html;

    }


    public static function dtEditButton($link, $title, $permission)
    {

//        if (auth()->user()->hasPermission($permission)) {

        $html = <<< HTML
 <a href="$link" class="update" > <i class="far fa-edit me-1 fa fa-1x"></i> </a>
HTML;


        return $html;
        }

//    }

    public static function dtShowshopButton($link, $title, $permission)
    {


 
        if (auth()->user()->hasPermission($permission)) {

        $html = <<< HTML
 <a href="$link" class="update" > <i class="far fa-file me-1 fa fa-1x"></i> </a>
HTML;


        return $html;
        }
          
     
    }
    public static function dtAddressButton($link, $title, $permission)
        {
    
    //        if (auth()->user()->hasPermission($permission)) {
    
            $html = <<< HTML
     <a href="$link" > <i class="fa fa-map-marker me-1 fa fa-1x"></i></a>
    HTML;
    
    
            return $html;
            }


        public static function dtDownloadButton($link, $title, $permission)
        {
        
            $html = <<< HTML
     <a href="$link" > <i class="far fa-download me-1 fa fa-1x" download></i></a>
    HTML;
    
    
            return $html;
            }
            
            public static function dtHistoryButton($link, $title, $permission)
        {
    
    //        if (auth()->user()->hasPermission($permission)) {
    
            $html = <<< HTML
     <a href="$link" > <i class="fa fa-history me-1 fa fa-1x"></i></a>
    HTML;
    
    
            return $html;
            }

    public static function dtPopButton($link, $title, $permission)
    {

    $balance = trans('site.chanagebalance');
//        if (auth()->user()->hasPermission($permission)) {
        $data = Wallet::where('id', $link)->first();

        $html = <<< HTML

     <button type="button" class="btn btn-primary data" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="getdata($data->balance,$data->id)">
                           $balance
                        </button>
HTML;


        return $html;
//        }

    }

    public static function dtPopButtonProduct($link, $title, $permission)
    {

        $balance = trans('site.chanagebalance');
//        if (auth()->user()->hasPermission($permission)) {
//        $data = Wallet::where('id', $link)->first();

        $html = <<< HTML

     <button type="button" class="data" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="getdata($link)">
                     <i class="far fa-eye me-1 fa fa-1x"></i>
                        </button>
HTML;


        return $html;
//        }

    }

    public static function dtBlockActivateButton($link, $status, $permission)
    {
        if ($status == 1) {
            $active = "fas fa-check-circle fa fa-1x";

        } else {
            $active = "far fa-ban fa fa-1x";


        }

        $csrf = csrf_field();
        $method_field = method_field('POST');
        $classType = ($status) ? "btn-warning" : "btn-default";
        $iconName = ($status) ? "fa-ban" : "fa-user";
        if($status=="TRUE") {
//            if (auth()->user()->hasPermission($permission)) {
            $html = <<< HTML

<a href="$link" class="update">

 <i class="fas fa-check-circle fa fa-1x"></i>

</a>

HTML;
        }
        else{
            $html = <<< HTML

<a href="$link" class="update">

 <i class="far fa-ban fa fa-1x"></i>

</a>

HTML;

        }
        return $html;
//        }

    }

    public static function dtDeleteButton($link, $title, $permission, $id)
    {


        $csrf = csrf_field();
        $method_field = method_field('delete');
        if (auth()->user()->hasPermission($permission)) {

        $html = <<< HTML
  <form action="$link" method="post" style="display: inline-block" id="deleteForm$id">
$csrf
$method_field
<button type="button" onclick="confirmDelete($id)" id="delete" class="delete" style="border: none;
    background: transparent;">
<i class="far fa-trash-alt me-1 fa-1x delete"></i>
</button>
</form>
HTML;


        return $html;
        }

    }

    public static function dtDeleteButtondisabled($link, $title, $permission)
    {


        $csrf = csrf_field();
        $method_field = method_field('delete');
        if (auth()->user()->hasPermission($permission)) {

        $html = <<< HTML
 <form action="$link" method="post" style="display: inline-block">
$csrf
$method_field
<button type="submit" id="delete" class="delete" style="border: none;
    background: transparent;" disabled>
<i class="far fa-trash-alt me-1 fa-1x delete"></i>
</button>
</form>
HTML;


        return $html;
        }

    }

    public static function dtShowButton($link, $title, $permission)
    {

//        if (auth()->user()->hasPermission($permission)) {

        $html = <<< HTML
 <a href="$link" > <i class="far fa-eye me-1 fa fa-1x"></i></a>
HTML;


        return $html;
        }

//    }

    public static function dtStatus($link, $title, $permission, $id)
    {
        //dd($link);

        $csrf = csrf_field();
        $method_field = method_field('put');
        $orderDetail = OrderDetail::find($id);
        $selected = $orderDetail->status;
        $selected_trans = trans('site.'.strtolower($selected));
        $not_delivered = trans('site.not_delivered');
        $received = trans('site.received');
        $ready = trans('site.ready');
        $shipped = trans('site.shipped');
        $delivered = trans('site.delivered');
        $cancelled = trans('site.cancelled');
        $returned = trans('site.returned');
        $cancelled_accepted = trans('site.cancelled_accepted');
        $cancelled_denied = trans('site.cancelled_denied');
        $returned_accepted = trans('site.returned_accepted');
        $returned_denied = trans('site.returned_denied');
        $assigned_by_captain = trans('site.assigned_by_captain');

        if($selected === 'CANCELED'){
            $html = <<< HTML
            <form action="$link" method="post" style="display: inline-block">
            $csrf
            $method_field
                        <select class="form-control" name="status" onchange="this.form.submit()">
                            <option value="$selected">$selected_trans</option>
                            <option disabled>-----</option>
                            <option value="CANCELLED_ACCEPTED"> $cancelled_accepted </option>
                            <option value="CANCELLED_DENIED"> $cancelled_denied </option>
                        </select>
            </form>
            HTML;
        }
        elseif($selected === 'RETURNED'){
            $html = <<< HTML
            <form action="$link" method="post" style="display: inline-block">
            $csrf
            $method_field
                        <select class="form-control" name="status" onchange="this.form.submit()">
                            <option value="$selected">$selected_trans</option>
                            <option disabled>-----</option>
                            <option value="RETURNED_ACCEPTED"> $returned_accepted </option>
                            <option value="RETURNED_DENIED"> $returned_denied </option>
                        </select>
            </form>
            HTML;
        }
        elseif (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
            $html = <<< HTML
        <form action="$link" method="post" style="display: inline-block">
        $csrf
        $method_field
            <select class="form-control" name="status" onchange="this.form.submit()">
                <option value="$selected">$selected_trans</option>
                <option disabled>-----</option>
                <option value="READY"> $ready </option>
                <option value="DELIVERED"> $delivered </option>
            </select>
        </form>
        HTML;
        }else {
            $html = <<< HTML
            <form action="$link" method="post" style="display: inline-block">
            $csrf
            $method_field
            <select class="form-control" name="status" id="status$id" onchange="return do_something($id)">
                <option value="$selected">$selected_trans</option>
                <option disabled>-----</option>
                <option value="RECEIVED"> $received </option>
                <option value="READY"> $ready </option>
                <option value="ASSIGNED_BY_CAPTAIN"> $assigned_by_captain </option>
                <option value="SHIPPED"> $shipped </option>
                <option value="DELIVERED"> $delivered </option>
                <option value="NOT_DELIVERED"> $not_delivered </option>
                <option value="CANCELED"> $cancelled </option>
                <option value="CANCELLED_ACCEPTED"> $cancelled_accepted </option>
                <option value="CANCELLED_DENIED"> $cancelled_denied </option>
                <option value="RETURNED"> $returned </option>
                <option value="RETURNED_ACCEPTED"> $returned_accepted </option>
                <option value="RETURNED_DENIED"> $returned_denied </option>
            </select>
            <input name="reason_id" id="reason_id" hidden>
        </form>
        HTML;
        }

        return $html;
        }

        public static function dtCaptain($link, $title, $permission, $id){
            $deliveres = User::join('role_user','role_user.user_id','=','users.id')->where('role_user.role_id', 6)->get();//get all deliveres
            $csrf = csrf_field();
            $method_field = method_field('put');
            $orderDetail = OrderDetail::find($id);
            $current_captain = User::where('id',$orderDetail->captain_id)->first();
            $options = [];
            if($current_captain == NULL){
                array_push($options,'<option value="" selected disabled>-</option>,');
            }
            foreach($deliveres as $delivery){ 
                if($current_captain)
                if($delivery->id == $current_captain->id){
                    array_push($options,'<option value="'.$delivery->id.'" selected>'. $delivery->name .'</option>,');
                }
                array_push($options,'<option value="'.$delivery->id.'">'. $delivery->name .'</option>,');
            } 
            
            if($orderDetail->status === 'DELIVERED'){
                $disabled_var = 'disabled';
            }else{
                $disabled_var = '';
            }
            
            $options_string = implode(",",$options);
            $html = <<< HTML
            <form action="$link" method="post" style="display: inline-block">
            $csrf
            $method_field
                        <select class="form-control" name="captain_id" onchange="this.form.submit()" $disabled_var>
                        $options_string
                        </select>
            </form>
            HTML;

            return $html;
        }

           public static function dtStatusActivateButton($link, $status, $permission)
    {
        if ($status == 1) {
            $active = "fas fa-check-circle fa fa-1x";

        } else {
            $active = "far fa-ban fa fa-1x";


        }

        $csrf = csrf_field();
        $method_field = method_field('POST');
        $classType = ($status) ? "btn-warning" : "btn-default";
        $iconName = ($status) ? "fa-ban" : "fa-user";
        if($status==1) {
//            if (auth()->user()->hasPermission($permission)) {
            $html = <<< HTML

<a href="$link" class="update">

 <i class="fas fa-check-circle fa fa-1x"></i>

</a>

HTML;
        }
        else{
            $html = <<< HTML

<a href="$link" class="update">

 <i class="far fa-ban fa fa-1x"></i>

</a>

HTML;

        }
        return $html;
//        }

    }
        
}
