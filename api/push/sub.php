<?php

use App\Controllers\Push\PushController;
use App\Controllers\User\UserController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json; charset=utf-8');

$root = dirname(__DIR__ ,2);
require_once $root . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret_key = $_ENV['SECRET_KEY'];
$app_name   = $_ENV['APP_NAME'];

$PushController = new PushController();
$UserController = new UserController();

// รับค่ามาจาก frontend ในรูปแบบ json
$input = json_decode(file_get_contents('php://input'), true);

$access_token_name = $app_name . '_access_token';
$access_token      = $_COOKIE[$access_token_name] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($access_token) && isset($input['endpoint']) && isset($input['keys'])) {

        $decoded = JWT::decode($access_token, new Key($secret_key, 'HS256'));

        // ประกาศตัวแปร usercode เพื่อเก็บรหัสผู้ใช้จาก access_token
        $usercode = $decoded->data->user_code;

        $userDevice = $UserController->getUserDeviceType();
        $userIp     = $UserController->getUserIP();

        // ประกาศตัวแปร endpoint , p256dh , auth เพื่อเก็บ endpoint , p256dh , auth  ของผู้ใช้ จาก frontend
        $enpoint    = $input['endpoint'];
        $public_key = $input['keys']['p256dh'];
        $auth_key   = $input['keys']['auth'];

        // เรียกใช้งาน function insertSub จาก PushController
        $stmt = $PushController->insertSub($usercode, $userDevice, $userIp, $enpoint, $public_key, $auth_key);

        // ตรวจสอบข้อมูลที่ถูกส่งไปฐานข้อมูล
        if ($stmt) {
            http_response_code(200);
            echo json_encode([
                'code'    => 200,
                'status'  => 'success',
                'title'   => 'Subscribed',
                'message' => 'You have successfully subscribed to notifications',
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'code'    => 400,
                'status'  => 'error',
                'title'   => 'Failed to subscribe',
                'message' => 'Failed to subscribe to notifications',
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'code'    => 200,
            'status'  => 'error',
            'title'   => 'Invalid request',
            'message' => 'Missing required parameters',
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'code'    => 405,
        'status'  => 'error',
        'title'   => 'Method Not Allowed',
        'message' => 'This method is not allowed',
    ]);
}
