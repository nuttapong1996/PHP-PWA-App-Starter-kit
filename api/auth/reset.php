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
    if (!empty($input['userCode']) && !empty($input['resetToken'])){
        // Check ResetToken 
        $CheckToken = $AuthController->getResetToken($input['userCode']);
        $resultCheckToken = $CheckToken->fetch(PDO::FETCH_ASSOC);

        // Validate ResetToken from GET request with DB
        if($resultCheckToken['reset_token'] == $input['resetToken']){
            http_response_code(200);
            echo json_encode([  // Valid return status valid
                'code' => 200,
                'status' => 'valid',
                'title' => 'Valid token',
                'message' => 'Reset token is valid.',
            ]);    
        }else{
            echo 'notfound';
        }


    }
} else {
    http_response_code(405);
    echo json_encode([
        'code' =>405,
        'status' => 'error',
        'title' => 'Method not allowed',
        'message' => 'Method not allowed',
    ]);
}
