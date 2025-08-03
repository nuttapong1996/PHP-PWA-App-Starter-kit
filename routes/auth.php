<?php

use App\Middleware\PersonalMiddleware;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/** @var AltoRouter $router */

$router->map('POST', '/auth/login', function () {
    require __DIR__ . '/../api/user/login.php';
});

$router->map('POST', '/auth/refresh', function () {
    require __DIR__ . '/../api/user/refresh.php';
});

$router->map('POST', '/auth/renew', function () {
    require __DIR__ . '/../api/user/renew.php';
});

$router->map('GET', '/auth/logout', function () {
    require __DIR__ . '/../api/user/logout.php';
});


