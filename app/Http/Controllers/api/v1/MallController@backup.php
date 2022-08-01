<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;

use App\Http\Resources\ShopResource;
use App\Models\Mall;
use App\Models\Rating;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Resources\MallResource;
use App\Http\Resources\MallDetailResource;

use Illuminate\Support\Facades\Auth;
use Validator;
use Lang;
use LaravelLocalization;
use DB;
use Mail;


class MallController extends Controller
{


    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }

    // get rate with type as request
    public function RatingMallShop(Request $request){

        $rule = [
            'type' => 'required',

            'id' => 'required',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }



        $ratings=Rating::where('type',$request->type)->where('rated_id',$request->id)->latest()->get();

        return $this->responseWithoutMessageJson(1,$ratings);

    }

    // //get mall with latitude and longitude
    // public function index(Request $request)
    // {

    //     $latitude = $request->input('latitude');
    //     $longitude = $request->input('longitude');

    //     $operation = $request->input('operation');


    //     if (!empty($latitude || $longitude)) {
    //         if($operation=='nearest')
    //         {
    //             $mall = Mall::query()
    //                 ->select('malls.*'
    //                     , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
    //                         * COS(RADIANS(longitude))
    //                         * COS(RADIANS(latitude)
    //                         - RADIANS('$latitude'))
    //                         + SIN(RADIANS('$longitude'))
    //                         * SIN(RADIANS(longitude)))) ,2)
    //                         as distance"))
    //                 ->groupBy("malls.id")
    //                 ->orderBy('distance', 'ASC')
    //                 ->where('visible', '=', 1)
    //                 ->where(DB::raw("(SELECT COUNT(*)  as mall_count  from shops where shops.mall_id = malls.id )"), '>', 0)
    //                 ->havingRaw('distance < ?', [1500])
    //                 ->paginate(10);

    //         }elseif ($operation=='numberofshop'){

    //             $mall = Mall::query()
    //                   ->select('malls.*'
    //                     , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
    //                         * COS(RADIANS(longitude))
    //                         * COS(RADIANS(latitude)
    //                         - RADIANS('$latitude'))
    //                         + SIN(RADIANS('$longitude'))
    //                         * SIN(RADIANS(longitude)))) ,2)
    //                         as distance"))
    //                 ->groupBy("malls.id")
    //                 ->where('visible', '=', 1)
    //                 //->where(DB::raw("(SELECT COUNT(*)  as mall_count  from shops where shops.mall_id = malls.id )"), '>', 0)
    //                 ->withCount('shops')
    //                 ->orderBy('shops_count', 'DESC')
    //                 ->havingRaw('distance < ?', [1500])
    //                 ->paginate(10);


    //         }
    //         elseif ($operation=='rating'){

    //             $mall = Mall::query()
    //                   ->select('malls.*'
    //                     , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
    //                         * COS(RADIANS(longitude))
    //                         * COS(RADIANS(latitude)
    //                         - RADIANS('$latitude'))
    //                         + SIN(RADIANS('$longitude'))
    //                         * SIN(RADIANS(longitude)))) ,2)
    //                         as distance"))
    //                 ->groupBy("malls.id")
    //                 ->where('visible', '=', 1)
    //                 ->withCount([
    //                     'shops',
    //                     'ratings as rating_avg' => function ($query) {
    //                             $query->select(DB::raw('coalesce(avg(rate), 0)'));
    //                         }
    //                     ])
    //                 ->orderBy('rating_avg', 'DESC')
    //                 ->havingRaw('distance < ?', [1500])

    //               ->paginate(10);


    //         }else{
    //             $mall = Mall::query()
    //                 ->select('malls.*'
    //                     , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
    //                         * COS(RADIANS(longitude))
    //                         * COS(RADIANS(latitude)
    //                         - RADIANS('$latitude'))
    //                         + SIN(RADIANS('$longitude'))
    //                         * SIN(RADIANS(longitude)))) ,2)
    //                         as distance"))
    //                 ->groupBy("malls.id")
    //                 ->orderBy('distance', 'ASC')
    //                 ->where('visible', '=', 1)
    //                 ->where(DB::raw("(SELECT COUNT(*)  as mall_count  from shops where shops.mall_id = malls.id )"), '>', 0)
    //                 ->havingRaw('distance < ?', [1500])
    //                 ->paginate(10);

    //         }



    //     } else {

    //         $mall = Mall::where('visible', '=', 1)->paginate(10);

    //     }


    //     $malls = MallResource::collection($mall);

    //     return $this->responseJson(1, __('site.messages.success'), $malls);

    // }



    //get mall with latitude and longitude
    public function index(Request $request)
    {

        $latitude  = $request->input('latitude');
        $longitude = $request->input('longitude');
        $operation = $request->input('operation');


         if (!empty($latitude || $longitude)) {
    //=====================General SQL Query to get malls according distance==========
         $sql = Mall::query()
                ->select('malls.*'
                , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
                    * COS(RADIANS(longitude))
                    * COS(RADIANS(latitude)
                    - RADIANS('$latitude'))
                    + SIN(RADIANS('$longitude'))
                    * SIN(RADIANS(longitude)))) ,2)
                    as distance"))
                    ->where('visible', '=', 1)
                    ->where(DB::raw("(SELECT COUNT(*)  as mall_count  from shops where shops.mall_id = malls.id )"), '>', 0)
                    ->havingRaw('distance < ?', [1500]);
         } else {

                $mall   = Mall::where('visible', '=', 1)->paginate(10);
                $malls  = MallResource::collection($mall);
                return $this->responseWithoutMessageJson(1,$malls);

        }

      //===================get Nearest malls=====================================================
            if($operation === 'nearest')
            {
                $mall =$sql
                ->orderBy('distance', 'ASC')
                 ->paginate(10);

            }
     //===================get malls by number of shops=========================================

            elseif ($operation === 'numberofshop'){

                      $mall =$sql
                     ->withCount('shops')
                     ->orderBy('shops_count', 'DESC')
                     ->paginate(10);

            }
      //===================get malls by rating=================================================

            elseif ($operation === 'rating'){
               $mall =$sql
                    ->withCount([
                        'shops',
                        'ratings as rating_avg' => function ($query) {
                                $query->select(DB::raw('coalesce(avg(rate), 0)'));
                            }
                        ])
                    ->orderBy('rating_avg', 'DESC')
                   ->paginate(10);
            }
       //===================search in malls====================================================
            else if ($operation === 'search'){
                $keyword = $request->input('keyword');
                $mall =$sql
                      ->where('name_ar', 'like', '%' . $keyword . '%')
                      ->orWhere('name_en', 'like', '%' . $keyword . '%')
                      ->orWhere('desc_ar', 'like', '%' . $keyword . '%')
                      ->orWhere('desc_en', 'like', '%' . $keyword . '%')
                     ->orderBy('distance', 'ASC')
                     ->paginate(10);

            }
       //===================search in malls by categories=======================================
            else if ($operation === 'category'){
                $category_id = $request->input('category_id');
                $mall =$sql
                      ->where('mall_category_id', '=',$category_id)
                     ->orderBy('distance', 'ASC')
                     ->paginate(10);

            }
    //===================case no operation selected ============================================
            else{
                 $mall =$sql
                    ->orderBy('distance', 'ASC')
                    ->paginate(10);

            }

    //===========================================================================================


        $malls = MallResource::collection($mall);

        return $this->responseWithoutMessageJson(1,$malls);

    }


    // show details mall with mall_id
    public function showmall(Request $request)
    {
        $rule = [
            'id' => 'required|exists:malls,id',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $mall = Mall::find($request->id);

        $malls = new MallDetailResource($mall);

        $shops = ShopResource::collection(Shop::where('mall_id', $request->id)->paginate(10));

        return response()->json(['status' => 1,'mall' => $malls, 'shops' => $shops]);


    }


}
