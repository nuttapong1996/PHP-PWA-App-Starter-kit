<?php

// use App\Controllers\Auth\AuthController;
use App\Controllers\User\UserController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

$root = dirname(__DIR__, 2);
require_once $root . '/vendor/autoload.php';

$input = json_decode(file_get_contents('php://input'), true);

// $AuthController = new AuthController();
$UserController = new UserController();

$access_token_name = $_ENV['APP_NAME'] . '_access_token';
$secret_key        = $_ENV['SECRET_KEY'];

if (isset($_COOKIE[$access_token_name])) {
    $access_token = $_COOKIE[$access_token_name];
} else {
    http_response_code(400);
    echo json_encode([
        'code'    => 400,
        'status'  => 'error',
        'title'   => 'Invalid access token',
        'message' => 'Access token is required',
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($input['OldPass'])) {

        $decode = JWT::decode($access_token, new Key($secret_key, 'HS256'));
        $usercode = $decode->data->user_code;

        $stmt = $UserController->getUserProfileByCode($usercode);

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($input['OldPass'], $result['password'])) {
                http_response_code(200);
                echo json_encode([
                    'code'    => 200,
                    'status'  => 'valid',
                    'title'   => 'Valid',
                    'message' => 'Current Password is valid',
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'code'    => 400,
                    'status'  => 'invalid',
                    'title'   => 'invalid',
                    'message' => 'Current Password is invalid',
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                'code'    => 400,
                'status'  => 'error',
                'title'   => 'Not found',
                'message' => 'User code not found.',
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'code'    => 400,
            'status'  => 'error',
            'title'   => 'Invalid input',
            'message' => 'Missing required input',
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'code'    => 405,
        'status'  => 'error',
        'title'   => 'Not allowed',
        'message' => 'Method is not allowed',
    ]);
}
