<?php

namespace App\Http\Resources;
use App\Http\Resources\OfferProductResource;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

   


        return [


            'id' => $this->id ?? '',
            'products' =>OfferProductResource::collection($this->OfferProducts),
   



        ];
   



    }
}
