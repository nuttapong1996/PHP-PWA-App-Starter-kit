<?php
session_start();
if($_SESSION['username']) {
    require '../includes/connect_db.php';
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);
    $username = $_SESSION['username'];
    $endpoint = $data['endpoint'];

    $sql = "SELECT endpoint , p256dh , authKey FROM push_subscribers WHERE username = :username AND endpoint = :endpoint";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':endpoint', $endpoint, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
                'status' => 'success',
                'result' => $result
            ]);
    }else{
        echo json_encode(['status' => 'not found']);
    }
}else{
    echo json_encode(['status' => 'session not found']);
}