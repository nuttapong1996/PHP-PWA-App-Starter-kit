<?php
use App\Controllers\Auth\AuthController;
use App\Controllers\Token\TokenController;
use App\Controllers\User\UserController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;

header('Content-Type: application/json');

$root = dirname(__DIR__,2);

require_once $root . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

// JWT attibute
$secret_key           = $_ENV['SECRET_KEY'];
$domain               = $_ENV['APP_DOMAIN'];
$app_name             = $_ENV['APP_NAME'];
$refresh_token_name   = $app_name . '_refresh_token';
$access_token_name    = $app_name . '_access_token';
$issued_at            = time();
$refresh_token_id     = uniqid('TK', true);
$access_token_expire  = $issued_at + (60 * 15);          // 15 นาที
$refresh_token_expire = $issued_at + (60 * 60 * 24 * 7); // 7 วัน

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($input['username']) && ! empty($input['password'])) {
        $username = $input['username'];
        $password = $input['password'];

        $AuthController = new AuthController();
        $stmt           = $AuthController->login($username);

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $result['password'])) {

                // Set Access token payload
                $access_token_payload = [
                    'iss'  => $domain,
                    'aud'  => $domain,
                    'iat'  => $issued_at,
                    'exp'  => $access_token_expire,
                    'data' => [
                        'user_code' => $result['user_code'],
                    ],
                ];

                // Set Refresh token payload
                $refesh_token_payload = [
                    'iss'  => $domain,
                    'aud'  => $domain,
                    'iat'  => $issued_at,
                    'exp'  => $refresh_token_expire,
                    'data' => [
                        'user_code' => $result['user_code'],
                        'token_id'  => $refresh_token_id,
                    ],
                ];

                // Encoded or create  Access Token and Refresh Token
                $access_token       = JWT::encode($access_token_payload, $secret_key, 'HS256');
                $refresh_token      = JWT::encode($refesh_token_payload, $secret_key, 'HS256');
                $refresh_token_hash = password_hash($refresh_token, PASSWORD_ARGON2I);

                // Store Access token in cookie HttpOnly with secure
                setcookie($access_token_name, $access_token, [
                    'expires'  => $access_token_expire,
                    'path'     => '/',
                    'httponly' => true,
                    'secure'   => true,
                    'samesite' => 'Strict',
                ]);
                // Store Refresh token in cookie HttpOnly with secure
                setcookie($refresh_token_name, $refresh_token, [
                    'expires'  => $refresh_token_expire,
                    'path'     => '/',
                    'httponly' => true,
                    'secure'   => true,
                    'samesite' => 'Strict',
                ]);

                // Check Refresh token in DB
                $TokenController = new TokenController();

                $UserController = new UserController();
                $user_ip        = $UserController->getUserIP();
                $user_device    = $UserController->getUserDeviceType();

                // Store Refresh token in DB
                $TokenController->insertRefreshToken($result['user_code'], $refresh_token_id, $refresh_token_hash, $user_device, $user_ip, date('Y-m-d H:i:s', $refresh_token_expire));

                // Check and remark expired token
                $stmt_expired = $TokenController->getExpiresToken($result['user_code']);
                if ($stmt_expired->rowCount() > 0) {
                    $TokenController->updateExpiredToken($result['user_code']);
                }

                // Check and remove Revoke token or expired token that more than 7 days
                $stmt_revoke = $TokenController->getRevokeToken($result['user_code']);
                if ($stmt_revoke->rowCount() > 0) {
                    $TokenController->deleteToken($result['user_code']);
                }
                http_response_code(200);
                echo json_encode([
                    'code'    => 200,
                    'status'  => 'success',
                    'title'   => 'Success',
                    'message' => 'Login success',
                ]);

            } else {
                http_response_code(401);
                echo json_encode([
                    'code'    => 401,
                    'status'  => 'error',
                    'title'   => 'Wrong Credentials',
                    'message' => 'Invalid username or password',
                ]);
            }
        } else {
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'title'   => 'Wrong Credentials',
                'message' => 'Invalid username or password',
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'code'    => 400,
            'status'  => 'error',
            'title'   => 'Unauthorized Access',
            'message' => 'Uername and password required',
        ]);
    }

} else {
    http_response_code(400);
    echo json_encode([
        'code'    => 400,
        'status'  => 'error',
        'title'   => 'Bad request',
        'message' => 'Invalid request',
    ]);
}
