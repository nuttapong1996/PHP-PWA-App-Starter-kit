<?php

use App\Middleware\PersonalMiddleware;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/** @var AltoRouter $router */

// Frontend Route
$router->map('GET', '/', function () {
    require __DIR__ . '/../view/login.html';
});

$router->map('GET', '/login', function () {
    require __DIR__ . '/../view/login.html';
});

$router->map('GET' , '/register' , function(){
    require __DIR__ .'/../view/regis.html';
});




// Backend Route
$router->map('POST', '/auth/login', function () {
    require __DIR__ . '/../api/auth/login.php';
});

$router->map('POST', '/auth/refresh', function () {
    require __DIR__ . '/../api/auth/refresh.php';
});

$router->map('POST', '/auth/renew', function () {
    require __DIR__ . '/../api/auth/renew.php';
});

$router->map('GET', '/auth/logout', function () {
    require __DIR__ . '/../api/auth/logout.php';
});

$router->map('POST','/auth/register', function(){
    require __DIR__ . '/../api/auth/register.php';
});



