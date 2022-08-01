<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
        $description = 'description_'.app()->getLocale();

        return [
            'id' => $this->id ?? '',
            'name' => $this->$name,
            'description' => $this->$description,
            'image_path' => asset('uploads/shops/categories/' . $this->image),
            'created_at' => $this->created_at ?? '',

        ];
    }
}
