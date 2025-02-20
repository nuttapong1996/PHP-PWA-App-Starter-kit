<?php 
session_start();
if(isset($_SESSION['empcode'])){
require 'includes/connect_db.php';
header('Content-Type: application/json; charset=utf-8');
$empcode = $_SESSION['empcode'];
$sql = "SELECT empcode FROM push_subscribers WHERE empcode = :empcode";
$stmt= $conn->prepare($sql);
$stmt->bindParam(':empcode', $empcode, PDO::PARAM_STR);
$stmt->execute();

    if($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            'status' => 'success' ,
            'empcode' => $row['empcode']
        ]);
    }else{
        echo json_encode([
            'status' => 'error',
            'empcode' => null
        ]);
    }
}else{
    echo json_encode([
        'status' => 'error',
        'empcode' => null
    ]);
}
?>