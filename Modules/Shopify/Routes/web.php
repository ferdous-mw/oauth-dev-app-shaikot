<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('shopify')->group(function() 
{
    Route::get('/', 'ShopifyController@index');
    Route::get('/Oauth_authentication_approval', 'ShopifyController@Oauth_authentication_approval');
    Route::get('/Oauth_approval_success', 'ShopifyController@Oauth_approval_success');
});
