<?php 
/** @var AltoRouter $router */
use App\Controllers\User\UnlockController;
$router->map('GET', '/', function () {
    require __DIR__ . '/../view/login.html';
});

$router->map('GET', '/login', function () {
    require __DIR__ . '/../view/login.html';
});

$router->map('GET', '/home', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../view/main.html';
    });
});



$router->map('GET', '/salary', function () use ($jwt) {
    return $jwt->handle(function () {
        $unlock = new UnlockController;
        $unlock->handle('salary', function () {
            require __DIR__ . '/../view/salary.html';
        });
    });
});