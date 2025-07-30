<?php 
header('Content-Type: application/json;  charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

if(!empty($_COOKIE['myapp_access_token'])){
    $access_token = trim($_COOKIE['myapp_access_token']);
    http_response_code(200);
    echo json_encode([
        'code'=> 200,
        'status'=> 'success',
        'message'=> 'Token found.',
        'count' => 1,
        'respone'=>[
            'access_token' => $access_token
        ]
    ]);
}else{
    http_response_code(401);
    echo json_encode([
        'code'=> 401,
        'status'=> 'error',
        'message'=> 'Token not found.',
        'count' => 0,
        'respone'=>[]
    ]);
}





