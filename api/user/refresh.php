<?php
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$root = str_replace('\api\user', '', __DIR__);

require_once $root . '\vendor\autoload.php';
require_once $root . '\configs\connect_db.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret_key = $_ENV['SECRET_KEY'];


$refresh_token = trim($_COOKIE['refresh_token'] ?? '');


// error_log('Refresh token from cookie: ' . ($_COOKIE['refresh_token'] ?? 'null'));
// exit;

if (! $refresh_token) {
    http_response_code(400);
    echo json_encode(['error' => 'No refresh token']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

try {
    // ตรวจสอบ JWT
    $decoded = JWT::decode($refresh_token, new Key($secret_key, 'HS256'));
    $user_code = $decoded->data->user_code;


    // ตรวจสอบว่ามี refresh token นี้ในฐานข้อมูลหรือไม่ และยังไม่หมดอายุ
    $stmt = $conn->prepare("SELECT * FROM refresh_tokens WHERE user_code = :usercode AND token = :token AND expires_at > NOW()");
    // $stmt = $conn->prepare("SELECT * FROM refresh_tokens WHERE user_code = :usercode AND expires_at > NOW()");
    $stmt->execute([
        ':usercode'   => $user_code,
        ':token' => $refresh_token,
    ]);
    $token_row = $stmt->fetch(PDO::FETCH_ASSOC);

    //  echo json_encode([
    //     'token_cookie' => $refresh_token,
    //  ]);

    // echo json_encode([
    //     'token_db' => $token_row['token'],
    // ]);
    // exit;

    if (! $token_row) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid or expired refresh token']);
        exit;
    }

    // สร้าง access token ใหม่

    $issued_at = time();
    $expire    = $issued_at + (60 * 15); // 15 นาที

    $access_token = JWT::encode([
        'iss'  => 'yourdomain.com',
        'aud'  => 'yourdomain.com',
        'iat'  => $issued_at,
        'exp'  => $expire,
        'data' => ['user_code' => $user_code],
    ], $secret_key, 'HS256');

    echo json_encode([
        'access_token' => $access_token,
        'expires_in'   => $expire,
    ]);
} catch (Exception $e) {
    http_response_code(403);
    echo json_encode(['error' => 'Token decode error']);
}
