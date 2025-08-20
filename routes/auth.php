<?php

use Firebase\JWT\Key;

/** @var AltoRouter $router */

/***************************** Route Backend ************************************* */

$router->map('POST', '/auth/login', function () {
    require __DIR__ . '/../api/auth/login.php';
});

$router->map('POST', '/auth/refresh', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../api/auth/refresh.php';
    });
});

$router->map('POST', '/auth/renew', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../api/auth/renew.php';
    });
});

$router->map('GET', '/auth/logout', function () {
    require __DIR__ . '/../api/auth/logout.php';
});

$router->map('POST', '/auth/register', function () {
    require __DIR__ . '/../api/auth/register.php';
});

$router->map('POST', '/auth/forgot', function () {
    require __DIR__ . '/../api/auth/forgot.php';
});

$router->map('POST', '/auth/reset', function () {
    require __DIR__ . '/../api/auth/reset.php';
});

$router->map('POST', '/auth/change', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../api/auth/change.php';
    });
});

$router->map('POST', '/auth/checkpass', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../api/auth/checkpass.php';
    });
});

/***************************** Route Frontend ************************************* */

$router->map('GET', '/', function () {
    require __DIR__ . '/../view/layout/header.php';
    require __DIR__ . '/../view/auth/login.html';
    require __DIR__ . '/../view/layout/footer_login.php';
});

$router->map('GET', '/login', function () {
    require __DIR__ . '/../view/layout/header.php';
    require __DIR__ . '/../view/auth/login.html';
    require __DIR__ . '/../view/layout/footer_login.php';
});

$router->map('GET', '/register', function () {
    require __DIR__ . '/../view/layout/header.php';
    require __DIR__ . '/../view/auth/regis.html';
    require __DIR__ . '/../view/layout/footer_login.php';
});

$router->map('GET', '/forgot', function () {
    require __DIR__ . '/../view/layout/header.php';
    require __DIR__ . '/../view/auth/forgot.html';
    require __DIR__ . '/../view/layout/footer_login.php';
});

$router->map('GET', '/reset/[a:userCode]/[a:resetToken]', function ($userCode, $resetToken) {
    $_GET['userCode']   = $userCode;
    $_GET['resetToken'] = $resetToken;
    require __DIR__ . '/../view/layout/header.php';
    require __DIR__ . '/../view/auth/reset.html';
    require __DIR__ . '/../view/layout/footer.php';
});
