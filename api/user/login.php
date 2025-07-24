<?php

use Dotenv\Dotenv;

session_start();
header('Content-Type: application/json');

$root = str_replace('\api\user', '', __DIR__);

require_once $root . '\vendor\autoload.php';
require_once $root . '\configs\connect_db.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();


// JWT attibute
// $secret_key = $_ENV['SECRET_KEY'];
// $issued_at = time();
// $expire = $issued_at + (60 * 60); // 1 ชั่วโมง

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($input['username']) && isset($input['password'])) {
        $username = $input['username'];
        $password = $input['password'];

        $sql = 'SELECT
                    username
                FROM
                    tbl_login 
                WHERE
                    username  = :username
                AND
                    password = :password';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! empty($result)) {
            $_SESSION['username'] = $username;
            http_response_code(200);
            echo json_encode([
                'code'    => '200',
                'status'  => 'success',
                'title'   => 'Success',
                'message' => 'Login success',
            ]);
        } else {
            http_response_code(203);
            echo json_encode([
                'code'    => '203',
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Invalid username or password',
            ]);
        }
    } else {
        http_response_code(401);
        echo json_encode([
            'code'    => '401',
            'status'  => 'Unauthorized',
            'title'   => 'Unauthorized Access',
            'message' => 'Please provide username and password',
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'code'    => '400',
        'status'  => 'Bad request',
        'title'   => 'Bad request',
        'message' => 'Invalid request',
    ]);
}
