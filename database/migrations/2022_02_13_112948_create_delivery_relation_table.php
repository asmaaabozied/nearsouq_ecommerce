<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_relation', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')->nullable()->constrained('carts');
            $table->foreignId('deliverycalculator_id')->nullable()->constrained('delivery_calculators');

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
        Schema::dropIfExists('delivery_relation');
    }
}
