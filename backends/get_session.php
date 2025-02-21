<?php 
session_start();
header('Content-Type: application/json');
if(isset($_SESSION['username'])){
    echo json_encode([
        'status' => 'success',
        'empcode' => $_SESSION['username']
        ]);
}else{
    echo json_encode([
        'status' => 'session not set',
        'empcode' => null
    ]);
}
?>