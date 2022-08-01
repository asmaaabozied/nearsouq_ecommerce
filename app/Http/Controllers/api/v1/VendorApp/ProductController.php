<?php

namespace App\Http\Controllers\api\v1\VendorApp;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Cart_product_option;
use App\Models\Option;
use App\Models\Product;
use App\Models\Product_option;
use App\Models\ProductRating;
use App\Models\RelatedProduct;
use App\Models\ShopProduct;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Lang;
use Illuminate\Support\Facades\Auth;
use LaravelLocalization;
use App\Http\Resources\ProductResource;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{

    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }


    //get AllProducts with vendors and admin

    public function getProducts(Request $request)
    {


        if (auth()->user()) {
            if (auth()->user()->type == 'SuperAdmin') {
                $product = Product::where('published', '=', 'TRUE')->orderBy('created_at', 'DESC')->paginate(10);
            } else {

                $product = Product::where('published', '=', 'TRUE')->where('shop_id', $request->shop_id)->orderBy('created_at', 'DESC')->paginate(10);


            }

        } else {
            $product = Product::where('published', '=', 'TRUE')->orderBy('created_at', 'DESC')->paginate(10);

        }
        $products = ProductResource::collection($product);

        return $this->responseWithoutMessageJson(1, $products);

    }


    function str_random($length = 4)
    {
        return Str::random($length);
    }

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }

    //create NewProduct into database
    public function createProducts(Request $request)
    {
        $rule = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'code' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }


        $shop_id = $request->shop_id;
        $product = Product::create($request->except('options','related_product_id', 'option_id', 'shop_id', 'quantity', 'images') + ['published' => "TRUE"]);

        if (!empty($request->shop_id)) {


            foreach ($request->shop_id as $key => $id) {
                ShopProduct::create([

                    'shop_id' => $request['shop_id'][$key],
                    'product_id' => $product->id,
                    'quantity' => $request['quantity'][$key],
                    'published' => "TRUE"
                ]);
            }
        }


        if (!empty($request->related_product_id)) {

            foreach ($request->related_product_id as $key => $id) {

                RelatedProduct::create([

                    'related_product_id' => $request['related_product_id'][$key],

                    'product_id' => $product->id

                ]);

            }

        }
        if (!empty($request->option_id)) {


            foreach ($request->option_id as $key => $id) {


                Product_option::create([

                    'option_id' => $request['option_id'][$key],

                    'product_id' => $product->id

                ]);

            }
        }

        if (!empty($request->options)) {
            foreach ($request->options as $key => $field) {

//            return $field;

                $option = Option::create([
                    'name_ar' => $field['name_ar'],
                    'reference_name' => $field['reference_name'],
                    'name_en' => $field['name_en'],

                    'type' => $field['option_type'],
//                    'shop_id' => $request['shop_id'],
                    'product_id' => $product->id

                ]);


                Product_option::create([

                    'option_id' => $option->id,
                    'product_id' => $product->id,

                ]);


                if(!empty($field['variants'])) {
                    foreach ($field['variants'] as $variant) {

                        $variant = Variant::create([
                            'name_ar' => $variant['name_ar'],
                            'name_en' => $variant['name_en'],
                            'extra_price' => $variant['extra_price'],
                            'option_id' => $option->id,


                        ]);
                    }

                }
            }
        }
        if ($request->file('images')) {

            $imagess = $request->file('images');


            foreach ($imagess as $images) {
                $img = "";
                $img = $this->str_random(4) . $images->getClientOriginalName();
                $originname = time() . '.' . $images->getClientOriginalName();
                $filename = $this->str_slug(pathinfo($originname, PATHINFO_FILENAME), "-");
                $filename = $images->hashName();
                $extention = pathinfo($originname, PATHINFO_EXTENSION);
                $img = $filename;


                $destintion = 'uploads/shops/products';
                $images->move($destintion, $img);
                $image = new \App\Models\Image();
                $image->image = $img;
                $image->imageable_id = $product->id;
                $image->imageable_type = 'App\Models\Product';
                $image->save();

            }
        }

        if ($request->hasFile('image')) {

             $image = $request->file('image');
             $destinationPath = 'uploads/shops/products/';
             $extension = $image->getClientOriginalExtension(); // getting image extension
             $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
             $image->move($destinationPath, $name); // uploading file to given
             $product->image = $name;
             $product->save();
//            UploadImage('uploads/shops/products', $product, $request);
        }

        return $this->responseWithoutMessageJson(1, $product);


    }

    public function updateProducts(Request $request)
    {
        $rule = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'code' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }



        $id=$request->id;
        $product = Product::find($id);


        $product->update($request->except('options','related_product_id', 'option_id', 'shop_id', 'quantity', 'images') + ['published' => "TRUE"]);

        if (!empty($request->shop_id)) {
            ShopProduct::where('product_id', $product->id)->delete();

            foreach ($request->shop_id as $key => $id) {
                ShopProduct::create([

                    'shop_id' => $request['shop_id'][$key],
                    'product_id' => $product->id,
                    'quantity' => $request['quantity'][$key],
                    'published' => "TRUE"
                ]);
            }
        }


        if (!empty($request->related_product_id)) {
            RelatedProduct::where('product_id', $product->id)->delete();
            foreach ($request->related_product_id as $key => $id) {

                RelatedProduct::create([

                    'related_product_id' => $request['related_product_id'][$key],

                    'product_id' => $product->id

                ]);

            }

        }
        if (!empty($request->option_id)) {


            foreach ($request->option_id as $key => $id) {


                Product_option::create([

                    'option_id' => $request['option_id'][$key],

                    'product_id' => $product->id

                ]);

            }
        }

        if (!empty($request->options)) {
            $options = Option::where('product_id', $id)->get();
            foreach ($options as $option) {

                Product_option::where('option_id', $option->id)->where('product_id', $id)->delete();
                $option->variants()->delete();//option has many variants

                Option::where('id', $option->id)->first()->delete();

            }


            foreach ($request->options as $key => $field) {

//            return $field;

                $option = Option::create([
                    'name_ar' => $field['name_ar'],
                    'reference_name' => $field['reference_name'],
                    'name_en' => $field['name_en'],

                    'type' => $field['option_type'],
//                    'shop_id' => $request['shop_id'],
                    'product_id' => $product->id

                ]);


                Product_option::create([

                    'option_id' => $option->id,
                    'product_id' => $product->id,

                ]);


                if(!empty($field['variants'])) {
                    foreach ($field['variants'] as $variant) {

                        $variant = Variant::create([
                            'name_ar' => $variant['name_ar'],
                            'name_en' => $variant['name_en'],
                            'extra_price' => $variant['extra_price'],
                            'option_id' => $option->id,


                        ]);
                    }

                }
            }
        }
        if ($request->file('images')) {

            $imagess = $request->file('images');


            foreach ($imagess as $images) {
                $img = "";
                $img = $this->str_random(4) . $images->getClientOriginalName();
                $originname = time() . '.' . $images->getClientOriginalName();
                $filename = $this->str_slug(pathinfo($originname, PATHINFO_FILENAME), "-");
                $filename = $images->hashName();
                $extention = pathinfo($originname, PATHINFO_EXTENSION);
                $img = $filename;


                $destintion = 'uploads/shops/products';
                $images->move($destintion, $img);
                $image = new \App\Models\Image();
                $image->image = $img;
                $image->imageable_id = $product->id;
                $image->imageable_type = 'App\Models\Product';
                $image->save();

            }
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $destinationPath = 'uploads/shops/products/';
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given
            $product->image = $name;
            $product->save();
//            UploadImage('uploads/shops/products', $product, $request);
        }

        return $this->responseWithoutMessageJson(1, $product);


    }

// delele product with all relationship
    public function deleteProduct(Request $request)
    {
        $id = $request->id;
        $res = RelatedProduct::where('product_id', $id)->delete();
        $res = ShopProduct::where('product_id', $id)->delete();
        $res = Product_option::where('product_id', $id)->delete();
        $res = ProductRating::where('product_id', $id)->delete();
        $res = Cart_product_option::where('product_id', $id)->delete();
        $res = Cart::where('product_id', $id)->delete();
        $product = Product::find($id);
        $product->delete();

        return response()->json(['status' => 1]);
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
}
