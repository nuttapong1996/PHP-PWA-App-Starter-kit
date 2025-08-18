<?php
/** @var AltoRouter $router */


/***************************** Route Backend ************************************* */ 
// Route get current user
$router->map('GET', '/user/profile', function () use ($jwt) {
    return $jwt->handle(function () {
        return require __DIR__ . '/../api/user/profile.php';
    });
});

// Route get user by id
$router->map('GET', '/user/profile/[i:usercode]', function ($usercode) use ($jwt) {
    return $jwt->handle(function () use ($usercode) {
        $_GET['usercode'] = $usercode;
        return require __DIR__ . '/../api/user/profile_id.php';
    });
});


