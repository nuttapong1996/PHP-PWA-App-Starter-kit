<?php

use App\Controllers\Token\TokenController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');
header('Acccess-Control-Allow-Methods: POST');

$root = dirname(__DIR__, 2);
require $root . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret            = $_ENV['SECRET_KEY'];
$access_token_name = $_ENV['APP_NAME'] . '_access_token';

$TokenController = new TokenController;

$access_token = trim($_COOKIE[$access_token_name] ?? '');

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($input['tokenid'])) {
        try {
            $decode   = JWT::decode($access_token, new Key($secret, 'HS256'));
            $usercode = $decode->data->user_code;
            $tokenid  = $input['tokenid'];

            $stmt = $TokenController->deleteTokenByID($usercode, $tokenid);

            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode([
                    'code'    => 200,
                    'status'  => 'success',
                    'title'   => 'Token deleted!',
                    'message' => 'You have successfully delete the device token.',
                ]);
                exit;
            } else {
                http_response_code(400);
                echo json_encode([
                    'code'   => 400,
                    'status' => 'error',
                    'error'  => 'Failed to delete token.',
                ]);
                exit;
            }

        } catch (\Firebase\JWT\ExpiredException) {
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'stauts'  => 'error',
                'message' => 'Token is invalid or expired.',
                'error'   => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'stauts'  => 'error',
                'message' => 'Token decode error',
                'error'   => $e->getMessage(),
            ]);
        }
    } else {
        http_response_code(401);
        echo json_encode([
            'code'    => 401,
            'stauts'  => 'error',
            'message' => 'Invalid request',
            'error'   => 'Token ID is require.',
        ]);
        exit;
    }

} else {
    http_response_code(405);
    echo json_encode([
        'code'   => 405,
        'stauts' => 'error',
        'title'  => 'Invalid Method',
        'error'  => 'This Method is not allowed.',
    ]);
}
