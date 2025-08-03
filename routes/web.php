<?php 
/** @var AltoRouter $router */

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

