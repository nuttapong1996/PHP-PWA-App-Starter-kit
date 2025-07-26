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
        // ✅ ดึง token จาก cookie
        $access_token = $_COOKIE['access_token'] ?? '';

        if (!$access_token) {
            echo '<script>window.location.href = "./";</script>';
            // http_response_code(401);
            // echo json_encode(['error' => 'No token provided']);
            // exit;
        }
        try {
            $decoded = JWT::decode($access_token, new Key($this->secret_key, 'HS256'));

            // ✅ แนบข้อมูล user ไปที่ global เพื่อใช้ใน controller ได้
            $GLOBALS['auth_user'] = $decoded->data;

            return $callback(); // ✅ token ผ่าน เรียก callback
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            exit;
        }
    }
}
