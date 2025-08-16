<?php

use Dotenv\Dotenv;


$root = dirname(__DIR__,2);
require_once $root . "/vendor/autoload.php";

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

echo json_encode([
    "publicKey"  => $_ENV['VAPID_PUBLIC_KEY'],
]);
