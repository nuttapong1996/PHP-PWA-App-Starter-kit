<?php

use App\Controllers\Push\PushController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json charset=utf-8');
header('Access-Control-Allow-Methods: POST');

$root = str_replace('api\push', '', __DIR__);
require_once $root . 'vendor\autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret_key = $_ENV['SECRET_KEY'];
$app_name   = $_ENV['APP_NAME'];

$PushController = new PushController();

// รับค่ามาจาก frontend ในรูปแบบ json
$input = json_decode(file_get_contents('php://input'), true);

$access_token_name  = $app_name . '_access_token';
$access_token = $_COOKIE[$access_token_name] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($access_token) && isset($input['endpoint'])) {

        $decoded   = JWT::decode($access_token, new Key($secret_key, 'HS256'));

         // ประกาศตัวแปร usercode เพื่อเก็บรหัสผู้ใช้จาก access_token
        $usercode = $decoded->data->user_code;

        // ประกาศตัวแปร endpoint เพื่อเก็บ endpoint ของผู้ใช้ จาก frontend
        $endpoint = $input['endpoint'];

        // ทำการเรียก function getSub จาก PushController
        $stmt = $PushController->getSubByUserID($usercode , $endpoint);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ตรวจสอบข้อมูลที่ถูกดึงมาว่ามีหรือไม่
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode([
                'code'    => 200,
                'status'  => 'sub',
                'title'   => 'Subscribed',
                'message' => 'You are already subscribed',
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                'code'    => 200,
                'status'  => 'not sub',
                'title'   => 'Not subscribe yet',
                'message' => 'You are not subscribe yet',
            ]);
        }
    } else {
        http_response_code(401);
        echo json_encode([
            'code'    => 401,
            'status'  => 'Unauthorized',
            'title'   => 'Unauthorized Access',
            'message' => 'Please login',
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'code'    => '405',
        'status'  => 'error',
        'title'   => 'Method Not Allowed',
        'message' => 'This method is not allowed',
    ]);
}
