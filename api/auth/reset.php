<?php
use App\Controllers\Auth\AuthController;
date_default_timezone_set("Asia/Bangkok");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

$root = dirname(__DIR__, 2);
require_once $root . '/vendor/autoload.php';

$input = json_decode(file_get_contents('php://input'), true);

$AuthController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // เช็คว่ามี UserPass ไหม
    if (isset($input['UserPass'])) {
        // กรณี reset password
        $hashpass = password_hash($input['UserPass'], PASSWORD_BCRYPT);
        $reset    = $AuthController->reset($input['userCode'], $hashpass);
        if ($reset) {
            http_response_code(200);
            echo json_encode([
                'code'    => 200,
                'status'  => 'success',
                'title'   => 'success',
                'message' => 'Reset password successfully',
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'code'    => 500,
                'status'  => 'error',
                'title'   => 'error',
                'message' => 'Reset password failed',
            ]);
        }
        exit; // หยุดตรงนี้ ไม่ให้ไป echo JSON อื่นเพิ่ม
    }

    // ถ้าไม่มี UserPass แสดงว่ามา validate token
    if (! empty($input['userCode']) && ! empty($input['resetToken'])) {
        $CheckToken       = $AuthController->getResetToken($input['userCode'], $input['resetToken']);
        $resultCheckToken = $CheckToken->fetch(PDO::FETCH_ASSOC);

        if ($CheckToken->rowCount() > 0) {
            // if ($resultCheckToken['reset_token'] == $input['resetToken']) {
            http_response_code(200);
            echo json_encode([
                'code'    => 200,
                'status'  => 'valid',
                'title'   => 'Valid token',
                'message' => 'Reset token is valid.',
            ]);
            // }
        } else {
            $AuthController->insertResetToken($input['userCode'], null, null);
            http_response_code(200);
            echo json_encode([
                'code'    => 200,
                'status'  => 'invalid',
                'title'   => 'Invalid token',
                'message' => 'Token is invalid or expired.',
            ]);
        }
        exit;
    }
}
