<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_details_id')->nullable()->constrained('order_details');
            $table->foreignId('shop_id')->nullable()->constrained('shops');
            $table->foreignId('order_id')->nullable()->constrained('order_details');
            $table->foreignId('captain_id')->nullable()->constrained('users');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->enum('payment_type', ['CASH','BANK_TRANSFER'])->nullable();
            $table->enum('payment_name', ['ORDER','DELIVERY'])->nullable();
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
        Schema::dropIfExists('customer_transactions');
    }
}
