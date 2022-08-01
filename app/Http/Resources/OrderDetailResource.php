<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
         $name = 'name_'. app()->getLocale();
         
             $brand = 'brand_name_' . app()->getLocale();
        $status_name = trans('site.'.strtolower($this->status));
        if($this->Order->confirmed === 'FALSE'){
            $status_name = trans('site.Not_Completed');
        }else{
            $status_name = trans('site.'.strtolower($this->status));
        }

        return [


            'id' => $this->id,
         
            'status' => $this->status ?? '',
            //'status2' => $status2 ?? '',
            'status_name' => $status_name ?? '',
            'shop_id' => $this->shop_id ?? '',
            'shop_name'=>$this->shop->$name ?? '',
              'brand_name'=>$this->shop->$brand,
            'product_id'=>$this->product_id ?? '',
            'order_id'=>$this->order_id ?? '',
            'price'=>$this->discount_price ?? $this->price ,
            'vat'=>$this->vat ?? '',
            'vat_value'=>$this->vat_value ?? '',
            'commsion'=>$this->commsion ?? '',
             'commsion_value'=> $this->commsion_value ?? '',
           'quantity'=>$this->quantity ?? '',
           'delivery_date' =>$this->delivery_date ?? '',
           'image_path'=>$this->image_path ?? '',
            'name'=> $this->$name ?? '',
          'created_at' => $this->created_at->format('Y-m-d'),

          'options'=> $this->options ?? '',
'code' => $this->code,
          'captain_lng' => $this->Captain->longitude ?? '0.0',
          'captain_lat' => $this->Captain->latitude ?? '0.0',
          'captain_phone' => $this->Captain->phone ?? '',


        ];
    }
}
