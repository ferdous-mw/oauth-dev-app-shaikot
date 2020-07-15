<?php

namespace Modules\Shopify\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Redirect;

class ShopifyController extends Controller
{

    public function Oauth_authentication_approval(Request $request)
    {
        $shop_name = $request->shop;
        $client_id = config('shopify.apiCredentials.client_id');
        $scopes = config('shopify.apiCredentials.scopes');
        $redirect_url = config('app.url') . "shopify/Oauth_approval_success";
        $state = Str::random(16);

        $request_url_for_approve = "https://{$shop_name}/admin/oauth/authorize?client_id={$client_id}&scope={$scopes}&redirect_uri={$redirect_url}&state={$state}&grant_options[]=per-user";

        return Redirect::to($request_url_for_approve);
    }

    public function Oauth_approval_success(Request $request)
    {
        $client_id = config('shopify.apiCredentials.client_id');
        $client_secret = config('shopify.apiCredentials.client_secret');
        $code = $request->code;

        $credentials = array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code' => $code,
        );

        $shop_name = $request->shop;
        $access_token_url = "https://{$shop_name}/admin/oauth/access_token";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $access_token_url);
        curl_setopt($curl, CURLOPT_POST, count($credentials));
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($credentials));

        $response = curl_exec($curl);
        curl_close($curl);

        if(preg_match("/OK/i", $response))
        {   
            $result = json_decode($response, true);
            $access_token = $result['access_token'];
        }
        else
        {
            return $response->body();
        }
    }
}
