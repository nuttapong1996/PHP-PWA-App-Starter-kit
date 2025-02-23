<?php
// เริ่มใช้งาน session
session_start();
// กำหนด header ให้เป็น json (แสดงข้อมูลในรูปแบบ json)
header('Content-Type: application/json');

// ตรวจเช็ค session ของผู้ใช้
if($_SESSION['username']) {
    // เรียกใช้ไฟล์ connect_db.php เชื่อมต่อฐานข้อมูล
    require '../includes/connect_db.php';
    
    // รับค่ามาจาก frontend ในรูปแบบ json
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // ประกาศตัวแปร username เพื่อเก็บชื่อผู้ใช้จาก session
    $username = $_SESSION['username'];
    // ประกาศตัวแปร endpoint เพื่อเก็บ endpoint ของผู้ใช้ จาก frontend
    $endpoint = $data['endpoint'];

    // ตรวจสอบว่ามี session หรือไม่
    if($username == null) {
        echo json_encode(['status' => 'error' , 'message' => 'session not found']);
        exit;
    }

    // ตรวจสอบว่ามี endpoint หรือไม่
    if($endpoint == null) {
        echo json_encode(['status' => 'error' , 'message' => 'endpoint not found']);
        exit;
    }

    // Query ดึงข้อมูล endpoint , p256dh , authKey จาก push_subscribers
    $sql = "SELECT endpoint , p256dh , authKey FROM push_subscribers WHERE username = :username AND endpoint = :endpoint";
    // เตรียมคําสั่ง
    $stmt = $conn->prepare($sql);
    // เชื่อมต่อตัวแปร username , endpoint
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':endpoint', $endpoint, PDO::PARAM_STR);
    // ทําการดึงข้อมูล
    $stmt->execute();

    // ตรวจสอบข้อมูลที่ถูกดึงมาว่ามีหรือไม่
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // หากมีส่งข้อมูลกลับไปยัง frontend ในรูปแบบ json
        // status จะเป็น success
        // 
        echo json_encode([
                'status' => 'success',
                'result' => $result
            ]);
    }else{
        // หากไม่มีส่งข้อมูลกลับไปยัง frontend ในรูปแบบ json
        // status จะเป็น not found
        echo json_encode(['status' => 'not found']);
    }
}else{
    echo json_encode(['status' => 'session not found']);
    exit;
}