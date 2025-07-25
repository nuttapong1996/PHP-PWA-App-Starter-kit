<?php


$root = str_replace('api\user','',__DIR__);
require_once $root . 'middleware\jwtMiddleware.php';



header('Content-Type: application/json');

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$user = checkAccessToken($authHeader); // คืนค่า user หรือหยุดด้วย 401

echo json_encode([
    'message' => 'Token valid',
    'user' => $user
]);
