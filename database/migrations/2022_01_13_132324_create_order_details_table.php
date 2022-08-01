<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['RECEIVED', 'READY', 'SHIPPED', 'DELIVERED', 'CANCELED', 'RETURNED'])->nullable();
            $table->enum('active', ['NOT_PAID', 'PAID'])->nullable();
            $table->string('image')->nullable();
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('shop_id')->nullable()->constrained('shops');
            $table->foreignId('reason_id')->nullable()->constrained('reason');

            $table->string('price')->nullable();
            $table->string('discount_price')->nullable();
            $table->string('vat')->nullable();
            $table->string('vat_value')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('commsion_value')->nullable();
            $table->string('commsion')->nullable();
            $table->string('delivery_date')->nullable();
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('order_details');
    }
}
