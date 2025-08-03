<?php
use App\Controllers\Token\TokenController;
use App\Controllers\User\UserController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// header('Access-Control-Allow-Origin: https://app.yourdomain.com');
// header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json;  charset=utf-8');
header('Access-Control-Allow-Methods: POST');

$root = str_replace('api\user', '', __DIR__);
require_once $root . 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret_key = $_ENV['SECRET_KEY'];
$domain              = $_ENV['APP_DOMAIN'];
$app_name             = $_ENV['APP_NAME'];
$refresh_token_name  = $app_name.'_refresh_token';
$access_token_name  = $app_name.'_access_token';
$issued_at            = time();
$access_token_expire  = $issued_at + (60 * 15);          // 15 นาที
$refresh_token_expire = $issued_at + (60 * 60 * 24 * 7); // 7 วัน

$refresh_token_cookie = trim($_COOKIE[$refresh_token_name] ?? '');

$decode   = JWT::decode($refresh_token_cookie, new Key($secret_key, 'HS256'));
$usercode = $decode->data->user_code;
$tokenid  = $decode->data->token_id;

$TokenController = new TokenController();
$UserController  = new UserController();

$user_ip     = $UserController->getUserIP();
$user_device = $UserController->getUserDeviceType();

$refresh_token_db = $TokenController->getRefreshTokenByID($usercode, $tokenid);
$token_result_db  = $refresh_token_db->fetch(PDO::FETCH_ASSOC);

$valid_token = password_verify($refresh_token_cookie, $token_result_db['token']);


if(empty($refresh_token_cookie)){
    http_response_code(401);
    echo json_encode([
        'code' => 401,
        'status' => 'error',
        'message' => 'No Token found'
    ]);
}

// Validate Refresh token in cookie and DB.
if ($valid_token === true) {

    // Check Access token if it exist send status success .
    if (! empty($_COOKIE[$access_token_name ])) {
        $access_token = trim($_COOKIE[$access_token_name]);

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
        // If Access token not exist the go check on Refresh token that store in cookie.
    } else {
        // Check Refresh token in cookie if exist  then renew Refresh token and refresh Acess token.
        if (! empty($refresh_token_cookie)) {

            // Set Access token payload
            $access_token_payload = [
                'iss'  => $domain ,
                'aud'  => $domain ,
                'iat'  => $issued_at,
                'exp'  => $access_token_expire,
                'data' => [
                    'user_code' => $usercode,
                ],
            ];

            // Set Refresh token payload
            $refesh_token_payload = [
                'iss'  => $domain ,
                'aud'  => $domain ,
                'iat'  => $issued_at,
                'exp'  => $refresh_token_expire,
                'data' => [
                    'user_code' => $usercode,
                    'token_id'  => $tokenid,
                ],
            ];

            // Encoded or create  Access Token and Refresh Token
            $access_token       = JWT::encode($access_token_payload, $secret_key, 'HS256');
            $refresh_token      = JWT::encode($refesh_token_payload, $secret_key, 'HS256');
            $refresh_token_hash = password_hash($refresh_token, PASSWORD_ARGON2I);

            // Update refresh token in DB
            $TokenController->updateToken($usercode, $tokenid, $refresh_token_hash, $user_device, $user_ip, date('Y-m-d H:i:s', $refresh_token_expire));

            // Store Access token in cookie HttpOnly with secure
            setcookie($access_token_name, $access_token, [
                'expires'  => $access_token_expire,
                'path'     => '/',
                'httponly' => true,
                'secure'   => true,
                'samesite' => 'Strict',
            ]);

            // Store Refresh token in cookie HttpOnly with secure
            setcookie($refresh_token_name , $refresh_token, [
                'expires'  => $refresh_token_expire,
                'path'     => '/',
                'httponly' => true,
                'secure'   => true,
                'samesite' => 'Strict',
            ]);
            echo json_encode([
                'code'    => 200,
                'status'  => 'success',
                'message' => 'Token Refreshed!',
                'respone' => [
                    'access_token' => $access_token,
                ],
            ]);
            // If Refresh token not exist in cookie then send code 401 let frontend handle route to login page.
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
    }
} else {
    http_response_code(401);
    echo json_encode([
        'code'    => 401,
        'status'  => 'error',
        'message' => 'Refresh Token not found.',
        'count'   => 0,
        'respone' => [],
    ]);
}
