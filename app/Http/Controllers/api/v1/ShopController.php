<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailShopResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ShopResource;
use App\Http\Resources\ProductWithoutOptionsResource;
use App\Models\category;
use App\Models\Shop;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Lang;
use LaravelLocalization;
use DB;
use Mail;
use Geographical;

class ShopController extends Controller
{
    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }

    /*----------------------------------------------------
    || Name     : get latitude and longitude to shops     |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function index(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $operation = $request->input('operation');
        if (!empty($latitude && $longitude)) {
        //=====================General SQL Query to get shops according distance==========
              $sql = Shop::query()
                ->select('shops.*'
                    , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
                            * COS(RADIANS(longitude))
                            * COS(RADIANS(latitude)
                            - RADIANS('$latitude'))
                            + SIN(RADIANS('$longitude'))
                            * SIN(RADIANS(longitude)))) ,2)
                            as distance"))
                // ->groupBy("shops.id")
                // ->orderBy('distance', 'ASC')
                ->where('published', '=', 'TRUE')
                ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0)"), '>', 0)
                ->havingRaw('distance < ?', [1500]);
        } else {
            $sql = Shop::where('published', '=', 'TRUE')->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0)"), '>', 0);
        }
        //===================get Nearest shops=====================================================
          if($operation === 'nearest') {
                 $shop =$sql
                    ->orderBy('distance', 'ASC')
                    ->paginate(10);
          }
        //===================get shops by rating=================================================
          elseif ($operation === 'rating'){
                    $shop =$sql
                    ->withCount([
                        'ratings as rating_avg' => function ($query) {
                                $query->select(DB::raw('coalesce(avg(rate), 0)'));
                            }
                        ])
                    ->orderBy('rating_avg', 'DESC')
                   ->paginate(10);
              }
        //===================search in shops====================================================
          else if ($operation === 'search'){
                $keyword = $request->input('keyword');
                $shop =$sql->where(function($sql) use ($keyword) {
                        $sql->where('name_ar', 'like', '%' . $keyword . '%')
                            ->orWhere('name_en', 'like', '%' . $keyword . '%')
                            ->orWhere('desc_ar', 'like', '%' . $keyword . '%')
                            ->orWhere('desc_en', 'like', '%' . $keyword . '%')
                            ->orWhere('address', 'like', '%' . $keyword . '%');
                    })->paginate(10);


            }
        //===================search in shops by categories=======================================
          else if ($operation === 'category'){
                $category_id = $request->input('category_id');
                $shop =$sql
                      ->where('category_id', '=',$category_id)
                     //->orderBy('distance', 'ASC')
                     ->paginate(10);
            }
               else{
                 $shop =$sql
                    //->orderBy('distance', 'ASC')
                    ->paginate(10);
            }
        $shops = ShopResource::collection($shop);

        return $this->responseWithoutMessageJson(1,$shops);
    }

   /*----------------------------------------------------
    || Name     : show shop details                       |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function showshop(Request $request)
    {
        $rule = [
            'id' => 'required|exists:shops,id',
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
        $operation = $request->operation;
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $shop = Shop::find($request->id);
        $shops = new DetailShopResource($shop);

        $sql = Product::query()
            ->select(
                'products.*'
                , \Illuminate\Support\Facades\DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
                            * COS(RADIANS(longitude))
                            * COS(RADIANS(latitude)
                            - RADIANS('$latitude'))
                            + SIN(RADIANS('$longitude'))
                            * SIN(RADIANS(longitude)))) ,2)
                            as distance"))
            ->join('shop_products', 'shop_products.product_id', '=', 'products.id')
            ->join('shops', 'shops.id', '=', 'shop_products.shop_id')
            ->where('shop_products.shop_id','=',$request->id)
            //->where('shop_products.published', '=', 'TRUE')
            ->where('shop_products.quantity', '>', '0');

        if(!$operation){
            $product   = $sql->paginate(10);
            $shop['products']  = ProductWithoutOptionsResource::collection($product);
            return response()->json(['status' => 1, 'message' => __(''), 'shop' => $shops]);
        }else
            //===================search in shops====================================================
            if ($operation === 'search'){
                $keyword = $request->input('keyword');
                $product = $sql->where(function ($sql) use ($keyword){
                    $sql->where('products.name_ar', 'like', '%' . $keyword . '%')
                        ->orWhere('products.name_en', 'like', '%' . $keyword . '%')
                        ->orWhere('products.desc_ar', 'like', '%' . $keyword . '%')
                        ->orWhere('products.desc_en', 'like', '%' . $keyword . '%');
                })->paginate(10);

            }
            //===================search in malls by categories=======================================
            else if ($operation === 'category'){
                $category_id = $request->input('category_id');
                $product =$sql
                    ->where('products.category_id', '=',$category_id)
                    ->paginate(10);
            }
            //===================case no operation selected ============================================
            else{
                $product =$sql
                    ->paginate(10);
            }
        $shop['products']  = ProductWithoutOptionsResource::collection($product);
        return response()->json(['status' => 1, 'message' => __(''), 'shop' => $shops]);
    }


    /*----------------------------------------------------
    || Name     : show all categories                     |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function getCategories()
    {
        $categories = category::all();
        $items = CategoryResource::collection($categories);

        return $this->responseWithoutMessageJson(1,$items);
    }
}
