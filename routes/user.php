<?php
/** @var AltoRouter $router */
// use App\Controllers\UnlockController;

$router->map('GET', '/user/profile', function () use ($jwt) {
    return $jwt->handle(function () {
        return require __DIR__ . '/../api/user/profile.php';
    });
});

// เรียกใช้งาน user จาก usercode
$router->map('GET', '/user/profile/[i:usercode]', function ($usercode) use ($jwt) {
    return $jwt->handle(function () use ($usercode) {
        $_GET['usercode'] = $usercode;
        return require __DIR__ . '/../api/user/profile_id.php';
    });
});


$router->map('GET', '/unlock/[**:section]', function($section) {
    include __DIR__ ."/../view/unlock.php";
    
});

// $router->map('POST', '/unlock/[**:section]', function($section) {
//     $controller = new UnlockController();
//     $controller->unlockSection($section);
// });