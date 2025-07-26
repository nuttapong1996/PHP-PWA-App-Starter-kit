<?php

header('Content-Type: application/json');

$root = str_replace('api\user', '', __DIR__);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once $root . '\configs\connect_db.php';

// GET user by ID
    if (isset($_GET['usercode'])) {
        $usercode = $_GET['usercode'];
        $id_stmt  = $conn->prepare("SELECT * FROM tbl_login WHERE user_code = :usercode");
        $id_stmt->bindParam(':usercode', $usercode, PDO::PARAM_STR);
        $id_stmt->execute();
        $id_user = $id_stmt->fetch(PDO::FETCH_ASSOC);

        if ($id_user) {
            http_response_code(200);
            echo json_encode([
                'code'    => 200,
                'status'  => 'success',
                'title'   => 'Success',
                'message' => 'Token valid',
                'data'    => $id_user,
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'Unauthorized',
                'title'   => 'Unauthorized Access',
                'message' => 'Please login',
            ]);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
