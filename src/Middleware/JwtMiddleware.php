<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'\..\..\vendor\autoload.php';

class JwtMiddleware
{
    private $secret_key;

    public function __construct($secret_key)
    {
        $this->secret_key = $secret_key;
    }

    public function handle($callback)
    {
        // อ่าน Authorization Header
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['error' => 'No token provided']);
            exit;
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));

            // แนบข้อมูล user ไปที่ global เพื่อใช้ใน controller ได้
            $GLOBALS['auth_user'] = $decoded->data;

            // เรียก callback ถ้า token ผ่าน
            return $callback();
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            exit;
        }
    }
}
