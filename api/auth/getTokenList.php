<?php

use App\Controllers\Token\TokenController;
use App\Controllers\User\UserController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

$TokenController = new TokenController();
$UserController  = new UserController();

$root = dirname(__DIR__, 2);
require_once $root . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$input = json_decode(file_get_contents('php://input'), true);

$secret_key        = $_ENV['SECRET_KEY'];
$access_token_name = $_ENV['APP_NAME'] . '_access_token';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_COOKIE[$access_token_name])) {

        $access_token = $_COOKIE[$access_token_name];
        $decode       = JWT::decode($access_token, new Key($secret_key, 'HS256'));
        $usercode     = $decode->data->user_code;

        $localIP = $UserController->getUserIP();

        $stmt = $TokenController->getRefreshTokenList($usercode);

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            $arr             = [];
            $arr['response'] = [];
            $arr['code']     = 200;
            $arr['status']   = 'success';
            $arr['title']    = 'Success';
            $arr['message']  = $stmt->rowCount() . ' records';
            $arr['count']    = $stmt->rowCount();

            while ($result = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($result as $row) {
                    $row['device_name'] = $row['device_name'];
                    $row['ip_address']  = $row['ip_address'];
                    $row['expires_at']  = $row['expires_at'];
                    if ($row['ip_address'] == $localIP) {
                        $row['remark'] = '(Current Device)';
                    } else {
                        $row['remark'] = '<button id="btnLogout" class="btn btn-danger btn-sm" data-endpoint="' . $row['token_id'] . '">Log out</button>';
                    }
                    $arr['response'][] = $row;
                }

            }
            echo json_encode($arr);
        } else {
            http_response_code(200);
            echo json_encode([
                'code'    => 200,
                'status'  => 'success',
                'title'   => 'Not found',
                'message' => 'Token not found',
            ]);
        }
    }

} else {
    http_response_code(405);
    echo json_encode([
        'code'    => 405,
        'status'  => 'error',
        'title'   => 'Invalid method',
        'message' => 'Request method is not allowed',
    ]);
    exit;
}
