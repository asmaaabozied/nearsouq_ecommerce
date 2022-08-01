<?php

namespace App\Imports;
use App\Models\Product;
use App\Models\Product_option;
use App\Models\ShopProduct;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $shop_id=Session::get('shop_id')  ?? '';

        $product= Product::create([
            'name_ar'     => $row['name_ar'] ?? '',
            'name_en'    => $row['name_en'] ?? '',
            'price' => $row['price'] ?? '',
            'discount_price' => $row['discount_price'] ?? '',
            'code' => $row['code'] ?? '',
            'desc_ar' => $row['desc_ar'] ?? '',
            'desc_en' => $row['desc_en'] ?? '',
            'category_id' => $row['category_id'] ?? '',
            'unit' => $row['unit'] ?? '',
            'published' => 1,
            'package_count' => 1,
            'shop_id'=>$shop_id,


            'weight' => 0,
            'can_delivery' => 1,

            'extras' => 0,
        ]);



        if (isset($row['shops'])) {
            ShopProduct::create([
                'product_id' => $product->id,
                'quantity' => $row['quantity'],
                'shop_id' => $row['shop_id'],

            ]);
        }
        if (isset($row['option'])) {
            Product_option::create([
                'product_id' => $product->id,
                'option_id' => $row['option_id'],

            ]);
        }
    }
}
