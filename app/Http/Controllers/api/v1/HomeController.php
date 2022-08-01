<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MallResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShopResource;
use App\Models\category;
use App\Models\Mall;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Shop;
use App\Http\Resources\ProductCategoryResource;
use App\Models\VisitorProduct;
use App\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
class HomeController extends Controller
{
    /*--------------------------------------------------------------
    || Name     : get shops, mall according distances and categories|
    || Tested   : Done                                              |
    || parameter:                                                   |
    || Info     : type                                              |
    ---------------------------------------------------------------*/
    public function home($locale, Request $request)
    {
        App::setLocale($locale);
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $user = Auth::guard('api')->user();

        if (!empty($latitude || $longitude)) {
            $shop = Shop::query()
                ->select('shops.*'
                    , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
                            * COS(RADIANS(longitude))
                            * COS(RADIANS(latitude)
                            - RADIANS('$latitude'))
                            + SIN(RADIANS('$longitude'))
                            * SIN(RADIANS(longitude)))) ,2)
                            as distance"))
                ->groupBy("shops.id")
                ->orderBy('distance', 'ASC')
                ->havingRaw('distance < ?', [1500])
                ->where('published', '=', 'TRUE')
                ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id )"), '>', 0)
                ->inRandomOrder()
                ->paginate(10);

            $mall = Mall::query()
                ->select('malls.*'
                    , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
                            * COS(RADIANS(malls.longitude))
                            * COS(RADIANS(malls.latitude)
                            - RADIANS('$latitude'))
                            + SIN(RADIANS('$longitude'))
                            * SIN(RADIANS(malls.longitude)))) ,2)
                            as distance"))
                ->groupBy("malls.id")
                ->orderBy('distance', 'ASC')
                ->where('visible', '=', 1)
                ->where(DB::raw("(SELECT COUNT(*)  as mall_count  from shops where shops.mall_id = malls.id )"), '>', 0)
                ->leftjoin('shops','shops.mall_id','=','malls.id')
                ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0)"), '>', 0)
                ->havingRaw('distance < ?', [1500])
                ->inRandomOrder()
                ->paginate(10);
            
            $productsss = Product::query()
            ->select(
                'products.*'
                , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
                            * COS(RADIANS(longitude))
                            * COS(RADIANS(latitude)
                            - RADIANS('$latitude'))
                            + SIN(RADIANS('$longitude'))
                            * SIN(RADIANS(longitude)))) ,2)
                            as distance"))
            ->join('shop_products', 'shop_products.product_id', '=', 'products.id')
            ->join('shops', 'shops.id', '=', 'shop_products.shop_id')
            ->havingRaw('distance < ?', [1500])
            ->where('shop_products.published', '=', 'TRUE')
            ->where('shop_products.quantity', '>', '0')
            ->orderBy('distance','ASC')
            ->inRandomOrder()
            ->paginate(10);
        } else {
            $mall = Mall::where('visible', '=', 1)->leftjoin('shops','shops.mall_id','=','malls.id')
                ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0)"), '>', 0)->paginate(10);
            $shop = Shop::where('published', '=', 'TRUE')->where('published', '=', 'TRUE')
            ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id )"), '>', 0)->inRandomOrder()->paginate(10);
            $productsss = Product::paginate(10);

        }

        if (Auth::guard('api')->user()) {
            $product = $this->paginate($user->productWatch);
            $productrecommand = $this->paginate($user->productWatch);
            $products = $user->products;
            $productss = ProductCategoryResource::collection($products);
        } else {
            $product = [];
            $productss = [];
            $seen_count = VisitorProduct::max('seen_count');
            $category_id = VisitorProduct::where('seen_count', $seen_count)->first()->category_id;
            $productrecommand = Product::where('category_id', $category_id)->paginate(10);
        }
        $products = ProductCategoryResource::collection($product);
        $productrecommands = ProductCategoryResource::collection($productrecommand);
        $prodss = ProductCategoryResource::collection($productsss);
        $productMinPrice = ProductCategoryResource::collection(Product::orderBy('price','ASC')->paginate(10));
        $productMaxOrder = ProductCategoryResource::collection(Product::orderBy(DB::raw("(SELECT COUNT(*)  as order_count  from order_details where order_details.product_id = products.id)"), 'DESC')->paginate(10));
        $malls = MallResource::collection($mall);
        $shops = ShopResource::collection($shop);
        $categories = category::all();
        $banners = Banner::all();
        foreach($banners as $banner){
            $banner->imagePath = $banner->getImagePathAttribute();
        }
        $items = CategoryResource::collection($categories);

        return response()->json(['status' => 1,'categories' => $categories, 'malls' => $malls, 'shops' => $shops, 'products' => $prodss, 'visitorproduct' => $products, 'favourite' => $productss, 'productrecommand' => $productrecommand, 'banners'=> $banners, 'productMinPrice'=>$productMinPrice, 'productMaxOrder'=>$productMaxOrder]);
    }

    /*------------------------------------------
    || Name     : make pagination for items |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    
        /*------------------------------------------
    || Name     : general search                |
    || Tested   : Done                          |
    || parameter:                               |
    || Info     : type                          |
    -------------------------------------------*/
    public function search($locale, Request $request){
        App::setLocale($locale);
        $rule = [
            'type' => 'required|string',
            'keyword' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $type = $request->input('type');
        $keyword = $request->input('keyword');
        $user = Auth::guard('api')->user();
        $malls= '';
        $products = '';
        $shops = '';
        if (!empty($latitude || $longitude)) {
            if($type === "shops") {
                $shop = Shop::query()
                    ->select('shops.*'
                        , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
                            * COS(RADIANS(longitude))
                            * COS(RADIANS(latitude)
                            - RADIANS('$latitude'))
                            + SIN(RADIANS('$longitude'))
                            * SIN(RADIANS(longitude)))) ,2)
                            as distance"))
                    ->where('name_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('desc_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('desc_en', 'like', '%' . $keyword . '%')
                    ->orWhere('brand_name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('brand_name_ar', 'like', '%' . $keyword . '%')
                    ->groupBy("shops.id")
                    ->orderBy('distance', 'ASC')
                    ->havingRaw('distance < ?', [1500])
                    ->where('published', '=', 'TRUE')
                    ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id )"), '>', 0)
                    ->paginate(10);
                $data = ShopResource::collection($shop);
            }elseif($type === "malls") {
                $mall = Mall::query()
                    ->select('malls.*'
                        , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
                            * COS(RADIANS(longitude))
                            * COS(RADIANS(latitude)
                            - RADIANS('$latitude'))
                            + SIN(RADIANS('$longitude'))
                            * SIN(RADIANS(longitude)))) ,2)
                            as distance"))
                    ->where('malls.name_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('malls.name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('malls.desc_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('malls.desc_en', 'like', '%' . $keyword . '%')
                    ->groupBy("malls.id")
                    ->orderBy('distance', 'ASC')
                    ->where('visible', '=', 1)
                    ->where(DB::raw("(SELECT COUNT(*)  as mall_count  from shops where shops.mall_id = malls.id )"), '>', 0)
                    ->leftjoin('shops','shops.mall_id','=','malls.id')
                     ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0)"), '>', 0)
                     ->distinct()
                     ->havingRaw('distance < ?', [1500])
                    ->paginate(10);
                $data = MallResource::collection($mall);
            }
        }else {
            if ($type === "shops") {
                $shop = Shop::query()
                    ->select('shops.*')
                    ->where('name_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('desc_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('desc_en', 'like', '%' . $keyword . '%')
                    ->orWhere('brand_name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('brand_name_ar', 'like', '%' . $keyword . '%')
                    ->groupBy("shops.id")
                    ->where('published', '=', 'TRUE')
                    ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id )"), '>', 0)
                    ->paginate(10);
                $data = ShopResource::collection($shop);
            } elseif ($type === "malls") {
                $mall = Mall::query()
                    ->select('malls.*')
                    ->where('malls.name_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('malls.name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('malls.desc_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('malls.desc_en', 'like', '%' . $keyword . '%')
                    ->groupBy("malls.id")
                    ->where('visible', '=', 1)
                    ->where(DB::raw("(SELECT COUNT(*)  as mall_count  from shops where shops.mall_id = malls.id )"), '>', 0)
                    ->leftjoin('shops','shops.mall_id','=','malls.id')
                     ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id and quantity > 0)"), '>', 0)
                     ->distinct()
                    ->paginate(10);
                $data = MallResource::collection($mall);
            }
        }
        if($type === "products") {
                $sql = Product::query()
                    ->select(
                    'products.*'
                    , DB::raw("ROUND(111.045 * DEGREES(ACOS(COS(RADIANS('$longitude'))
                                * COS(RADIANS(longitude))
                                * COS(RADIANS(latitude)
                                - RADIANS('$latitude'))
                                + SIN(RADIANS('$longitude'))
                                * SIN(RADIANS(longitude)))) ,2)
                                as distance"))
                ->join('shop_products', 'shop_products.product_id', '=', 'products.id')
                ->join('shops', 'shops.id', '=', 'shop_products.shop_id');

                if(isset($request['category_id'])){
                    $sql->where('category_id','=',$request['category_id']);
                }

                $products = $sql
                    ->where(function($query) use ($keyword,$request) {
                        $query->where('products.name_ar', 'like', '%' . $keyword . '%')
                            ->orWhere('products.name_en', 'like', '%' . $keyword . '%')
                            ->orWhere('products.desc_ar', 'like', '%' . $keyword . '%')
                            ->orWhere('products.desc_en', 'like', '%' . $keyword . '%')
                            ;
                    });

                $products->orderBy('price',$request['price'] ? $request['price'] :'ASC');
                $product = $products
                ->inRandomOrder()
                ->paginate(10);
                $data = ProductResource::collection($product);
            }
        
        return response()->json(['status' => 1,'data' => $data]);
    }
    
        public function onlineStatus(Request $request){
        $rule = [
            'status' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $status = $request->status;
        $user = User::findOrFail(Auth::id());
        $user->update(['online_status'=>$status]);

        return response()->json(['status' => 1]);
    }
}
