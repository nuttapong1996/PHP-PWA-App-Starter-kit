<?php
// เริ่มใช้งาน session 
session_start();
// กําหนด header ให้เป็น json (แสดงข้อมูลในรูปแบบ json)
header('Content-Type: application/json');

// ตรวจเช็ค session ของผู้ใช้
if(isset($_SESSION['username'])){
    // ถ้ามี session ของผู้ใช้ให้ส่งค่า username ไปยัง frontend
    echo json_encode([
        'status' => 'success',
        'username' => $_SESSION['username']
        ]);
}else{
    // ถ้าไม่มี session ของผู้ใช้ให้ส่งค่า username = null
    echo json_encode([
        'status' => 'session not set',
        'username' => null
    ]);
}
?>