<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {


        $name = 'name_' . app()->getLocale();
         $brand = 'brand_name_' . app()->getLocale();


        return [


            'id' => $this->id ?? '',
            'shop_id' => $this->shop_id ?? '',
            'product_id' => $this->product_id ?? '',

            'quantity' => $this->quantity ?? '',

                'brand_name'=>$this->shop->$brand,
            'shop_name' => $this->shop->$name ?? '',
            'user_name' => $this->user->name ?? '',
            'product_name' => $this->Product->$name ?? '',
            'price' => $this->Product->price ?? '',
             'discount_price' => $this->Product->discount_price ?? '',
           
            'variants'=>$this->variants ?? '',

            'image_path' => asset('uploads/shops/products/' . $this->Product->image) ?? '',
            'created_at' => $this->created_at ?? '',


        ];
    }
}
