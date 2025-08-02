<?php
use App\Controllers\Token\TokenController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header('Content-Type: application/json;  charset=utf-8');
// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$root = str_replace('api\user', '', __DIR__);
require_once $root . 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret_key = $_ENV['SECRET_KEY'];

$refresh_token_cookie = trim($_COOKIE['myapp_refresh_token'] ?? '');

$decode   = JWT::decode($refresh_token_cookie, new Key($secret_key, 'HS256'));
$usercode = $decode->data->user_code;
$tokenid  = $decode->data->token_id;

$TokenController  = new TokenController();
$refresh_token_db = $TokenController->getRefreshTokenByID($usercode, $tokenid);
$token_result_db = $refresh_token_db->fetch(PDO::FETCH_ASSOC);

$valid_token = password_verify($refresh_token_cookie, $token_result_db['token']);

if ($valid_token === true) {
    if (! empty($_COOKIE['myapp_access_token'])) {
        $access_token = trim($_COOKIE['myapp_access_token']);
        http_response_code(200);
        echo json_encode([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Token found.',
            'count'   => 1,
            'respone' => [
                'access_token' => $access_token,
            ],
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'code'    => 401,
            'status'  => 'error',
            'message' => 'Access Token not found.',
            'count'   => 0,
            'respone' => [],
        ]);
    }
}else{
     http_response_code(401);
        echo json_encode([
            'code'    => 401,
            'status'  => 'error',
            'message' => 'Refresh Token not found.',
            'count'   => 0,
            'respone' => [],
        ]);
}
