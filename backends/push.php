<?php
// เริ่มใช้งาน session
session_start();

// เรียกใช้ Lib ของ WebPush
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
// เรียกใช้ไฟล์ autoload.php ของ Composer
require ("../vendor/autoload.php");

// ตรวจเช็ค session ของผู้ใช้
if(isset($_SESSION['username'])) { 
    // ตรวจสอบการส่งข้อมูลจาก form
    if(!empty($_POST['title']) && !empty($_POST['body']) && !empty($_POST['url'])) {
        // เรียกใช้ไฟล์ connect_db.php เชื่อมต่อฐานข้อมูล
        require '../includes/connect_db.php';
        // ประกาศตัวแปร username เพื่อเก็บชื่อผู้ใช้จาก session
        $username = $_SESSION['username'];
        // คําสั่ง SQL เพื่อดึงข้อมูล endpoint , p256dh , authKey จาก push_subscribers ของผู้ใช้โดยอิงจาก username ของผู้ใช้ที่ได้สมัครเข้ามา
        $sql = "SELECT username, endpoint , p256dh , authKey FROM push_subscribers WHERE username =:username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $endpoints = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // กำหนดตัวแปร auth สำหรับใช้ในการ ส่งแจ้งเตือน โดยเรียกใช้งานจาก ไฟล์ public_key.text และ private_key.text ที่จัดเก็บไว้ในโฟลเดอร์ includes
            $auth = [
                'VAPID' => [
                    'subject' => 'mailto:me@website.com', // can be a mailto: or your website address
                    'publicKey' => trim(file_get_contents('../includes/public_key.text')),
                    'privateKey' => trim(file_get_contents('../includes/private_key.text'))
                ],
            ];
            // กำหนดตัวแปร message สำหรับใช้ในการ ส่งแจ้งเตือนโดยกําหนดค่า title , body , url จาก form
            $message = [
                'title' => $_POST['title'],
                'body' => $_POST['body'],
                'url' => $_POST['url']
            ];

            // สร้างตัวแปร webPush เพื่อใช้ส่งแจ้งเตือน
            $webPush = new WebPush($auth);

            // วนลูปเพื่อส่งแจ้งเตือนให้แต่ละ endpoint
            foreach($endpoints as $endpoint){
                $subscription = Subscription::create([
                    'endpoint' => $endpoint['endpoint'],
                    'keys' => [
                        'p256dh' => $endpoint['p256dh'],
                        'auth' => $endpoint['authKey']
                    ]
                ]);

                $webPush->queueNotification(
                    $subscription,
                    json_encode($message)
                );
            }
            
            // วนลูปเพื่อส่งแจ้งเตือน
            foreach($webPush->flush() as $report) {
                if($report->isSuccess()){
                    // ทำการแจ้งว่าส่งแจ้งเตือนเรียบร้อย
                    // และหน่วง 1 วินาที จากนั้นกลับไปหน้าหลัก
                    echo "<script>
                            alert('ส่งแจ้งเตือนเรียบร้อย');
                            setTimeout(()=>{
                                window.location.href = '../main.php';
                            },0);
                        </script>";

                    // uncomment บรรทัดด่านล่าง เพื่อทำการส่งค่า status success กลับไปในกรณีที่มีการใช้ fetch api ในการเช็คสถานะการส่งแจ้งเตือน
                        // header('Content-Type: application/json; charset=utf-8');
                        // echo json_encode(['status' => 'success']);
                }else{

                    //บรรทัดด่านล่าง เพื่อ ทำการส่งค่า status error กลับไปในกรณีที่มีการใช้ fetch api ในการเช็คสถานะการส่งแจ้งเตือน
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['status' => 'error']);
                }
            }
    }else{
        // ทำการแจ้งว่าข้อมูลไม่ครบ
        // และหน่วง 1 วินาที จากนั้นกลับไปหน้าหลัก
        echo "<script>
                alert('ข้อมูลไม่ครบ');
                setTimeout(() =>{
                    window.location.href = '../main.php';
                },0);
            </script>";
    }
}else{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'session not found']);
}