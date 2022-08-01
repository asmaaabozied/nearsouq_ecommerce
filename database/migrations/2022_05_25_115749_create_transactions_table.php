<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('name_id');
            $table->foreign('name_id')
                ->references('id')
                ->on('transaction_name')
                ->onDelete('cascade');
            $table->integer('debit')->nullable();
            $table->integer('credit')->nullable();
            $table->integer('final_balance')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('shop_id')->nullable()->constrained('shops');
            $table->string('payment_type')->nullable();
            $table->date('transaction_date')->nullable();
            $table->date('updated_date')->nullable();
            $table->foreignId('order_id')->nullable()->constrained('orders');
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
        Schema::dropIfExists('transactions');
    }
}
