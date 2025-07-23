<?php
use Minishlink\WebPush\VAPID;
$root = str_replace("configs", "", __DIR__);
require_once $root . "vendor/autoload.php";

header('Content-Type: application/json;');
echo json_encode(VAPID::createVapidKeys());
