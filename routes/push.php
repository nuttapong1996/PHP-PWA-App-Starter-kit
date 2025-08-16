<?php
/** @var AltoRouter $router */


$router->map('GET','/pub',function() use ($jwt){
    return $jwt->handle(function(){
        require __DIR__ .'/../api/push/getPub.php';
    });
});

$router->map('GET','/api/push/getall',function() use ($jwt){
    return $jwt->handle(function(){
        require __DIR__ .'/../api/push/getSubList.php';
    });
});

$router->map('POST', '/api/push/getsub', function () use ($jwt) {
    return $jwt->handle(function () {
         require __DIR__ . '/../api/push/getSub.php';
    });
});

$router->map('POST', '/api/push/unsub', function () use ($jwt) {
    return $jwt->handle(function () {
         require __DIR__ . '/../api/push/unSub.php';
    });
});

$router->map('POST', '/api/push/sub', function () use ($jwt) {
    return $jwt->handle(function () {
         require __DIR__ . '/../api/push/sub.php';
    });
});

$router->map('POST', '/api/push', function () {
    require __DIR__ . '/../api/push/push.php';
});

$router->map('POST', '/api/push-all', function () {
    require __DIR__ . '/../api/push/push-many.php';
});
