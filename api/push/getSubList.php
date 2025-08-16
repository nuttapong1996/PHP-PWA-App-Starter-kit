<?php

use App\Controllers\Push\PushController;
use App\Controllers\User\UserController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET');

$root = str_replace('api\push', '', __DIR__);
require_once $root . 'vendor\autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret_key = $_ENV['SECRET_KEY'];
$app_name   = $_ENV['APP_NAME'];

$access_token_name = $app_name . '_access_token';
$access_token      = $_COOKIE[$access_token_name] ?? null;

$PushController = new PushController();
$UserController = new UserController();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($access_token)) {

        $decoded  = JWT::decode($access_token, new Key($secret_key, 'HS256'));
        $usercode = $decoded->data->user_code;

        $localIP = $UserController->getUserIP();

        $stmt        = $PushController->getAllSubByUserID($usercode);
        $resultCount = $stmt->rowCount();

        if ($resultCount > 0) {
            http_response_code(200);
            $arr             = [];
            $arr['response'] = [];
            $arr['count']    = $resultCount;
            $arr['code']     = 200;
            $arr['status']   = 'success';
            $arr['message']  = $resultCount . ' records';

            while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {

                foreach ($row as $rows) {
                    
                    $rows['create_at']   = $rows['create_at'];
                    $rows['ip_address'] = $rows['ip_address'];
                    if ($rows['ip_address'] == $localIP) {
                        // $rows['device_name'] = $rows['device_name'];
                        $rows['device_name'] .= '<br>(Current Device)';
                    }
                    $arr['response'][] = $rows;
                }
            }
            echo json_encode($arr);
        } else {
            // http_response_code(400);
            echo json_encode([
                'code'    => 400,
                'status'  => 'error',
                'title'   => 'Not found',
                'message' => 'No subscription found',
            ]);
        }

    } else {
        http_response_code(401);
        echo json_encode([
            'code'    => 401,
            'status'  => 'unauthorized',
            'title'   => 'Unauthorized Access',
            'message' => 'Please login',
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
