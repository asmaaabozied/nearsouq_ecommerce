<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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


            'id' => $this->id,
            'name' => $this->name ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->phone,
            'online_status' => $this->online_status ?? '',
            'image_path'=>asset('uploads/'.$this->image),
            'created_at' => $this->created_at->format('Y-m-d'),

        ];
    }
}
