<?php 
session_start();
header('Content-Type: application/json');
if(isset($_SESSION['empcode'])){
    echo json_encode([
        'status' => 'success',
        'empcode' => $_SESSION['empcode']
        ]);
}else{
    echo json_encode([
        'status' => 'session not set',
        'empcode' => null
    ]);
}
?>