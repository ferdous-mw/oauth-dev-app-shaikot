@extends('shopify::layouts.master')

@section('content')
    <pre>
    	WELCOME: SHOPIFY ACCOUNT USER
    	your credentials are:
    	Fullname : {{ $user_details[0]['name']}};
    	Email : {{ $user_details[0]['email']}};
    	Store Name : {{ $user_details[0]['shopifyUserDetails'][0]['store_url']}};
    	Access Token : {{ $user_details[0]['shopifyUserDetails'][0]['access_token']}};
    </pre>
@endsection
