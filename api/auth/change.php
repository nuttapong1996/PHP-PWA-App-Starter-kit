<?php

use App\Controllers\Auth\AuthController;
use App\Controllers\Token\TokenController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type : application/json');
header('Access-Control-Allow-Methods: POST');

$root = dirname(__DIR__, 2);
require_once $root . '/vendor/autoload.php';

$input = json_decode(file_get_contents('php://input'), true);

$AuthController  = new AuthController();
$TokenController = new TokenController();

$access_token_name = $_ENV['APP_NAME'] . '_access_token';
$secret_key        = $_ENV['SECRET_KEY'];

if (! $_COOKIE[$access_token_name]) {
    http_response_code(400);
    echo json_encode([
        'code'    => 400,
        'status'  => 'error',
        'title'   => 'Invalid access token',
        'message' => 'Access token is required',
    ]);
    exit;
}

$access_token = $_COOKIE['$access_token_name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($input['NewPass'])) {
        $decode   = JWT::decode($access_token, new Key($secret_key, 'HS256'));
        $usercode = $decode->data->user_code;

        $stmt = $AuthController->login($usercode);

        if ($stmt->rowCount() > 0) {
            $stmt_reset = $AuthController->reset($usercode, $newpass);
            if ($stmt_reset->rowCount() > 0) {
                http_response_code(200);
                echo json_encode([
                    'code'    => 200,
                    'status'  => 'success',
                    'title'   => 'Change password successfully',
                    'message' => 'Password has been changed successfully',
                ]);
                $TokenController->deleteAllToken($usercode);
            } else {
                http_response_code(400);
                echo json_encode([
                    'code'    => 400,
                    'status'  => 'error',
                    'title'   => 'Could not change password',
                    'message' => 'Failed to change password',
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                'code'    => 400,
                'status'  => 'error',
                'title'   => 'Not found',
                'message' => 'User not found.',
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
