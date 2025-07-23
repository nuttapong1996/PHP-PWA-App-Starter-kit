<?php
// เริ่มใช้งาน session
session_start();
header('Content-Type: application/json; charset=utf-8');
// ตรวจเช็ค session
if (isset($_SESSION['username'])) {

    $root = str_replace("\api\push", "", __DIR__);

    // เรียกใช้ไฟล์ connect_db.php เชื่อมต่อฐานข้อมูล
    require $root . "\configs\connect_db.php";

    // รับค่ามาจาก frontend ในรูปแบบ json
    $input = json_decode(file_get_contents("php://input"),true);

    // ประกาศตัวแปร username เพื่อเก็บชื่อผู้ใช้จาก session
    $username = $_SESSION['username'];

    // ประกาศตัวแปร endpoint , p256dh , auth เพื่อเก็บ endpoint , p256dh , auth  ของผู้ใช้ จาก frontend
    $enpoint    = $input['endpoint'];
    $public_key = $input['keys']['p256dh'];
    $auth_key   = $input['keys']['auth'];

    // คำสั่ง SQL เพื่อเพิ่มข้อมูลในตาราง push_subscribers
    $sub_sql = "INSERT INTO push_subscribers(username,endpoint,p256dh,authKey) VALUES (:username,:endpoint,:pub_key,:auth_key)";
    // เตรียมคําสั่ง
    $stmt_sub = $conn->prepare($sub_sql);

    // ทำการผูกตัวแปร username กับตัวแปรในคําสั่ง SQL
    $stmt_sub->bindParam(':username', $username, PDO::PARAM_STR);

    // ทำการผูก ตัวแปร username , endpoint , p256dh , auth กับตัวแปรในคําสั่ง SQL
    $stmt_sub->bindParam(':endpoint', $enpoint, PDO::PARAM_STR);
    $stmt_sub->bindParam(':pub_key', $public_key, PDO::PARAM_STR);
    $stmt_sub->bindParam(':auth_key', $auth_key, PDO::PARAM_STR);

    // ทำการส่งคําสั่งไปที่ฐานข้อมูล
    $stmt_sub->execute();

    // ตรวจสอบข้อมูลที่ถูกส่งไปฐานข้อมูล
    if ($stmt_sub->rowCount() > 0) {
        // หากมีส่งข้อมูลกลับไปยัง frontend ในรูปแบบ json
        // status จะเป็น success
        echo json_encode(['status' => 'success']);
    } else {
        // หากไม่มีส่งข้อมูลกลับไปยัง frontend ในรูปแบบ json
        // status จะเป็น error
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error']);
}
