<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBigCommerceUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('big_commerce_user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name')->comment('Big commerce user name');
            $table->bigInteger('big_commerce_user_id')->comment('Big Commerce user id');
            $table->string('store_hash')->comment('Big commerce store hash');
            $table->string('access_token')->comment('Big commerce store access token');
            $table->unsignedBigInteger('user_id')->comment('user id will foreign with users.id');
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
        Schema::dropIfExists('big_commerce_user_details');
    }
}
