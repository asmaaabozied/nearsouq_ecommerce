<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->foreignId('shop_id')->nullable()->constrained('shops');
            $table->foreignId('category_id')->nullable()->constrained('categories');

            $table->string('price')->nullable();
            $table->string('discount_price')->nullable();
            $table->string('code')->nullable();
            $table->string('desc_ar')->nullable();
            $table->string('desc_en')->nullable();
            $table->string('image')->nullable();
            $table->string('ingredients_ar')->nullable();
            $table->string('ingredients_en')->nullable();
            $table->string('unit')->nullable();
            $table->string('package_count')->nullable();
            $table->string('weight')->nullable();
            $table->string('can_delivery')->nullable();
            $table->enum('published', ['TRUE', 'FALSE'])->nullable();
            $table->string('extras')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->userstamps();
            $table->softUserstamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
