<?php

namespace App\Http\Resources;
use App\Http\Resources\ProductResource;
use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
class OfferProductResource extends JsonResource
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
        $description = 'desc_' . app()->getLocale();
        $user = Auth::guard('api')->id();
        $favourite = Favorite::where('user_id', $user)->where('product_id', $this->id)->first() ?? '';
        return [


            'id' => $this->id ?? '',
            'name' => $this->name ?? '',
            'description' => $this->description ?? '',
            'price' => $this->price ?? '',
            'category_name' => $this->category->$name ?? '',
            'discount_price' => $this->discount_price ?? '',
            'unit' => $this->unit ?? '',
            'package_count' => $this->package_count ?? '',
            'extras' => $this->extras ?? '',
            'weight' => $this->weight ?? '',
            'number_of_options' => $this->options()->count() ?? '',
            'rate_product' => $this->ratings->avg('rate') ?? '',
            'can_delivery' => $this->can_delivery ?? '',
            'image_path' => asset('uploads/shops/products/' . $this->image) ?? '',
            'created_at' => $this->created_at ?? '',
            'favourite' => !empty($favourite) ? true : false,
            // 'distance' => $this->distance,



        ];
   



    }
}
