<?php

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json charset=utf-8');

$root = str_replace('api\push', '', __DIR__);
require_once $root . 'vendor\autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

// รับค่ามาจาก frontend ในรูปแบบ json
$input = json_decode(file_get_contents('php://input'), true);

$access_token = $_COOKIE['access_token'] ?? null;

$secret_key = $_ENV['SECRET_KEY'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($access_token) && isset($input['endpoint'])) {

        require $root . '\configs\connect_db.php';

        $decoded   = JWT::decode($access_token, new Key($secret_key, 'HS256'));
        $user_code = $decoded->data->user_code;

        // ประกาศตัวแปร usercode เพื่อเก็บรหัสผู้ใช้จาก access_token
        $usercode = $user_code;

        // ประกาศตัวแปร endpoint เพื่อเก็บ endpoint ของผู้ใช้ จาก frontend
        $endpoint = $input['endpoint'];

        $sql  = 'SELECT endpoint , p256dh , authKey FROM push_subscribers WHERE user_code = :usercode AND endpoint = :endpoint';
        $stmt = $conn->prepare($sql);
        // เชื่อมต่อตัวแปร username , endpoint
        $stmt->bindParam(':usercode', $usercode, PDO::PARAM_STR);
        $stmt->bindParam(':endpoint', $endpoint, PDO::PARAM_STR);
        $stmt->execute();
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
    http_response_code(400);
    echo json_encode([
        'code'    => '400',
        'status'  => 'Bad request',
        'title'   => 'Bad request',
        'message' => 'Invalid request',
    ]);
}
