<?php
/** @var AltoRouter $router */
use App\Controllers\UnlockController;

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
$router->map('GET', '/unlock/[a:section]', function($section) {
    $controller = new UnlockController();
    $controller->showForm($section);
});

// route สำหรับ submit ฟอร์มปลดล็อก
$router->map('POST', '/unlock/[a:section]', function($section) {
    $controller = new UnlockController();
    $controller->unlockSection($section);
});

// $router->map('GET|POST', '/unlock/[**:section]', function($section) use ($personal) {
//     $personal->handle($section, function () use ($section) {
//         header("Location: /" . $section); // ไปหน้าเดิมหลังปลดล็อก
//         exit;
//     });

//     require __DIR__ . '/../view/unlock.html'; // หน้ากรอกรหัสผ่าน
// });


