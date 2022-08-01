<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Shop;
use App\Models\ShopSetting;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Carbon\carbon;

class ShopSettingController extends Controller
{



    public function edit($id)
    {
        $setting = ShopSetting::latest()->first();
        
        $selectedshop=ShopSetting::pluck('shop_id')->toArray();
        
      
        
            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
          

          $shops=auth()->user()->shops()->get();
          
          
   

        }else {
         $shops=Shop::get();

        }



        return view('dashboard.shopsettings.edit', compact('setting','shops','selectedshop'));
    }//end of edit

    public function update(Request $request, $id)
    {
        // return $request;

        $request->validate([
            'payment' => 'required',
        ]);

        // $payment=ShopSetting::find($id);
        
        
            $request_data = $request->except('_method','_token','shop_id');
            
            //   $result = $payment->update($request_data);  

         if(!empty($request->shop_id)){
  
    //   ShopSetting::whereNotNull('id')->delete();
         foreach($request->shop_id as $shop){
                
                
         $shopsetting=ShopSetting::where('shop_id',$shop)->first();
         
          $result= ShopSetting::updateOrCreate(
          ['shop_id' => (isset($shopsetting['shop_id']) ? $shopsetting['shop_id'] : null)]
          
          ,[
               'shop_id'=>$shop,
               'payment'=>$request->payment,
               'created_by'=>auth()->user()->name,
               ]);     
                
            }

         
}
          



            if ($result) {
                flash(__('site.updated_successfully'))->success();
                return back();

            } else {
                flash(__('site.update_faild'))->success();

            }

        return back();

    }



}//end of controller
