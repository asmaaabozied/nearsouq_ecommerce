<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('image')->nullable();
            $table->enum('type', ['INAPP', 'OUTSIDE_APP','IN_DIALOG'])->nullable();
            $table->enum('position', ['TOP', 'DOWN'])->nullable();
            $table->string('banner_url')->nullable();
            $table->string('details_ar')->nullable();
            $table->string('details_en')->nullable();
            $table->boolean('visible')->nullable();
            $table->foreignId('shop_id')->nullable()->constrained('shops');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->foreignId('product_id')->nullable()->constrained('products');

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
        Schema::dropIfExists('banners');
    }
}
