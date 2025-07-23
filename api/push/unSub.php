<?php
// เริ่มใช้งาน session
session_start();

// ตรวจเช็ค session ของผู้ใช้
if(isset($_SESSION['username'])) {
    // เรียกใช้ไฟล์ connect_db.php เชื่อมต่อฐานข้อมูล
    require '../includes/connect_db.php';

    // ประกาศตัวแปร username เพื่อเก็บชื่อผู้ใช้จาก session
    $username = $_SESSION['username'];
    
    // ตรวจสอบว่า session ของผู้ใช้มีข้อมูลหรือไม่
    if($username == null) {
        echo json_encode(['status' => 'error' , 'message' => 'no data found']);
        exit;
    }

    // คําสั่ง SQL เพื่อลบข้อมูลจาก push_subscribers ของผู้ใช้โดยอิงจาก username
    $sql = "DELETE FROM push_subscribers WHERE username =:username";
    // เตรียมคําสั่ง SQL
    $stmt = $conn->prepare($sql);
    // ทำการผูกต่อพารามิเตอร์ในคําสั่ง SQL กับตัวแปร username
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    // ส่งคําสั่ง SQL ไปยังฐานข้อมูล
    $stmt->execute();

    // ตรวจสอบว่ามีการลบข้อมูลหรือไม่
    if($stmt->rowCount() > 0) {
        echo "<script>
                alert('ยกเลิกการแจ้งตือนแล้ว');
                setTimeout(()=>{
                    window.location.href = '../main.php';
                },0);
            </script>";
    }else{
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'something went wrong'
        ]);
        exit;
    }
}else{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error']);
    exit;
}