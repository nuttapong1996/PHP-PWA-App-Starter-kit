<?php

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$root = str_replace('\api\user', '', __DIR__);

require_once $root . '\vendor\autoload.php';
require_once $root . '\configs\connect_db.php';

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

        $sql  = 'SELECT user_code,username FROM tbl_login WHERE username  = :username AND password = :password';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            // Generate access token
            $access_token_payload = [
                'iss'  => 'yourdomain.com',
                'aud'  => 'yourdomain.com',
                'iat'  => $issued_at,
                'exp'  => $access_token_expire,
                'data' => [
                    'user_code' => $user['user_code'],
                    'username'  => $user['username'],
                ],
            ];

            $access_token = JWT::encode($access_token_payload, $secret_key, 'HS256');

            // Generate refresh token
            $refesh_token_payload = [
                'iat'  => $issued_at,
                'exp'  => $refresh_token_expire,
                'data' => [
                    'user_code' => $user['user_code'],
                ],
            ];

            $refresh_token = JWT::encode($refesh_token_payload, $secret_key, 'HS256');

            // เก็บ refresh token ในฐานข้อมูล

            // $check_refresh_token = $conn->prepare("SELECT * FROM refresh_tokens WHERE user_code = :usercode");
            // $check_refresh_token->BindParam(':usercode', $user['user_code'], PDO::PARAM_STR);
            // $check_refresh_token->execute();
            // $refresh_token_row = $check_refresh_token->fetch(PDO::FETCH_ASSOC);

            // if ($refresh_token_row['token'] !== $refresh_token || $refresh_token_row['expires_at'] < date('Y-m-d H:i:s')) {
            //     $delete_token = $conn->prepare("DELETE FROM refresh_tokens WHERE user_code = :usercode AND token = :token LIMIT 1");
            //     $delete_token->execute([
            //         ':usercode' => $user['user_code'],
            //         ':token'    => $refresh_token_row['token'],
            //     ]);
            // }

            // เช็คว่า token นี้มีอยู่แล้วไหม
            $check_token = $conn->prepare("SELECT * FROM refresh_tokens WHERE user_code = :usercode AND token = :token");
            $check_token->execute([
                ':usercode' => $user['user_code'],
                ':token'    => $refresh_token,
            ]);

            if ($check_token->rowCount() == 0) {
                $insert = $conn->prepare("INSERT INTO refresh_tokens (user_code, token, expires_at) VALUES (:usercode, :token, :expires)");
                $insert->execute([
                    ':usercode' => $user['user_code'],
                    ':token'    => $refresh_token,
                    ':expires'  => date('Y-m-d H:i:s', $refresh_token_expire),
                ]);
            }

            // ส่ง access token กลับ และตั้ง cookie httpOnly สำหรับ refresh token
            setcookie('refresh_token', $refresh_token, [
                'expires'  => $refresh_token_expire,
                'path'     => '/',
                'httponly' => true,
                'secure'   => false, // ใช้ https เท่านั้น ถ้าไม่มีให้ false ชั่วคราว
                'samesite' => 'Strict',
            ]);

            http_response_code(200);
            echo json_encode([
                'code'          => '200',
                'status'        => 'success',
                'title'         => 'Success',
                'message'       => 'Login success',
                'access_token'  => $access_token,
                'refresh_token' => $refresh_token,
                'expires_in'    => $access_token_expire,
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Invalid credentials',
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
