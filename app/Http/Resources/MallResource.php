<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MallResource extends JsonResource
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


        return [


            'id' => $this->id ?? '',
            'name' => $this->$name,
            'description' => $this->$description,
            'address' => $this->address ?? '',
            'latitude' => $this->latitude ?? '',
            'longitude' => $this->longitude ?? '',
            'category_name'=>$this->category->$name ?? '',
            'contact_number'=>$this->contact_number ?? '',
             'number_of_shops'=>$this->shops()->count() ?? '',
            'distance'=>$this->distance ?? 0,
            'rate'=>$this->ratings->avg('rate') ?? '',
            'phone' => $this->owner_phone,
            'ratings_count' => $this->ratings()->count(),
            'image_path' => asset('uploads/shops/malls/' . $this->image),
            'created_at' => $this->created_at ?? '',

        ];
    }
}
