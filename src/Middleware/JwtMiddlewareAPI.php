<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$root = dirname(__DIR__, 2);
require_once $root . '/vendor/autoload.php';

class JwtMiddlewareAPI
{
    private $secret_key;
    private $access_token_name;

    public function __construct($access_token_name, $secret_key)
    {
        $this->secret_key        = $secret_key;
        $this->access_token_name = $access_token_name;
    }

    public function handle($callback)
    {
        //ดึง token จาก cookie
        $access_token = $_COOKIE[$this->access_token_name] ?? '';

        if (! $access_token) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'code'   => 401,
                'status' => 'error',
                'error'  => 'No access token',
            ]);
            exit;
        }
        try {
            $decoded = JWT::decode($access_token, new Key($this->secret_key, 'HS256'));
            // แนบข้อมูล user ไปที่ global เพื่อใช้ใน controller ได้
            $_SERVER['jwt_payload'] = (array) $decoded->data;
            return $callback();

        } catch (\Firebase\JWT\ExpiredException $e) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'message' => 'Invalid or expired token',
                'error'   => $e->getMessage(),
            ]);
            exit;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'message' => 'Invalid Signature',
                'error'   => $e->getMessage(),
            ]);
            exit;
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'message' => 'Invalid or expired token',
                'error'   => $e->getMessage(),
            ]);
            exit;
        }
    }
}
