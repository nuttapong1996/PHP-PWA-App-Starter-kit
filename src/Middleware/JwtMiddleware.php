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

    public function __construct($access_token_name, $base_path, $secret_key)
    {
        $this->secret_key        = $secret_key;
        $this->base_path         = $base_path;
        $this->access_token_name = $access_token_name;
    }

    public function handle($callback)
    {
        //ดึง token จาก cookie
        $access_token = $_COOKIE[$this->access_token_name] ?? '';

        try {
            $decoded = JWT::decode($access_token, new Key($this->secret_key, 'HS256'));
            // แนบข้อมูล user ไปที่ global เพื่อใช้ใน controller ได้
            $_SERVER['jwt_payload'] = (array) $decoded->data;
            return $callback();

        } catch (\Firebase\JWT\ExpiredException $e) {
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'message' => 'Invalid or expired token',
                'error'   => $e->getMessage(),
            ]);
            exit;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'message' => 'Invalid Signature',
                'error'   => $e->getMessage(),
            ]);
            exit;
        } catch (\Exception $e) {
            // เก็บ Session intend_url จาก
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            header('Location: ./');
        }
    }
}
