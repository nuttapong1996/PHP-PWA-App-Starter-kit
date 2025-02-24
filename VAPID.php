<?php
require_once("vendor/autoload.php");
use Minishlink\WebPush\VAPID;
header('Content-Type: text/plain');
var_dump(VAPID::createVapidKeys());
