<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\OfferProductResource;
use App\Http\Resources\ShopResource;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Cart;
use App\Models\ProductRating;
use App\Models\VisitorProduct;
use App\Models\ShopProduct;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelLocalization;
use Intervention\Image\Facades\Image;


class ProductController extends Controller
{

    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }

    /*----------------------------------------------------
    || Name     : show offer product                      |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function offerProduct(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $operation = $request->operation;
        if (!empty($latitude && $longitude)) {
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
            ->join('shops', 'shops.id', '=', 'shop_products.shop_id')
            ->orderBy('distance', 'ASC')
            ->havingRaw('distance < ?', [1500])
            ->where('shop_products.published', '=', 'TRUE')
            ->where('shop_products.quantity', '>', '0')
            //->where('products.discount_price', '<', 'products.price')
            ->where(DB::raw("products.price"), '>', DB::raw("products.discount_price"))
            ->whereNotNull('products.discount_price');

            if ($operation === 'category'){
                $category_id = $request->input('category_id');
                $product =$sql
                    ->where('products.category_id', '=',$category_id)
                    ->paginate(10);
            }elseif($operation === 'rating'){
                $product =$sql
                    ->leftjoin('product_ratings', 'product_ratings.product_id','=','products.id')
                    ->orderBy(DB::raw("(SELECT AVG(rate)  as rate_product from product_ratings where product_ratings.product_id = products.id )"),'DESC')
                    ->paginate(10);
            }else if ($operation === 'search'){
                $keyword = $request->input('keyword');
                $product =$sql
                    ->where('products.name_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('products.name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('products.desc_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('products.desc_en', 'like', '%' . $keyword . '%')
                    ->paginate(10);
            }elseif($operation === 'nearest'){
                $product =$sql
                ->orderBy('distance', 'ASC')->paginate(10);
            }
            else{
            $product = $sql->inRandomOrder()->paginate(10);
        }
        }
        $products = OfferProductResource::collection($product);

        return $this->responseWithoutMessageJson(1,$products);
    }

    /*----------------------------------------------------
    || Name     : show product rating                     |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function showRating(Request $request)
    {
        $rule = [
            'product_id' => 'required|exists:products,id'
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            if(str_contains(validationErrorsToString($validator->errors()),'product_id')){
                return response()->json(['status' => 423, 'message' => validationErrorsToString($validator->errors())], 422);
            }
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $product_id = $request->input('product_id');
        $ratings = ProductRating::where('product_id', $product_id)->latest()->get();

        return $this->responseWithoutMessageJson(1,$ratings);
    }

    /*----------------------------------------------------
    || Name     : add rating to products                  |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function addRating(Request $request)
    {
        $rule = [
            'product_id' => 'required|exists:products,id',
            'rate' => 'required',
            'comment' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            if(str_contains(validationErrorsToString($validator->errors()),'product_id')){
                return response()->json(['status' => 423, 'message' => validationErrorsToString($validator->errors())], 422);
            }
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $product_id = $request->input('product_id');
        //$image = Product::where('id', $product_id)->first()->image;
        $picture_name = null;
        if ($request->hasFile('image')) {
            $picture_name = 'uploads/shops/ratings' . '/' . time() . str_shuffle('abcdef') . '.' . $request->file('image')->getClientOriginalExtension();
            Image::make($request->file('image'))->save(public_path("$picture_name"));
            $request->request->set('image', $picture_name);
        }
        $ratings = ProductRating::create([
            'product_id' => $product_id,
            'rate' => $request->input('rate'),
            'comment' => $request->input('comment'),
            'image' => $picture_name,
            'user_id' => Auth::id()
        ]);

        return $this->responseWithoutMessageJson(1,$ratings);
    }

    /*----------------------------------------------------
    || Name     : add quantity to cart                    |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function addQuantity(Request $request)
    {
        $rule = [
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required'
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            if(str_contains(validationErrorsToString($validator->errors()),'cart_id')){
                return response()->json(['status' => 423, 'message' => validationErrorsToString($validator->errors())], 422);
            }
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $cart = Cart::find($request->cart_id);
        $quantity = ShopProduct::where('shop_id', $cart->shop_id)->where('product_id', $cart->product_id)->first()->quantity;
        if ($cart->exists
            && $quantity >= $request->quantity) {
            $cart->update(['quantity' => $request->quantity]);
        } else {
            return $this->responseJson(1, __('site.productnotavailable'));
        }

        return $this->responseWithoutMessageJson(1,$cart);
    }

    /*----------------------------------------------------
    || Name     : show product with categories            |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function showProductWithCategories(Request $request)
    {
        $rule = [
            'category_id' => 'required|exists:categories,id',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            if(str_contains(validationErrorsToString($validator->errors()),'Category')){
                return response()->json(['status' => 423, 'message' => validationErrorsToString($validator->errors())], 422);
            }
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        //$product = Product::where('category_id', $request->category_id)->where('published', '=', 'TRUE')->orderBy('created_at', 'DESC')->paginate(10);
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $type = $request->type;
        $operation = $request->operation;

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
            ->join('shops', 'shops.id', '=', 'shop_products.shop_id')
            ->where('shop_products.published', '=', 'TRUE')
            ->where('products.category_id', $request->category_id)
            //->orderBy('created_at', 'DESC')
            ->where('shop_products.quantity', '>', '0');
            if($type === 'nearest'){
                $sql->orderBy('distance', 'ASC');
            }elseif($type === 'LowestPrice'){
                $sql->orderBy('price','ASC');
            }elseif($type === 'MaxOrder'){
                $sql->orderBy(DB::raw("(SELECT COUNT(*)  as order_count  from order_details where order_details.product_id = products.id)"), 'DESC');
            }

            if($operation === 'rating'){
                $product =$sql
                    ->leftjoin('product_ratings', 'product_ratings.product_id','=','products.id')
                    ->orderBy(DB::raw("(SELECT AVG(rate)  as rate_product from product_ratings where product_ratings.product_id = products.id )"),'DESC')
                    ->paginate(10);
            }else if ($operation === 'search'){
                $keyword = $request->input('keyword');
                $product =$sql
                    ->where('products.name_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('products.name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('products.desc_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('products.desc_en', 'like', '%' . $keyword . '%')
                    ->paginate(10);
            }
            else{
            $product = $sql->inRandomOrder()->paginate(10);
        }
        $products = ProductCategoryResource::collection($product);

        return $this->responseWithoutMessageJson(1,$products);
    }

    /*----------------------------------------------------
    || Name     : show product details                    |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function showProduct(Request $request)
    {
        $rule = [
            'id' => 'required|exists:products,id',
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
        $user_id = Auth::guard('api')->id();
        $product_id = $request->id;
        $category_id = Product::where('id', $product_id)->first()->category_id;
        $product = VisitorProduct::where('product_id', $product_id)->where('user_id', $user_id)->first();
        if ($product) {
            $prod = $product->seen_count;
            $count = $prod + 1;
        } else {
            $count = 1;
        }
        VisitorProduct::updateOrCreate(['product_id' => $product_id, 'user_id' => $user_id],
            ['product_id' => $product_id, 'user_id' => $user_id, 'category_id' => $category_id, 'seen_count' => $count]);
        $data = ['user_id' => $user_id, 'product_id' => $product_id, 'category_id' => $category_id, 'count' => $count];
        $product = Product::where('id', $request->id)->first();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        if (!empty($latitude && $longitude)) {
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
                ->join('shop_products', 'shop_products.shop_id', '=', 'shops.id')
                ->where("shop_products.product_id", $product_id)
                ->where(DB::raw("(SELECT COUNT(*)  as product_count  from shop_products where shop_products.shop_id = shops.id )"), '>', 0)
                ->paginate(5);
        } else {
            $shop = $this->paginate($product->Shops);
        }
        $shops = ShopResource::collection($shop);
        $products = new ProductResource($product);

        return response()->json(['status' => 1,'product' => $products, 'shops' => $shops]);
    }

    /*----------------------------------------------------
    || Name     : show all products                       |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function indexProduct(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $type = $request->type;
        $operation = $request->operation;

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
            ->join('shops', 'shops.id', '=', 'shop_products.shop_id')
            ->where('shop_products.published', '=', 'TRUE')
            ->where('shop_products.quantity', '>', '0');
            if($type === 'nearest'){
                $sql->orderBy('distance', 'ASC');
            }elseif($type === 'LowestPrice'){
                $sql->orderBy('price','ASC');
            }elseif($type === 'MaxOrder'){
                $sql->orderBy(DB::raw("(SELECT COUNT(*)  as order_count  from order_details where order_details.product_id = products.id)"), 'DESC');
            }

            if ($operation === 'category'){
                $category_id = $request->input('category_id');
                $product =$sql
                    ->where('products.category_id', '=',$category_id)
                    ->paginate(10);
            }elseif($operation === 'rating'){
                $product =$sql
                    ->leftjoin('product_ratings', 'product_ratings.product_id','=','products.id')
                    ->orderBy(DB::raw("(SELECT AVG(rate)  as rate_product from product_ratings where product_ratings.product_id = products.id )"),'DESC')
                    ->paginate(10);
            }else if ($operation === 'search'){
                $keyword = $request->input('keyword');
                $product =$sql
                    ->where('products.name_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('products.name_en', 'like', '%' . $keyword . '%')
                    ->orWhere('products.desc_ar', 'like', '%' . $keyword . '%')
                    ->orWhere('products.desc_en', 'like', '%' . $keyword . '%')
                    ->paginate(10);
            }
            else{
            $product = $sql->inRandomOrder()->paginate(10);
        }
        $products = ProductResource::collection($product);

        return $this->responseWithoutMessageJson(1,$products);
    }
    /*----------------------------------------------------
    || Name     : add visitor from here                   |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function AddVisitorProduct(Request $request)
    {
        $user_id = Auth::id();
        $product_id = $request->input('product_id');
        $category_id = Product::where('id', $product_id)->first()->category_id;
        $product = VisitorProduct::where('product_id', $product_id)->where('user_id', $user_id)->first();
        if ($product) {
            $prod = $product->seen_count;
            $count = $prod + 1;
        } else {
            $count = 1;
        }
        VisitorProduct::updateOrCreate(['product_id' => $product_id, 'user_id' => $user_id],
            ['product_id' => $product_id, 'user_id' => $user_id, 'category_id' => $category_id, 'seen_count' => $count]);
        $data = ['user_id' => $user_id, 'product_id' => $product_id, 'category_id' => $category_id, 'count' => $count];

        return $this->responseWithoutMessageJson(1,$data);
    }

    /*----------------------------------------------------
    || Name     : get paginate                            |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /*----------------------------------------------------
    || Name     : add favourite products                  |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function AddFavouriteProduct(Request $request)
    {
        $rule = [
            'product_id' => 'required|exists:products,id',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            if(str_contains(validationErrorsToString($validator->errors()),'product_id')){
                return response()->json(['status' => 423, 'message' => validationErrorsToString($validator->errors())], 422);
            }
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        $user_id = Auth::id();
        $users = auth()->user();
        $user = $users->products()->toggle($request->product_id);

        return $this->responseWithoutMessageJson(1,$user);
    }

    /*----------------------------------------------------
    || Name     : show product favourite                  |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function ShowFavouriteProduct()
    {
        $user_id = Auth::id();
        $users = User::find($user_id);
        $product = $users->products;
        $products = ProductResource::collection($product);

        return $this->responseWithoutMessageJson(1,$products);
    }
    
     /*----------------------------------------------------
    || Name     : get realted products                    |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function relatedProduct(Request $request){
        $rule = [
            'product_id' => 'required|exists:products,id',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);
        if ($validator->fails()) {
            if(str_contains(validationErrorsToString($validator->errors()),'product_id')){
                return response()->json(['status' => 423, 'message' => validationErrorsToString($validator->errors())], 422);
            }
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }

        $product = Product::find($request->product_id);
        $related_products = Product::query()
            ->select('products.*')
            ->where('related_products.product_id',$request->product_id)
            ->join('related_products','related_products.related_product_id','=','products.id')
            ->paginate(10);
        //return $related_products;
        $products = ProductResource::collection($related_products);
        return $this->responseWithoutMessageJson(1,$products);
    }
}
