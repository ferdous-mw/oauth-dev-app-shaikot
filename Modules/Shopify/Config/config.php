<?php

return 
[
    'name' => 'Shopify',
    'apiCredentials' => 
    [
    	'client_id' => 'f9fff0543352643063c0f503ead40e03',
    	'client_secret' => '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',
    	'scopes' => 'read_orders,read_customers,read_products,write_orders,write_customers,write_products,read_script_tags,write_script_tags',
    	'store_hash' => session()->get('store_url')
    ]
];
