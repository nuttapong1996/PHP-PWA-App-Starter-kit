<?php

header('Content-Type: application/json');

$root = str_replace('api\user', '', __DIR__);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    require_once $root . '\configs\connect_db.php';

// GET Current user
    $auth_user    = $_SERVER['jwt_payload'] ?? null;
    $cur_usercode = $auth_user['user_code'];

    $cur_stmt = $conn->prepare("SELECT * FROM tbl_login WHERE user_code = :usercode");
    $cur_stmt->bindParam(':usercode', $cur_usercode, PDO::PARAM_STR);
    $cur_stmt->execute();
    $cur_user = $cur_stmt->fetch(PDO::FETCH_ASSOC);

    if ($cur_user) {
        http_response_code(200);
        echo json_encode([
            'code'    => 200,
            'status'  => 'success',
            'title'   => 'Success',
            'message' => 'Token valid',
            'data'    => $cur_user,
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
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
