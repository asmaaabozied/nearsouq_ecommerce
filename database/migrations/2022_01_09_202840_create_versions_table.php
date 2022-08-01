<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
            $table->string('version_no')->nullable();
            $table->string('change_log_en')->nullable();
            $table->string('change_log_ar')->nullable();
            $table->string('os')->nullable();
            $table->enum('type', ['customer_app', 'delivery_app','vendor_app']);
            $table->date('release_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('build_no')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('versions');
    }
}
