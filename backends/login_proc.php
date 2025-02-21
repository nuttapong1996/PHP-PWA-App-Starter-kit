<?php 
    session_start();
    require '../includes/connect_db.php';

    if(isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    

    // $data = json_decode(file_get_contents('php://input'), true);
    // $empcode = $data['empcode'];
    // $password = $data['password'];

    $sql ="SELECT username FROM tbl_login WHERE username = :username AND password = :password";
    $stmt =$conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['username'] = $username;
        echo "<script> 
                alert('เข้าสู่ระบบสำเร็จ');
                window.location.href = '../main.php';
            </script>";
        // echo json_encode(['status' => 'success']);
    }else{
        // echo json_encode(['status' => 'error']);
        echo "<script> 
            alert('ไม่พบผู้ใช้งาน');
            window.location.href = '../index.php';
        </script>";
    }
}else{
    echo "<script>
            alert('กรุณากรอกข้อมูลให้ครบ');
            window.location.href = 'index.php';
        </script>";
}



?>