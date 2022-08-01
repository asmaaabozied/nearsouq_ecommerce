<?php

namespace App\Imports;
use App\Models\Product;
use App\Models\Product_option;
use App\Models\ShopProduct;
use Maatwebsite\Excel\Concerns\ToModel;
class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $product= Product::create([
            'name_ar'     => $row[0] ?? '',
            'name_en'    => $row[1] ?? '',
            'price' => $row[2] ?? '',
            'discount_price' => $row[3] ?? '',
            'code' => $row[4] ?? '',
            'desc_ar' => $row[5] ?? '',
            'desc_en' => $row[6] ?? '',
            'category_id' => $row[7] ?? '',
            'unit' => $row[8] ?? '',
            'published' => 1,
            'package_count' => 1,


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
