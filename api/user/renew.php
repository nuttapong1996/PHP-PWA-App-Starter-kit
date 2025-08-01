<?php

use App\Controllers\Token\TokenController;
use App\Controllers\User\UserController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json;  charset=utf-8');
// header('Access-Control-Allow-Origin: https://app.yourdomain.com');
// header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST');

$root = str_replace('api\user', '', __DIR__);

require_once $root . '\vendor\autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret_key           = $_ENV['SECRET_KEY'];
$issued_at            = time();
$refresh_token_expire = $issued_at + (60 * 60 * 24 * 7); // 7 วัน
$refresh_token_id     = uniqid('TK', true);

$refresh_token_cookie = trim($_COOKIE['myapp_refresh_token'] ?? '');

$TokenController = new TokenController();
$UserController  = new UserController();

$user_ip         = $UserController->getUserIP();
$user_device     = $UserController->getUserDeviceType();

if (! $refresh_token_cookie) {
    http_response_code(401);
    echo json_encode(['error' => ' Invalid or expired Token']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        // Decode Refresh token that store in cookie
        $decoded = JWT::decode($refresh_token_cookie, new Key($secret_key, 'HS256'));

        // Set $user_code from data that decoded from Refresh token
        $usercode         = $decoded->data->user_code;
        $decoded_token_id = $decoded->data->token_id;

        $stmt             = $TokenController->getRefreshToken($usercode);
        $refresh_token_db = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify Refresh Token from cookie with Refresh Token from DB
        $validate_token = password_verify($refresh_token_cookie, $refresh_token_db['token']);

        if ($validate_token === true) {

            // Set Refresh token payload
            $refesh_token_payload = [
                'iat'  => $issued_at,
                'exp'  => $refresh_token_expire,
                'data' => [
                    'user_code' => $usercode,
                    'token_id'  => $decoded_token_id,
                ],
            ];

            // Encoded or create  refresh Token
            $refresh_token = JWT::encode($refesh_token_payload, $secret_key, 'HS256');
            // Hash refresh token for store in DB
            $refresh_token_hash = password_hash($refresh_token, PASSWORD_ARGON2I);

            // Update refresh token in DB
            $TokenController->updateToken($usercode, $decoded_token_id, $refresh_token_hash, $user_device, $user_ip,$refresh_token_expire);

            // Store Refresh token in cookie HttpOnly with secure
            setcookie('myapp_refresh_token', $refresh_token, [
                'expires'  => $refresh_token_expire,
                'path'     => '/',
                'httponly' => true,
                'secure'   => true,
                'samesite' => 'Strict',
            ]);

            http_response_code(200);
            echo json_encode([
                'code'         => 200,
                'status'       => 'success',
                'message'      => 'Token Renewed',
            ]);

        } else {
            http_response_code(400);
            echo json_encode([
                'code'    => 4010,
                'status'  => 'error',
                'message' => 'Invalid or expired refresh token',
            ]);
        }

    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode([
            'code'    => 400,
            'status'  => 'error',
            'message' => 'Token decode error',
            'error'   => $e->getMessage(),
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'code'    => 405,
        'status'  => 'error',
        'message' => 'Method not allowed.',
    ]);
}
