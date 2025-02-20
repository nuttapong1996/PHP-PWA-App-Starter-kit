<?php
require_once("vendor/autoload.php");

use Minishlink\WebPush\VAPID;

var_dump(VAPID::createVapidKeys());

/*
array(2) { ["publicKey"]=> string(87) "BEQdLcaaNBD-nYLwfVdhI8bteRKHIKr4fEn9Dnz6kX5HiRLA64VZlORjXX2ExN9YHKhMmBwHBW1WZOM4zCx11p4" 
["privateKey"]=> string(43) "JRFsCB91QXzaGKcFTKutlVYa7FV0lU_m-ha1n4O3kf0" }
*/