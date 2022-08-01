<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\RelatedProduct;
use App\Models\Variant;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Helpers\SiteHelper;
use App\DataTables\ProductDatatables;
use App\Imports\ProductImport;
use App\Models\Option;
use App\Models\Product;
use App\Models\Notification;
use App\Models\Product_option;
use App\Models\ProductRating;
use App\Models\Cart;
use App\Models\Cart_product_option;
use App\Models\ShopProduct;
use Illuminate\Support\Facades\Session;
use Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\Shop;
use DB;
use App\Models\Device;
use App\Models\ShopSetting;


// use Illuminate\Support\Facades\Validator;
use Validator;
class ProductController extends Controller
{


    public function getdata(ProductDatatables $productDatatables)
    {
        //find product by id

        //echo $pro

        //   prd->id
        //  get options from db
//        foreach (){
//            $option +="";
//        }
        //  foreach
        //     opt+=""

//        echo "<div>id : .$prd->id. <br>
//               name: $prd->name
//
//               options: .$option.
//               </div>";
    }

    /*----------------------------------------------------
    || Name     : show all products                     |
      || Tested   : Done                                    |
      || using  : datatables                                      |
       ||                                    |
           -----------------------------------------------------*/
    public function index(ProductDatatables $productDatatables)
    {
        
    

        // session()->forget('success');
        if (auth()->user()->hasPermission('read_products')) {
            
            
    
                        
                if (auth()->user()->hasPermission('read_notificationproducts')) {                        
                        $products=DB::table('shop_products')->where('quantity','<',5)->first();
                        
                       
                        $quantity=$products->quantity;
                            $productId=$products->product_id;
                     
                        if($quantity < 5){
                            
                            
                              $user = auth()->user();
                              $user_device = Device::where('user_id',Auth::id())->where('platform','WEB')->first();
                    if($user_device != NULL){
                    $client = $user_device;
                    //dd($client);
                 
                    // if ($client->onesignal_id != null) {
                        $text = 'site.messages.productis quantity';
                        $title = 'site.products';
                        $onesignal_id = $client->one_signal_id;
                        $type = "NOTIFY";
                        $order_id = 1;
                        $id=$client->user_id;
                 

                        $response = SiteHelper::sendMessage($onesignal_id,$text,$title,'web',$type, $id);
                        // $imageid = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
                        // $uid = uniqid();

                        Notification::create([
                            'title' => $title,
                            'message' => $text, 
                            'user_id' => $id,
                            // 'image' => $imageid,
                            'order_id' =>$productId,
                            'type' => $type,
                            'delete' => 0,
                            'show' => 1,
                            'read' => 0,
                        ]);
                        //print_r($response);die();
                  
                    }
                    // }
                           
                           
                           
                           
                        }
                // }
                
          
                          }
        
                 


            return $productDatatables->render('dashboard.products', [
                'title' => trans('site.products'),
                'model' => 'products',
                'count' => $productDatatables->count()
            ]);
        } else {
            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }
    }//end of index


    // public function CheckedProduct()
    // {


    //     $products = Product::get();


    //     foreach ($products as $product) {

    //         $shop = Shop::where('old_shop_id', $product->shop_old_id)->first();

    //         if ($shop) {
    //             $product->update(['shop_id' => $shop->id]);

    //           ShopProduct::create(['shop_id' => $shop->id,
    //           'product_id' => $product->id,
    //           'quantity' => 500,
    //           'published'=> "TRUE"]);


    //         }
    //     }
    //     if ($shop) {

    //         session()->flash('success', __('site.added_successfully'));
    //     }
    //     return redirect('/dashboard');

    // }


    // public function changeImageId()
    // {


    //     $products = Product::get();


    //     foreach ($products as $product) {


    //             DB::table('images')
    //             ->where('product_old_id', $product->old_id)
    //             ->update(['imageable_id' =>  $product->id,
    //                       'imageable_type' => 'App\Models\Product']);

    //     }

    //         session()->flash('success', __('site.added_successfully'));
    //     return redirect('/dashboard');

    // }


//  public function CheckedProduct()
//     {


//         $products = Images::get();


//         foreach ($products as $product) {


//                 DB::table('images')
//                 ->where('image', $product->image)
//                 ->delete();

//         }

//             session()->flash('success', __('site.added_successfully'));
//         return redirect('/dashboard');

//     }

//   public function CheckedProduct()
//     {


//         $shops = Shop::get();


//         foreach ($shops as $shop) {

//             // $shop = Shop::where('old_shop_id', $product->shop_old_id)->first();

//             // if ($shop) {
//             //     $product->update(['shop_id' => $shop->id]);

//               ShopSetting::create(['shop_id' => $shop->id,
//               'payment' => "prompt"
//      ]);


//             // }
//         }
//         if ($shop) {

//             session()->flash('success', __('site.added_successfully'));
//         }
//         return redirect('/dashboard');

//     }


