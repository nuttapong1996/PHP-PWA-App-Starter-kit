<?php

use App\Controllers\Token\TokenController;
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

$secret_key          = $_ENV['SECRET_KEY'];
$domain              = $_ENV['APP_DOMAIN'];
$app_name             = $_ENV['APP_NAME'];
$refresh_token_name  = $app_name.'_refresh_token';
$access_token_name  = $app_name.'_access_token';
$issued_at           = time();
$access_token_expire = $issued_at + (60 * 15); // 15 นาที

$refresh_token_cookie = trim($_COOKIE[$refresh_token_name] ?? '');

$tokenController = new TokenController();

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
        $usercode = $decoded->data->user_code;
        $tokenid  = $decoded->data->token_id;

        $stmt             = $tokenController->getRefreshTokenByID($usercode, $tokenid);
        $refresh_token_db = $stmt->fetch(PDO::FETCH_ASSOC);

        $validate_token = password_verify($refresh_token_cookie, $refresh_token_db['token']);

        if ($validate_token === true) {

            $issued_at = time();
            $expire    = $issued_at + (60 * 15); // 15 นาที

            // Set Access token payload
            $access_token_payload = [
                'iss'  =>$domain ,
                'aud'  => $domain ,
                'iat'  => $issued_at,
                'exp'  => $expire,
                'data' => [
                    'user_code' => $usercode,
                ],
            ];

            // Encoded or create  Access Token
            $access_token = JWT::encode($access_token_payload, $secret_key, 'HS256');

            // Store Access token in Cookie HttpOnly with secure
            setcookie($access_token_name, $access_token, [
                'expires'  => $access_token_expire,
                'path'     => '/',
                'httponly' => true,
                'secure'   => true,
                'samesite' => 'Strict',
            ]);

            http_response_code(200);
            echo json_encode([
                'code'         => 200,
                'status'       => 'success',
                'message'      => 'Token refreshed',
                'access_token' => $access_token,
                'expires_in'   => $expire,
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
