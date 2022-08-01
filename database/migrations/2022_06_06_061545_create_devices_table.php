<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->enum('brand_name', ['SAMSUNG', 'HAWAWI','APPLE','OPPO','REALME','XIAOMI','HONOR','SICO'])->nullable();
            $table->string('oauth_access_tokens_id')->nullable();
            $table->enum('platform', ['IOS', 'ANDROID'])->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->date('last_login_date')->nullable();
            $table->string('device_id')->nullable();
            $table->enum('login_status', ['LOGIN', 'SIGNOUT'])->nullable();
            $table->string('one_signal_id')->nullable();
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
        Schema::dropIfExists('devices');
    }
}
