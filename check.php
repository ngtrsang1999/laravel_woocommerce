<?php
var_dump($_POST);
$store_url = 'https://xuong88.com/';
$endpoint = '/wc-auth/v1/authorize';
$params = [
    'app_name' => 'laravel-woo',
    'scope' => 'write',
    'user_id' => 1,
    'return_url' => 'http://xuongsx.com/',
    'callback_url' => 'https://woocommerce.com/demo'
];
$query_string = http_build_query( $params );

// echo $store_url . $endpoint . '?' . $query_string;
?>
