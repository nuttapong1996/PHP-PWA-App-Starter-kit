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

$router->map('GET', '/salary', function () use ($jwt) {
    // if Unlockcontroller false or not enter password yet go unlock/salaty
    // else include or reqire salary.html
    return $jwt->handle(function(){
       header('Location: unlock/salary');
    });
});