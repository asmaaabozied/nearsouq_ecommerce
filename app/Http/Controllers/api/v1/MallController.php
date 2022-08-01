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

    /*------------------------------------------
    || Name     : get rate with type as request |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
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

        return $this->responseJson(1, __('site.messages.success'), $ratings);
    }

    /*---------------------------------------------------------------------------------
    || Name     : get mall with latitude and longitude or searching according opertion |
    || Tested   : Done                                                                 |
    || parameter:                                                                      |
    || Info     : type                                                                 |
    ----------------------------------------------------------------------------------*/
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
                    * COS(RADIANS(malls.longitude))
                    * COS(RADIANS(malls.latitude)
                    - RADIANS('$latitude'))
                    + SIN(RADIANS('$longitude'))
                    * SIN(RADIANS(malls.longitude)))) ,2)
                    as distance"))
                 ->where('visible', '=', 1)
                 ->where(DB::raw("(SELECT COUNT(*)  as mall_count  from shops where shops.mall_id = malls.id)"), '>', 0)
                 ->leftjoin('shops','shops.mall_id','=','malls.id')
                 ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0)"), '>', 0)
                 ->distinct()
                 ->havingRaw('distance < ?', [1500]);
         } else {
             $mall   = Mall::query()->select('malls.*')->where('visible', '=', 1)->where(DB::raw("(SELECT COUNT(*)  as mall_count  from shops where shops.mall_id = malls.id)"), '>', 0)
                 ->leftjoin('shops','shops.mall_id','=','malls.id')
                 ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0)"), '>', 0)
                 ->distinct()->paginate(10);
             $malls  = MallResource::collection(
                 $mall
             );
             return $this->responseJson(1, __(''), $malls);
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
                      ->where('malls.name_ar', 'like', '%' . $keyword . '%')
                      ->orWhere('malls.name_en', 'like', '%' . $keyword . '%')
                      ->orWhere('malls.desc_ar', 'like', '%' . $keyword . '%')
                      ->orWhere('malls.desc_en', 'like', '%' . $keyword . '%')
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
                    ->paginate(10);
            }
        $malls = MallResource::collection($mall);
            //$malls = $malls->where('number_of_shops','>',0);

        return $this->responseJson(1, __(''), $malls);
    }

    /*---------------------------------------------------------------------------------
    || Name     : show mall with their shops filtering by keyword or category          |
    || Tested   : Done                                                                 |
    || parameter:                                                                      |
    || Info     : type                                                                 |
    ----------------------------------------------------------------------------------*/
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
            if(str_contains(validationErrorsToString($validator->errors()),'id')){
                return response()->json(['status' => 423, 'message' => validationErrorsToString($validator->errors())], 422);
            }
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $operation = $request->input('operation');
        $mall = Mall::find($request->id);
        $malls = new MallDetailResource($mall);
        $sql = Shop::query()
                        ->select('shops.*')->where('mall_id',$request->id)->where(DB::raw("(SELECT COUNT(*)  as shop_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0)"), '>', 0);
                if(!$operation){
                        $shop   = $sql->paginate(10);
                        $shops  = ShopResource::collection($shop);
                        return response()->json(['status' => 1, 'message' => __(''), 'mall' => $malls, 'shops' => $shops]);
                }else
        //===================search in shops====================================================
        if ($operation === 'search'){
            $keyword = $request->input('keyword');
            $shops =$sql
                    ->where('name_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('desc_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('desc_en', 'like', '%' . $keyword . '%')
                    ->orWhere('brand_name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('brand_name_ar', 'like', '%' . $keyword . '%')
                ->paginate(10);
        }
        //===================search in malls by categories=======================================
        else if ($operation === 'category'){
            $category_id = $request->input('category_id');
            $shops =$sql
                    ->where('category_id', '=',$category_id)
                ->paginate(10);
        }
        //===================case no operation selected ============================================
        else{
            $shops =$sql
                ->paginate(10);
        }
        $shops = ShopResource::collection($shops);

        return response()->json(['status' => 1, 'message' => __(''), 'mall' => $malls, 'shops' => $shops]);
    }
    
    /*--------------------------------------------
    || Name     :  make rate with type as request |
    || Tested   : Done                            |
    || parameter:                                 |
    || Info     : type                            |
    ---------------------------------------------*/
    public function MakeRatingMallShop(Request $request){
        $rule = [
            'id' => 'required',
            'operation' => 'required',
            'rate' => 'required'
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $rating = Rating::create([
            'rated_id' => $request->id,
            'user_id' => Auth::id(),
            'type' => $request->operation,
            'rate' => $request->rate,
            'comment' => $request->comment,
        ]);

        return $this->responseJson(1, __('') );
    }
}
