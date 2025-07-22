<?php 
require_once 'loadEnv.php';
loadEnv(__DIR__ . '/.env');
header('Content-Type: text/plain');
echo  $_ENV['VAPID_PUBLIC_KEY'];
?>