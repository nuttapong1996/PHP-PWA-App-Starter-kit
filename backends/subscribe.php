<?php 
session_start();
if(isset($_SESSION['username'])){
    require '../includes/connect_db.php';
    header('Content-Type: application/json; charset=utf-8');

    $data = json_decode(file_get_contents('php://input'), true);
    $username = $_SESSION['username'];
    $enpoint = $data['endpoint'];
    $public_key = $data['keys']['p256dh'];
    $auth_key = $data['keys']['auth'];

    $sub_sql = "INSERT INTO push_subscribers(username,endpoint,p256dh,authKey) VALUES (:username,:endpoint,:pub_key,:auth_key)";
    $stmt_sub = $conn->prepare($sub_sql);
    $stmt_sub->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_sub->bindParam(':endpoint', $enpoint, PDO::PARAM_STR);
    $stmt_sub->bindParam(':pub_key', $public_key, PDO::PARAM_STR);
    $stmt_sub->bindParam(':auth_key', $auth_key, PDO::PARAM_STR);
    $stmt_sub->execute();

    if ($stmt_sub->rowCount() > 0) {
        echo json_encode(['status' => 'success']);
    }else{
        echo json_encode(['status' => 'error']);
    }
}else{
    echo json_encode(['status' => 'error']);
}
?>