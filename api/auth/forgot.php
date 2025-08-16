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
    if (! empty($input['userCode'] && ! empty($input['userIdenCode']))) {

        $stmt = $AuthController->forgot($input['userCode'], $input['userIdenCode']);


        if ($stmt->rowCount() > 0) {
            http_response_code(200);

            $resetToken       = bin2hex(random_bytes(16)); // resetToken
            $resetTokenExpire = time() + (60 * 5);         // expires in 5 minutes;

            $AuthController->insertResetToken($input['userCode'], $resetToken, date('Y-m-d H:i:s', $resetTokenExpire));

            echo json_encode([
                'code'       => 200,
                'status'     => 'success',
                'title'      => 'Valid',
                'message'    => 'You can reset your password.',
                'resetToken' => $resetToken,
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                'code'    => 200,
                'status'  => 'error',
                'title'   => 'Invalid',
                'message' => 'Invalid user code or ID card number.',
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'code'    => 400,
            'status'  => 'error',
            'title'   => 'Invalid request',
            'message' => 'Invalid input data',
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'code'    => 405,
        'status'  => 'error',
        'title'   => 'Method not allowed',
        'message' => 'This method is not allowed',
    ]);
}
