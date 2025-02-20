<?php 
    session_start();
    require 'includes/connect_db.php';

    if(isset($_POST['empcode']) && isset($_POST['password'])) {
        $empcode = $_POST['empcode'];
        $password = $_POST['password'];
    

    // $data = json_decode(file_get_contents('php://input'), true);
    // $empcode = $data['empcode'];
    // $password = $data['password'];

    $sql ="SELECT empcode FROM tbl_login WHERE empcode = :empcode AND password = :password";
    $stmt =$conn->prepare($sql);
    $stmt->bindParam(':empcode', $empcode, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['empcode'] = $empcode;
        echo "<script> 
                alert('Login Success');
                window.location.href = 'main.php';
            </script>";
        // echo json_encode(['status' => 'success']);
    }else{
        // echo json_encode(['status' => 'error']);
        echo "<script> 
            alert('Login error');
            window.location.href = 'index.php';
        </script>";
    }
}else{
    echo "<script>
            alert('กรุณากรอกข้อมูลให้ครบ');
            window.location.href = 'index.php';
        </script>";
}



?>