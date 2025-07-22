<?php 
    // session_start();
    require '../includes/connect_db.php';
    require '../vendor/autoload.php'; 
    require_once '../includes/loadEnv.php';

    header('Content-Type: application/json');

    loadEnv(__DIR__ . '/.env');

    $secret_key = $_ENV['SECRET_KEY'];
    $issued_at = time();
    $expire = $issued_at + (60 * 60); // 1 ชั่วโมง

    // รับค่าจาก POST
    if(isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
    $sql ="SELECT username FROM tbl_login WHERE username = :username AND password = :password";
    $stmt =$conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // $_SESSION['username'] = $username;
        $payload =[
            "iss" => "localhost",
            "iat" => $issued_at,
            "exp" => $expire,
            "data" =>[
                "username" => $username
            ]
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');
        echo json_encode([
            'status' => 'success' , 
            'message' => 'login success',
            'token' => $jwt
        ]);

    }else{
        http_response_code(401);
        echo json_encode([
            'status' => 'fail' , 
            'message' => 'login fail'
        ]);
    }
}else{
    http_response_code(400);
    echo json_encode([
        'status' => 'fail' , 
        'message' => 'please provide username and password'
    ]);
}



?>