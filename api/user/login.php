<?php
use App\Controllers\Auth\AuthController;
use App\Controllers\Token\TokenController;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;

header('Content-Type: application/json');

$root = str_replace('api\user', '', __DIR__);

require_once $root . '\vendor\autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

// JWT attibute
$secret_key           = $_ENV['SECRET_KEY'];
$issued_at            = time();
$access_token_expire  = $issued_at + (60 * 15);          // 15 นาที
$refresh_token_expire = $issued_at + (60 * 60 * 24 * 7); // 7 วัน

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($input['username']) && isset($input['password'])) {
        $username = $input['username'];
        $password = $input['password'];

        $AuthController = new AuthController();
        $stmt           = $AuthController->login($username, $password);

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set Access token payload
            $access_token_payload = [
                'iss'  => 'yourdomain.com',
                'aud'  => 'yourdomain.com',
                'iat'  => $issued_at,
                'exp'  => $access_token_expire,
                'data' => [
                    'user_code' => $result['user_code'],
                ],
            ];

            // Set Refresh token payload
            $refesh_token_payload = [
                'iat'  => $issued_at,
                'exp'  => $refresh_token_expire,
                'data' => [
                    'user_code' => $result['user_code'],
                ],
            ];

            // Encoded or create  Access Token and Refresh Token
            $access_token       = JWT::encode($access_token_payload, $secret_key, 'HS256');
            $refresh_token      = JWT::encode($refesh_token_payload, $secret_key, 'HS256');
            $refresh_token_hash = password_hash($refresh_token, PASSWORD_ARGON2I);

            // Store Access token in cookie HttpOnly with secure
            setcookie('myapp_access_token', $access_token, [
                'expires'  => $access_token_expire,
                'path'     => '/',
                'httponly' => true,
                'secure'   => true,
                'samesite' => 'Strict',
            ]);
            // Store Refresh token in cookie HttpOnly with secure
            setcookie('myapp_refresh_token', $refresh_token, [
                'expires'  => $refresh_token_expire,
                'path'     => '/',
                'httponly' => true,
                'secure'   => true,
                'samesite' => 'Strict',
            ]);

            // Check Refresh token in DB
            $TokenController = new TokenController();
            $stmt_token      = $TokenController->getRefreshToken($result['user_code'], $refresh_token);

            // Store Refresh token in DB
            if ($stmt_token->rowCount() == 0) {
                $TokenController->insertRefreshToken($result['user_code'], $refresh_token_hash, date('Y-m-d H:i:s', $refresh_token_expire));
            }

        } else {
            http_response_code(401);
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Invalid credentials',
            ]);
        }
        http_response_code(200);
        echo json_encode([
            'code'    => '200',
            'status'  => 'success',
            'title'   => 'Success',
            'message' => 'Login success',
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'code'    => 401,
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
