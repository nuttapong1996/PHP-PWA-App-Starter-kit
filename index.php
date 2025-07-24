<?php
use AltoRouter as Router;
require_once 'vendor/autoload.php';

$router = new Router();
$router->setBasePath('/PHP-PWA-App-Starter-kit');

$router->map('GET', '/', function () {
   require __DIR__ .'/view/login.php';
});
$router->map('GET', '/login', function () {
   require __DIR__ .'/view/login.php';
});
$router->map('GET' ,'/logout',function(){
    require __DIR__ .'/api/user/logout.php';
});

$router->map('GET', '/home', function () {
    require __DIR__ .'/view/main.php';
});

$router->map('POST', '/push', function () {
    require __DIR__ .'/api/push/push.php';
});

$router->map('POST', '/push-all', function () {
    require __DIR__ .'/api/push/push-many.php';
});


// $router->map('GET', '/product/[i:id]', function ($id) {
//     echo 'สินค้ารหัส: ' . $id;
// });
// $router->map('DELETE', '/product/[i:id]', function ($id) {
//     echo 'ลบสินค้ารหัส: ' . $id;
// });

// ตรวจสอบและรัน route
$match = $router->match();
if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}


