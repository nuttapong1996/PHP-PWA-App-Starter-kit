<?php
use AltoRouter as Router;
use App\Middleware\JwtMiddleware;
use Dotenv\Dotenv;
require_once 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->setBasePath('/PHP-PWA-App-Starter-kit');

// กำหนด secret key จาก .env
$secret = $_ENV['SECRET_KEY'];
$jwt    = new JwtMiddleware($secret);

$router->map('GET', '/', function () {
    require __DIR__ . '/view/login.html';
});

$router->map('GET', '/login', function () {
    require __DIR__ . '/view/login.html';
});

$router->map('GET', '/offline', function () {
    require __DIR__ . '/view/offline.html';
});

$router->map('POST', '/auth/login', function () {
    require __DIR__ . '/api/user/login.php';
});

$router->map('POST', '/auth/refresh', function () {
    require __DIR__ . '/api/user/refresh.php';
});

$router->map('POST', '/auth/renew', function () {
    require __DIR__ . '/api/user/renew.php';
});

$router->map('GET', '/auth/token', function () {
    require __DIR__ . '/api/user/get_token.php';
});

$router->map('GET', '/auth/logout', function () {
    require __DIR__ . '/api/user/logout.php';
});

$router->map('GET', '/home', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/view/main.html';
    });
});

// เรียกใช้งาน user จาก usercode jwt
$router->map('GET', '/api/profile', function () use ($jwt) {
    return $jwt->handle(function () {
        return require __DIR__ . '/api/user/profile.php';
    });
});

// เรียกใช้งาน user จาก usercode
$router->map('GET', '/api/profile/[i:usercode]', function ($usercode) use ($jwt) {
    return $jwt->handle(function () use ($usercode) {
        $_GET['usercode'] = $usercode;
        return require __DIR__ . '/api/user/profile_id.php';
    });
});

$router->map('POST', '/api/push', function () {
    require __DIR__ . '/api/push/push.php';
});

$router->map('POST', '/api/push-all', function () {
    require __DIR__ . '/api/push/push-many.php';
});

// ตรวจสอบและรัน route
$match = $router->match();
if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}
