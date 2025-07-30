<?php
use App\Controllers\Token\TokenController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$root = str_replace('api\user', '', __DIR__);
require_once $root . 'vendor\autoload.php';

$dotevn = Dotenv::createImmutable($root);
$dotevn->load();

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$refresh_token_cookie = trim($_COOKIE['myapp_refresh_token']);
$secret_key           = $_ENV['SECRET_KEY'];

if (! $refresh_token_cookie) {
    http_response_code(401);
    echo json_encode([
        'code'    => 401,
        'status'  => 'error',
        'message' => 'Refresh Token not found.',
    ]);
}

$token_decoded = JWT::decode($refresh_token_cookie, new Key($secret_key, 'HS256'));
$usercode      = $token_decoded->data->user_code;

try {

    $TokenController = new TokenController();
    $stmt            = $TokenController->getExpiresToken($usercode);
    $result          = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $expiresAtTs = strtotime($result['expires_at']);
        $remark      = 'Expires';

        if ($expiresAtTs <= time()) {
            $update = $TokenController->updateRevokeToken($usercode, $remark);
            if ($update) {
                http_response_code(200);
                echo json_encode([
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Token revoked',
                ]);
            }
        }
    }

} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode([
        'code'    => 400,
        'status'  => 'error',
        'message' => 'Token revoke error',
    ]);
}

// }else{
//     http_response_code(405);
//     echo json_encode([
//         'code' => 405,
//         'status' =>'error',
//         'message' => 'Method not allowed.'
//     ]);
// }