    public function filesProduct()
    {
        return view('dashboard.products.files');


    }


    public function uploadecxel(Request $request)
    {
        
        $request->validate([

                'file' => 'required',
    

            ]

        );

        if ($request->file('file')) {

            $images = $request->file('file');

            $img = "";
            $img = $this->str_random(4) . $images->getClientOriginalName();
            $originname = time() . '.' . $images->getClientOriginalName();
            $filename = $this->str_slug(pathinfo($originname, PATHINFO_FILENAME), "-");
            $filename = $images->hashName();
            $extention = pathinfo($originname, PATHINFO_EXTENSION);
            $img = $filename;

            $destintion = 'images/shops/products';
            $images->move($destintion, $img);
        }
        Excel::import(new ProductImport(), base_path('images/shops/products/' . $filename));


        session()->flash('success', __('site.file_successfully'));


        return back();

    }


    /*----------------------------------------------------
          || Name     : open pages create                     |
          || Tested   : Done                                    |
          ||                                     |
           ||                                    |
           -----------------------------------------------------*/


    public
    function edit($id)
    {
        if (auth()->user()->hasPermission('update_products')) {

            $product = Product::find($id);

           $shops= $product->Shops ?? [];

            $optiondetails = $product->options ?? '';


            $related = RelatedProduct::where('product_id', $id)->pluck('related_product_id')->toArray();
            // $optionselected = Product_option::where('product_id', $id)->pluck('option_id')->toArray();

            $optionselected = Option::where('product_id', $id)->get();
// return $optionselected;

            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
                $productrelated = Product::where('shop_id', Session::get('shop_id'))->get();
                $options = Option::where('shop_id', Session::get('shop_id'))->where('product_id', $id)->get();
            } else {
                $productrelated = Product::get();
                $options = Option::where('product_id', $id)->get();

            }

            return view('dashboard.products.edit', compact('shops','optiondetails', 'optionselected', 'product', 'productrelated', 'options', 'related'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }

    public function create()
    {
        if (auth()->user()->hasPermission('create_products')) {

            $code = Str::random(2) . rand(10, 99);

            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
                $productrelated = Product::where('shop_id', Session::get('shop_id'))->get();
                $options = Option::where('shop_id', Session::get('shop_id'))->get();
            } else {
                $productrelated = Product::get();
                $options = Option::get();
            }


            return view('dashboard.products.create', compact('productrelated', 'options', 'code'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));
        }
    }//end of create


    function str_random($length = 4)
    {
        return Str::random($length);
    }

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }


    /*----------------------------------------------------
   || Name     : store data into database Product          |
   || Tested   : Done                                    |
    ||                                     |
     ||                                    |
         -----------------------------------------------------*/

