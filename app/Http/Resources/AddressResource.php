<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
class AddressResource extends JsonResource
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
            'type' => $this->type ?? '',
            'first_name' =>$this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'phone' => $this->phone ?? '',
            'created_at' => $this->created_at ?? '',
            'user_id' => $this->user_id ?? '',
            'comment' => $this->comment ?? '',
            'longitude' => $this->longitude ?? '',
            'latitude' => $this->latitude ?? '',
            'state' => $this->state ?? '',
            'default_address' => $this->default_address ?? '',
            'street' => $this->street ?? '',
            'postal_code' => $this->postal_code ?? '',
            'city' => $this->city ?? '',
            'country' => $this->country ?? '',
            'neighborhood' => $this->neighborhood ?? '',



        ];
    }
}
