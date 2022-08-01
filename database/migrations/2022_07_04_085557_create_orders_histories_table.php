<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->foreignId('order_details_id')->nullable()->constrained('order_details');
            $table->foreignId('shop_id')->nullable()->constrained('shops');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->enum('status', ['RECEIVED', 'READY', 'SHIPPED', 'DELIVERED', 'CANCELED', 'RETURNED', 'CANCELLED_ACCEPTED', 'CANCELLED_DENIED', 'RETURNED_ACCEPTED', 'RETURNED_DENIED', 'APPROVED_BY_CAPTAIN', 'NOT_DELIVERED'])->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->integer('processed_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_histories');
    }
}
