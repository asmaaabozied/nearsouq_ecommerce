<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletlogResource extends JsonResource
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
            'amount' => $this->amount ?? '',
            'total' => $this->total ?? '',
            'comment' => $this->comment ?? '',
            'user_id'=>$this->user_id ?? '',
            'payment_method'=>$this->payment_method ?? '',
            'operation'=>$this->operation ?? '',
            'created_at' => $this->created_at ?? '',

        ];
    }
}
