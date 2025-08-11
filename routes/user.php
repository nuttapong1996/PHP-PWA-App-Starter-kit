<?php
/** @var AltoRouter $router */

use App\Controllers\User\UnlockController;
use App\Controllers\User\SectionController;


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




// route สำหรับแสดงฟอร์มปลดล็อก
$router->map('GET', '/unlock/[a:section]', function($section) use ($jwt) {
    return $jwt->handle(function () use ($section){
        $_GET['section'] = $section ;
        require __DIR__ . '/../view/unlock.html';
    });
});

// Route Submit ปลดล็อก
$router->map('POST', '/unlock/[a:section]', function($section) use ($jwt) {
    return $jwt->handle(function () use ($section){

        $unlock = new UnlockController;
        $validate = $unlock->unlockSection($_POST['input_lock']);

        if ($validate === true) {
              require __DIR__ . "/../view/{$section}.html";
            exit;
        } else {
            echo 'Invalid password';
        }
    });
});



