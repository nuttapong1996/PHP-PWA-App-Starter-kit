<?php

// กำหนด header ให้เป็น json (แสดงข้อมูลในรูปแบบ json)
header('Content-Type: application/json');

$root = str_replace("\api\push", "", __DIR__);

// เรียกใช้ไฟล์ connect_db.php เชื่อมต่อฐานข้อมูล
require $root . "\configs\connect_db.php";

// รับค่ามาจาก frontend ในรูปแบบ json
$input = json_decode(file_get_contents("php://input"),true);

// ตรวจเช็ค session ของผู้ใช้

if ($_SERVER['REQUEST_METHOD'] == "POST") {


    if (isset($input['subName']) && isset($input['endpoint'])) {

        // ประกาศตัวแปร username
        $username = $input['subName'];

        // ประกาศตัวแปร endpoint เพื่อเก็บ endpoint ของผู้ใช้ จาก frontend
        $endpoint = $input['endpoint'];


        // ตรวจสอบว่ามี subName หรือ endpoint หรือไม่
        // if (empty($input['subName']) || empty($input['endpoint'])) {
        //    echo "<script>console.log('not found');</script>";
        //     exit;
        // }

        $sql = "SELECT endpoint , p256dh , authKey FROM push_subscribers WHERE username = :username AND endpoint = :endpoint";
        $stmt = $conn->prepare($sql);
        // เชื่อมต่อตัวแปร username , endpoint
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':endpoint', $endpoint, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ตรวจสอบข้อมูลที่ถูกดึงมาว่ามีหรือไม่
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode([
                "code" => 200,
                "status" => "sub",
                "title" => "Subscribed",
                "message" => "You are already subscribed",
            ]);
        } else {
           http_response_code(200);
           echo json_encode([
                "code" => 200,
                "status" => "not sub",
                "title" => "Not subscribe yet",
                "message" => "You are not subscribe yet",
           ]);
        }
    } else {
        http_response_code(401);
        echo json_encode([
            "code"    => 401,
            "status"  => "Unauthorized",
            "title"   => "Unauthorized Access",
            "message" => "Please login",
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "code"    => "400",
        "status"  => "Bad request",
        "title"   => "Bad request",
        "message" => "Invalid request",
    ]);
}
