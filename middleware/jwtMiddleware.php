<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;


$root = str_replace('middleware','',__DIR__);
// require_once $root . '\vendor\autoload.php';
require_once __DIR__.'\..\vendor\autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

function checkAccessToken($bearerToken)
{
    $secret_key = $_ENV['SECRET_KEY'];

    if (preg_match('/Bearer\s(\S+)/', $bearerToken, $matches)) {
        $jwt = $matches[1];

        try {
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            return $decoded->data; // คืนค่าข้อมูลผู้ใช้
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized', 'message' => $e->getMessage()]);
            exit;
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'No token provided']);
        exit;
    }
}
