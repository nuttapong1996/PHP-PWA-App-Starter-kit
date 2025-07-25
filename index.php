<?php
use App\Middleware\JwtMiddleware;
use Dotenv\Dotenv;
use AltoRouter as Router;
require_once 'vendor/autoload.php';


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->setBasePath('/PHP-PWA-App-Starter-kit');

// กำหนด secret key จาก .env
$secret = $_ENV['SECRET_KEY'];
$jwt = new JwtMiddleware($secret);



$router->map('GET', '/', function () {
   require __DIR__ .'/view/login.php';
});
$router->map('GET', '/login', function () {
   require __DIR__ .'/view/login.php';
});
$router->map('GET' ,'/logout',function(){
    require __DIR__ .'/api/user/logout.php';
});

$router->map('GET', '/home', function () use ($jwt) {
   return $jwt->handle(function(){
        require __DIR__ .'/view/main.php';
   });
});

$router->map('GET', '/profile', function () {
    require __DIR__ .'/api/user/profile.php';
});

$router->map('POST', '/push', function () {
    require __DIR__ .'/api/push/push.php';
});

$router->map('POST', '/push-all', function () {
    require __DIR__ .'/api/push/push-many.php';
});


// ตรวจสอบและรัน route
$match = $router->match();
if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}


?>


