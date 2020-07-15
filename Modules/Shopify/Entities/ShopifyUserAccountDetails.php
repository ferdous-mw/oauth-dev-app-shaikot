<?php

namespace Modules\Shopify\Entities;

use Illuminate\Database\Eloquent\Model;

class ShopifyUserAccountDetails extends Model
{
	protected $table = "shopify_user_details";
	
    protected $fillable = ['user_id', 'store_url'];
}
