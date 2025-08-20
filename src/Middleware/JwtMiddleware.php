<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$root = dirname(__DIR__, 2);
require_once $root . '/vendor/autoload.php';

class JwtMiddleware
{
    private $secret_key;
    private $base_path;
    private $access_token_name;
    private $refresh_token_name;

    public function __construct($access_token_name, $refresh_token_name, $base_path, $secret_key)
    {
        $this->secret_key         = $secret_key;
        $this->base_path          = $base_path;
        $this->access_token_name  = $access_token_name;
        $this->refresh_token_name = $refresh_token_name;
    }

    public function handle($callback)
    {
        //ดึง token จาก cookie
        $access_token  = $_COOKIE[$this->access_token_name] ?? '';
        $refresh_token = $_COOKIE[$this->refresh_token_name] ?? '';

        if (! $access_token || ! $refresh_token) {
            // header('Content-Type: application/json; charset=utf-8');
            // http_response_code(401);
            // echo json_encode([
            //     'code'    => 401,
            //     'status'  => 'error',
            //     'title'   => 'Access denied',
            //     'message' => 'Invalid or expired token.',
            // ]);
            header('Location:' . $this->base_path);
        }

        try {
            $decoded = JWT::decode($access_token, new Key($this->secret_key, 'HS256'));

            // แนบข้อมูล user ไปที่ global เพื่อใช้ใน controller ได้
            $_SERVER['jwt_payload'] = (array) $decoded->data;

            return $callback();

        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'message' => 'Invalid or expired token',
                'error'   => $e->getMessage()]);
            header('Location:' . $this->base_path);
        }
    }
}
