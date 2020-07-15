<?php

namespace Modules\Shopify\Http\Controllers;

use Auth;
use Redirect;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Shopify\Entities\ShopifyUserAccountDetails;

class ShopifyController extends Controller
{
    public function index()
    {
        $user_details = User::with('shopifyUserDetails')->where('id',Auth::user()->id)->get();
        return view('shopify::index')->with('user_details',$user_details);
    }

    public function  oauthAuthenticationApprove(Request $request)
    {
        $shop_name = $request->shop;
        $client_id = config('shopify.apiCredentials.client_id');
        $scopes = config('shopify.apiCredentials.scopes');
        $redirect_url = config('app.url') . "shopify/oauthApprovalSuccess";
        $state = Str::random(16);

        $request_url_for_approve = "https://{$shop_name}/admin/oauth/authorize?client_id={$client_id}&scope={$scopes}&redirect_uri={$redirect_url}&state={$state}&grant_options[]=per-user";

        return Redirect::to($request_url_for_approve);
    }

    public function oauthApprovalSuccess(Request $request)
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
            $response = json_decode($response, true);

            /* create or update shopify marchent user */
            $user = $this->create_or_update_marchent_user($request, $response);

            /* auto login system user*/
            auth()->login($user, true);

            return redirect()->to('/shopify');

            print_r($user);
            return;
        }
        else
        {
            return $response->body();
        }
    }

    public function create_or_update_marchent_user($request, $response)
    {
        $user = User::firstOrNew(['email' => $response['associated_user']['email']]);
        $user->email = $response['associated_user']['email'];
        $user->password = Hash::make($response['associated_user']['email']);
        $user->name = $response['associated_user']['first_name'] . ' ' . $response['associated_user']['last_name'];
        $user->save();
        $this->saveShopifyUserAccountDetails($user, $request, $response);

        return $user;
    }

    public function saveShopifyUserAccountDetails($user, $request, $response)
    {
        $shopify_details = ShopifyUserAccountDetails::firstOrNew(['user_id' => $user->id, 'store_url' => $request->shop]);
        $shopify_details->user_name = $response['associated_user']['first_name'] . ' ' . $response['associated_user']['last_name'];
        $shopify_details->shopify_user_id = $response['associated_user']['id'];
        $shopify_details->store_url = $request->shop;
        $shopify_details->user_id = $user->id;
        $shopify_details->access_token = $response['access_token'];
        $shopify_details->save();
        session([
            'store_url' => $request->shop
        ]);
    }
}
