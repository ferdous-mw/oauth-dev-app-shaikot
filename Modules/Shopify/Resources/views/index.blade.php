@extends('shopify::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module ikbal : {!! config('shopify.apiCredentials.store_hash') !!}
    </p>
@endsection
