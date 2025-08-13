<?php
ini_set('session.gc_maxlifetime', 1440);
ini_set('session.cookie_httponly', 1);
session_set_cookie_params([
    'lifetime' => 0,          // 0 = session cookie (ปิด browser แล้วหาย)
    'path'     => '/',
    'domain'   => '', // หรือปล่อยว่างให้ใช้ domain ปัจจุบัน
    'secure'   => true,       // true = ส่ง cookie เฉพาะผ่าน HTTPS
    'httponly' => true,       // ปิดการเข้าถึงจาก JS
    'samesite' => 'Strict',   // ป้องกัน CSRF จาก cross-site
]);
session_start();

/** @var AltoRouter $router */

use App\Controllers\Sections\SectionsController;

$router->map('GET', '/', function () {
    require __DIR__ . '/../view/login.html';
});

$router->map('GET', '/login', function () {
    require __DIR__ . '/../view/login.html';
});

$router->map('GET' , '/register' , function(){
    require __DIR__ .'/../view/regis.html';
});

$router->map('GET', '/home', function () use ($jwt) {
    return $jwt->handle(function () {
        unset($_SESSION['unlocked_sections']);
        require __DIR__ . '/../view/main.html';
    });
});

// ปลดล็อก section ต่างๆ
$router->map('POST', '/[a:section]', function ($section) use ($jwt) {
    return $jwt->handle(function () use ($section) {

        $unlock   = new SectionsController();
        $validate = $unlock->validate($_POST['input_lock']);

        if ($validate === true) {
            $_SESSION['unlocked_sections'][$section] = true;
            require __DIR__ . "/../view/{$section}.html";
            exit;
        } else {
            echo '<script>
                    alert("Invalid password");
                     window.history.back();
                </script>';
        }
    });
});

// section salary
$router->map('GET', '/salary', function () use ($jwt) {
    return $jwt->handle(function () {
        if (!empty($_SESSION['unlocked_sections']['salary'])) {
            require __DIR__ . '/../view/salary.html';
        } else {
            require __DIR__ . '/../view/unlock.html';
            exit;
        }

    });
});

$router->map('GET', '/salary_now', function () use ($jwt) {
    return $jwt->handle(function () {
        if (!empty($_SESSION['unlocked_sections']['salary'])) {
            require __DIR__ . '/../view/salary_now.html';
        } else {
            require __DIR__ . '/../view/unlock.html';
            exit;
        }

    });
});

$router->map('GET' ,'/score',function() use ($jwt){
    return $jwt->handle(function(){
        if (!empty($_SESSION['unlocked_sections']['score'])) {
            require __DIR__ . '/../view/score.html';
        } else {
            require __DIR__ . '/../view/unlock.html';
            exit;
        }
    });
});
