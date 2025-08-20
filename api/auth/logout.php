<?php
use App\Controllers\Token\TokenController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$root =dirname(__DIR__,2);
require_once $root . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$secret_key = $_ENV['SECRET_KEY'];
$basepath   = $_ENV['BASE_PATH'];
$app_name   = $_ENV['APP_NAME'];

$access_token_name  = $app_name . '_access_token';
$refresh_token_name = $app_name . '_refresh_token';

$refresh_token = trim($_COOKIE[$refresh_token_name] ?? '');

$TokenController = new TokenController();

if ($refresh_token) {

    $token_decode = JWT::decode($refresh_token, new Key($secret_key, 'HS256'));
    $usercode     = $token_decode->data->user_code;
    $tokenid      = $token_decode->data->token_id;
    $remark       = 'Logout';

    $stmt         = $TokenController->getRefreshTokenByID($usercode, $tokenid);
    $token_result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($refresh_token, $token_result['token'])) {

        try {

            $update = $TokenController->updateRevokeToken($usercode, $tokenid, $remark);

            if ($update) {
                setcookie($access_token_name, '', time() - 3600, '/', '', true, true);
                setcookie($refresh_token_name, '', time() - 3600, '/', '', true, true);
                // session_start();
                $_SESSION = [];
                session_destroy();
                echo "<script>window.location.href='login'</script>";
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                'code'   => 400,
                'status' => 'error',
                'error'  => $e->getMessage(),
            ]);
        }
    } else {
        http_response_code(401);
        echo json_encode([
            'code'   => 401,
            'status' => 'error',
            'error'  => 'Invalid or expired token',
        ]);
    }
} else {
    http_response_code(401);
    echo json_encode([
        'code'   => 401,
        'status' => 'error',
        'error'  => 'No refresh token',
    ]);
}
