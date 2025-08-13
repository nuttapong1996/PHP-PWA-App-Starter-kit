<?php
use App\Controllers\Auth\AuthController;
use App\Controllers\User\UserController;

$root = str_replace('api\auth', '', __DIR__);
require_once $root . 'vendor\autoload.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($input['usercode']) && ! empty($input['name']) && ! empty($input['username']) && ! empty($input['password']) && ! empty($input['email'])) {

        $AuthController = new AuthController();
        $UserController = new UserController();

        $usercode = $UserController->createUserId();
        $name     = $input['name'];
        $username = $input['username'];
        $password = $input['password'];
        $email    = $input['email'];

        $password = password_hash($password, PASSWORD_BCRYPT);

        $existingUser  = $UserController->getUserProfileByCode($usercode);
        $existingEmail = $UserController->getEmailByEmail($email);

        // Check if usercode already exists
        if ($existingUser->rowCount() > 0) {
            echo json_encode([
                'code'    => 200,
                'status'  => 'error',
                'title'   => 'error',
                'message' => 'User code already exists',
            ]);
            exit;
        }
        // Check if email already exists
        if ($existingEmail->rowCount() > 0) {
            echo json_encode([
                'code'    => 200,
                'status'  => 'error',
                'title'   => 'error',
                'message' => 'Email already exists',
            ]);
            exit;
        }

        // If usercode and email are unique, proceed with registration
        $result = $AuthController->register($usercode, $name, $username, $password, $email);
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
