<?php

use Dotenv\Dotenv;

$root = str_replace("configs","",__DIR__);  // ตัวอย่าง C:\xampp\htdocs\PHP-PWA-App-Starter-kit\
require_once $root ."vendor/autoload.php";


// เรียกใช้งาน Dotenv สำหรับทำการอ่านไฟล์ .env ที่อยู่ใน root
$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
try{

    //ตั้งค่าการเชื่อมต่อฐานข้อมูล
    $db_host = $_ENV['DB_HOST'];
    $db_user = $_ENV['DB_USERNAME'];
    $db_pass = $_ENV['DB_PASSWORD'];
    $db_name = $_ENV['DB_DATABASE'];

    //สร้างตัวแปรการเชื่อมต่อฐานข้อมูล PDO Object
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

    //ตั้งค่าโหมดการแจ้งเตือนข้อผิดพลาด
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<script>console.log('Connect to Database name ".$db_name." successfully.')</script>";
    // echo "Connection to ".$db_name." successfully.<br>";
}catch(Exception $e)
{
    echo "<h1>Connection failed : </h1>";
    echo "<h3>failed to connect to Database</h3><br>";
    echo "<b>Error code : </b>" . $e->getMessage();
}
