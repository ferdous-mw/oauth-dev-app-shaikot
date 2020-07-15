<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_user_details', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->comment('shopify user name');
            $table->bigInteger('shopify_user_id')->comment('shopify user id');
            $table->string('store_url')->comment('shopify store url');
            $table->string('access_token')->comment('shopify store access token');
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
        Schema::dropIfExists('shopify_user_details');
    }
}
