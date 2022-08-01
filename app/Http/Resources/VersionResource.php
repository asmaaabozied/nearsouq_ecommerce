<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Versions;
use DateTime;
use Carbon\Carbon;

class VersionResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $today       = date("d-m-Y");
        if(isset($this->id)){
            $expiry_date = $this->expiry_date;

            $datetime1   = new DateTime( Carbon::now());
            $datetime2   = new DateTime($expiry_date);
            $interval    = $datetime1->diff($datetime2);
            $value       = (int)$interval->format('%a');

            return [
                'expire'=>$this->expire ?? 0,
                'days'  =>$this->days ?? $value,
                'recommend_version'=> Versions::
                where('build_no','>' ,$request->build_no)
                    ->where('type', $request->type)
                    ->where('os',$request->os)
                    ->whereDate('expiry_date', '>',  Carbon::now())
                    ->orderBy('id','desc')->first()



            ];
        }else{
            return [
                'expire'=>$this->expire ?? 1,
                'days'=>$this->days ?? 0,
                'recommend_version'=> Versions::
                where('build_no','>' ,$request->build_no)
                    ->where('type', $request->type)
                    ->where('os',$request->os)
                    ->whereDate('expiry_date', '>',  Carbon::now())
                    ->orderBy('id','desc')->first()
            ];
        }

    }
}
