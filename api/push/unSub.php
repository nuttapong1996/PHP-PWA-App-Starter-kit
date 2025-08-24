<?php

use App\Controllers\Push\PushController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json charset=utf-8');
header('Access-Control-Allow-Methods: POST');

$root = dirname(__DIR__ ,2);
require_once $root . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret_key = $_ENV['SECRET_KEY'];
$app_name   = $_ENV['APP_NAME'];

$PushController = new PushController();

$input = json_decode(file_get_contents('php://input'), true);

$access_token_name = $app_name . '_access_token';
$access_token      = $_COOKIE[$access_token_name] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($access_token) && isset($input['endpoint'])) {

        $decoded = JWT::decode($access_token, new Key($secret_key, 'HS256'));

        $usercode = $decoded->data->user_code;
        $endPoint = $input['endpoint'];

        $stmt = $PushController->deleteSubByUser($usercode, $endPoint);

        http_response_code(200);
        echo json_encode([
            'code'    => 200,
            'status'  => 'success',
            'title'   => 'Unsubscribed',
            'message' => 'You have successfully unsubscribed to notifications',
        ]);
        if ($stmt) {

        }
        else{
            http_response_code(400);
            echo json_encode([
                'code' =>400,
                'status' => 'error',
                'title' => 'Failed to unsubscribe',
                'message' => 'Failed to unsubscribe to notifications',
            ]);
        }
    }
    else {
        http_response_code(400);
        echo json_encode([
            'code'    => 400,
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
