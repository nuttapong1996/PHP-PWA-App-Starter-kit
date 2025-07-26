<?php
use Dotenv\Dotenv;
$root = str_replace('api\user', '', __DIR__);
require_once $root . 'vendor\autoload.php';
require_once $root . 'configs\connect_db.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$refresh_token = trim($_COOKIE['refresh_token'] ?? '');

if ($refresh_token) {
    $stmt = $conn->prepare("DELETE FROM refresh_tokens WHERE token = :token");
    $stmt->execute([':token' => $refresh_token]);

    setcookie('access_token', '', time() - 3600, '/', '', true, true); 

    setcookie('refresh_token', '', time() - 3600, '/', '', true, true); 
    echo "<script> window.location.href = '../'; </script>";
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No refresh token']);
}
