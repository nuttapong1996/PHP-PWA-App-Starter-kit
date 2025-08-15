<?php

use Dotenv\Dotenv;

$root = str_replace("api\push", "", __DIR__);
require_once $root . "vendor\autoload.php";

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

echo json_encode([
    "publicKey"  => $_ENV['VAPID_PUBLIC_KEY'],
]);
