<?php
use App\Controllers\Auth\AuthController;
use App\Controllers\User\UserController;

$root = dirname(__DIR__ ,2);
require_once $root . '/vendor/autoload.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($input['fName']) && ! empty($input['userName']) && ! empty($input['userPass']) && ! empty($input['userEmail']) && ! empty($input['userIdenCode'])) {

        $AuthController = new AuthController();
        $UserController = new UserController();

        $usercode = $UserController->createUserId();
        $name     = $input['fName'];
        $username = $input['userName'];
        $password = $input['userPass'];
        $email    = $input['userEmail'];
        $idenCode = $input['userIdenCode'];


        $password = password_hash($password, PASSWORD_BCRYPT);

        $existingUserCode = $UserController->getUserProfileByCode($usercode);
        $existingUserName = $UserController->getUserByUsername($username);
        $existingEmail    = $UserController->getEmailByEmail($email);
        $existingIDcard   = $UserController->getIdCardByIdCard($idenCode);


        // Check if usercode already exists
        if ($existingUserCode->rowCount() > 0) {
            echo json_encode([
                'code'    => 200,
                'status'  => 'error',
                'title'   => 'usercode exists',
                'message' => 'User code already exists',
            ]);
            exit;
        }

        // Check if username already exists
        if ($existingUserName->rowCount() > 0) {
            echo json_encode([
                'code'    => 200,
                'status'  => 'error',
                'title'   => 'username exists',
                'message' => 'Username already exists',
            ]);
            exit;
        }

        // Check if email already exists
        if ($existingEmail->rowCount() > 0) {
            echo json_encode([
                'code'    => 200,
                'status'  => 'error',
                'title'   => 'email exists',
                'message' => 'Email already exists',
            ]);
            exit;
        }

        // Check if ID card already exists
        if ($existingIDcard->rowCount() > 0) {
            echo json_encode([
                'code'    => 200,
                'status'  => 'error',
                'title'   => 'ID card exists',
                'message' => 'ID card already exists',
            ]);
            exit;
        }

        // If usercode and email are unique, proceed with registration
        $result = $AuthController->register($usercode, $name, $username, $password, $email ,$idenCode);
        if ($result) {
            echo json_encode([
                'code'    => 201,
                'status'  => 'success',
                'title'   => 'success',
                'message' => 'Registration successful',
            ]);
        } else {
            echo json_encode([
                'code'    => 500,
                'status'  => 'error',
                'title'   => 'error',
                'message' => 'Registration failed',
            ]);
        }

    } else {
        http_response_code(400);
        echo json_encode([
            'code'    => 400,
            'status'  => 'error',
            'message' => 'Invalid input data',
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'code'    => 405,
        'status'  => 'error',
        'message' => 'Method not allowed',
    ]);
}