    public function store(Request $request)
    {

// dd($request->all());die();

         $validation = Validator::make($request->all(), [
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'price' => 'required',
            'code' => 'required|unique:products',
            //'image'=>'required',
            'category_id' => 'required',
            //'shop_id' => 'required|array',
            //'shop_id.*' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }


       
        // DB::beginTransaction();
        // try {
            // $shop_id = 1;

            $shop_id = Session::get('shop_id') ?? $shop_id = $request['shop_id'][0];
            
            $product = Product::create($request->except('fields', 'related_product_id', 'option_id', 'shop_id', 'quantity', 'images') + ['shop_id' => $shop_id, 'published' => "TRUE"]);



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
            if (!empty($request->fields)) {


                foreach ($request->fields as $key => $field) {

//            return $field;

                    $option = Option::create([
                        'name_ar' => $field['ar']['name'][0],
                        'reference_name' => $field['ar']['reference_name'][0],
                        'name_en' => $field['en']['name'][0],

                        'type' => $field['option_type'][0],
                        'shop_id' => $request['shop_id'][0] ?? '',
                        'product_id' => $product->id

                    ]);


                    Product_option::create([

                        'option_id' => $option->id,
                        'product_id' => $product->id,

                    ]);

                    if (isset($field['en']['value'])) {
                        foreach ($field['en']['value'] as $k => $value) {

//                return $value;

                            $variant = Variant::create([
                                'name_ar' => $field['ar']['value'][$k],
                                'name_en' => $field['en']['value'][$k],
                                'option_id' => $option->id,
                                'extra_price' => $field['add_price'][$k]

                            ]);


                            if (isset($field['images'][$k])) {

                                $image = $field['images'][$k];


//                        foreach ($imagess as $image) {


                                $destinationPath = 'uploads/shops/options/';
                                $extension = $image->getClientOriginalExtension(); // getting image extension
                                $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                                $image->move($destinationPath, $name); // uploading file to given
                                $variant->image = $name;

                                $variant->save();

//                        }

                            }


                        }
                    }

                }


            }

            if (!empty($request->related_product_id)) {


                foreach ($request->related_product_id as $key => $id) {
                    if ($id == 'nodataselect') {

                    } else {
                        RelatedProduct::create([

                            'related_product_id' => $request['related_product_id'][$key],

                            'product_id' => $product->id

                        ]);
                    }
                }

            }
            if (!empty($request->option_id)) {


                foreach ($request->option_id as $key => $id) {

                    if ($id == 'nodataselect') {

                    } else {


                        Product_option::create([

                            'option_id' => $request['option_id'][$key],

                            'product_id' => $product->id

                        ]);
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
                // $image = $request->file('image');
                // $destinationPath = 'uploads/shops/products/';
                // $extension = $image->getClientOriginalExtension(); // getting image extension
                // $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                // $image->move($destinationPath, $name); // uploading file to given
                // $product->image = $name;
                // $product->save();

                UploadImage('uploads/shops/products',$product,$request);
            }

            if (!empty($product)) {
                flash(__('site.added_successfully'))->success();
                 return response()->json(['success' =>true,], 200);
                //return redirect(route('dashboard.products.index'));

//               session()->flash('success', __('site.added_successfully'));

            }
           
             

        //     DB::commit();
        // } catch (\Exception $e) {
        //     ///Roll the db back if something happened
        //     DB::rollback();
        //     return response([
        //         'status' => 'error',
        //         // 'error' => $e->getMessage(),
        //         'message' => trans('site.Try_again_something_went_wrong.'),
        //     ], 500);
        // }
        // return back();

        // return redirect(route('dashboard.products.index'));
    }

    /*----------------------------------------------------
        || Name     : redirect to edit pages          |
        || Tested   : Done                                    |
        ||                                     |
       ||                                    |
         -----------------------------------------------------*/
    public
    function show($id)
    {
        if (auth()->user()->hasPermission('read_products')) {

            $product = Product::find($id);

            $optiondetails = $product->options ?? '';


            // $related = RelatedProduct::where('product_id', $id)->get();
             $related = Product::query()
            ->select('products.*')
            ->where('related_products.product_id',$id)
            ->join('related_products','related_products.related_product_id','=','products.id')
            ->get();
            // $optionselected = Product_option::where('product_id', $id)->pluck('option_id')->toArray();

            $optionselected = Option::where('product_id', $id)->get();
// return $optionselected;

            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
                // $productrelated = Product::where('shop_id', Session::get('shop_id'))->get();
                $options = Option::where('shop_id', Session::get('shop_id'))->where('product_id', $id)->get();
            } else {
                // $productrelated = Product::get();
                $options = Option::where('product_id', $id)->get();

            }

            return view('dashboard.products.show', compact('optiondetails', 'optionselected', 'product', 'options', 'related'));
        } else {

            session()->flash('success', __('site.notaccesspermisssions'));
            return redirect(url('/dashboard'));

        }

    }

    /*----------------------------------------------------
       || Name     : getshopProduct using shop_id          |
       || Tested   : Done                                    |
       ||                                     |
      ||                                    |
        -----------------------------------------------------*/

    public function getshopProduct($id)
    {

        $products = Product::where('shop_id', '=', $id)->get();


        return Response::json($products);
    }


    /*----------------------------------------------------
     || Name     : update data into database using Product        |
     || Tested   : Done                                    |
       ||                                     |
        ||                                    |
           -----------------------------------------------------*/

    public function update($id, Request $request)
    {

        $request->validate([
                'name_ar' => 'required',
                'name_en' => 'required',
                'price' => 'required',
                'category_id' => 'required',
                'code' => 'required',
//                'shop_id' => 'required',
            ]

        );
        // DB::beginTransaction();
        // try {
        $product = Product::find($id);

        $data = $product->update($request->except('fields', 'shop_id', 'quantity', 'images', 'related_product_id'));

        $options = Option::where('product_id', $id)->get();


        if (!empty($request->fields)) {

//----------------------------------------------
            //  foreach ($field['en']['value'] as $k => $value) {

            //               $variants   =  variants::where('id',$field['id'][$k])->first();
            //                 $image= $variants->image;

            //                 //   $field['images'].push($image);


            // }
//------------------------------------------------
            foreach ($options as $option) {

                Product_option::where('option_id', $option->id)->where('product_id', $id)->delete();
                $option->variants()->delete();//option has many variants

                Option::where('id', $option->id)->first()->delete();

            }

            foreach ($request->fields as $key => $field) {

//            return $field;

                $option = Option::create([
                    'name_ar' => $field['ar']['name'][0],
                    'reference_name' => $field['ar']['reference_name'][0],
                    'name_en' => $field['en']['name'][0],
                    'type' => $field['option_type'][0],
                    'shop_id' => $request['shop_id'][0],
                    'product_id' => $product->id

                ]);

//                $product->options()->sync($option->id);


                Product_option::create([
                    'option_id' => $option->id,
                    'product_id' => $product->id,

                ]);


                if (isset($field['en']['value'])) {
//                    Variant::where('option_id',$option->id)->delete();
//                    $option->variants()->delete();

                    foreach ($product->options as $optionss) {

                        $optionss->variants()->delete();//option has many variants

                    }
                    foreach ($field['en']['value'] as $k => $value) {

                        // return $field['id'][$k];
                        $old_image = '';

                        if (!empty($field['id'][$k])) {

                            $old_variants = Variant::where('id', $field['id'][$k])->withTrashed()->first();
                            // return $old_variants;
                            if (!empty($old_variants))
                                $old_image = $old_variants->image ?? '';

                        }

                        $variant = Variant::create([
                            'name_ar' => $field['ar']['value'][$k],
                            'option_id' => $option->id,
                            'name_en' => $field['en']['value'][$k],
                            'extra_price' => $field['add_price'][$k],
                            'image' => $old_image

                        ]);


                        if (isset($field['images'][$k])) {

                            $image = $field['images'][$k];


//                        foreach ($imagess as $image) {


                            $destinationPath = 'uploads/shops/options/';
                            $extension = $image->getClientOriginalExtension(); // getting image extension
                            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
                            $image->move($destinationPath, $name); // uploading file to given
                            $variant->image = $name;

                            $variant->save();

//                        }

                        }


                    }

                }
            }


        }


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
        if (!empty($request->option_id)) {
//            Product_option::where('product_id', $product->id)->delete();
            foreach ($request->option_id as $key => $id) {

                if ($id == 'nodataselect') {

                } else {

                    $product->options()->sync($request['option_id'][$key]);


                }
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
            // $image = $request->file('image');
            // $destinationPath = 'uploads/shops/products/';
            // $extension = $image->getClientOriginalExtension(); // getting image extension
            // $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            // $image->move($destinationPath, $name); // uploading file to given
            // $product->image = $name;
            // $product->save();

            UploadImage('uploads/shops/products',$product,$request);
        }

        if (!empty($data)) {
            flash(__('site.updated_successfully'))->success();

//            session()->flash('success', __('site.updated_successfully'));
            return redirect(route('dashboard.products.index'));


//            return back();


        }

        //     DB::commit();
        // } catch (\Exception $e) {
        //     ///Roll the db back if something happened
        //     DB::rollback();
        //     return response([
        //         'status' => 'error',
        //         // 'error' => $e->getMessage(),
        //         'message' => trans('site.Try_again_something_went_wrong.'),
        //     ], 500);
        // }
        // return back();
    }


    /*----------------------------------------------------
   || Name     : delete data into database using Product        |
   || Tested   : Done                                    |
   ||                                     |
   ||                                    |
     -----------------------------------------------------*/

    public function destroy($id)
    {
        $res = RelatedProduct::where('product_id', $id)->delete();
        $res = ShopProduct::where('product_id', $id)->delete();
        $res = Product_option::where('product_id', $id)->delete();
        $res = ProductRating::where('product_id', $id)->delete();
        $res = Cart_product_option::where('product_id', $id)->delete();
        $res = Cart::where('product_id', $id)->delete();


        $product = Product::find($id);
        $product->delete();


        flash(__('site.deleted_successfully'))->success();
        return back();

    }//end of destroy


    public function deleteImage(Request $request)
    {
        $input = $request->all();
        $delete_image = $input['image_id'];
        $img = \App\Models\Image::where('id', $delete_image)->first();
        if (isset($img)) {
            $img->delete();
        }
    }
}//end of controller
