<?php
ini_set('session.gc_maxlifetime', 1440);
ini_set('session.cookie_httponly', 1);
session_set_cookie_params([
    'lifetime' => 0, // 0 = session cookie (ปิด browser แล้วหาย)
    'path'     => '/',
    'domain'   => '',       // หรือปล่อยว่างให้ใช้ domain ปัจจุบัน
    'secure'   => true,     // true = ส่ง cookie เฉพาะผ่าน HTTPS
    'httponly' => true,     // ปิดการเข้าถึงจาก JS
    'samesite' => 'Strict', // ป้องกัน CSRF จาก cross-site
]);
session_start();

/** @var AltoRouter $router */

use App\Controllers\Sections\SectionsController;


/*****************************Route Backend ************************************* */ 

// route section ต่างๆ
$router->map('POST', '/[a:section]', function ($section) use ($jwt) {
    return $jwt->handle(function () use ($section) {

        $unlock   = new SectionsController();
        $validate = $unlock->validate($_POST['input_lock']);

        if ($validate === true) {
            $_SESSION['unlocked_sections'][$section] = true;
            require __DIR__ . '/../view/layout/header.php';
            require __DIR__ . "/../view/sections/{$section}/{$section}.html";
            require __DIR__ . '/../view/layout/footer.php';
            exit;
        } else {
            echo '<script>
                    alert("Invalid password");
                     window.history.back();
                </script>';
        }
    });
});

/*****************************Route Frontend ************************************* */ 

$router->map('GET', '/home', function () use ($jwt) {
    return $jwt->handle(function () {
        unset($_SESSION['unlocked_sections']);
        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/main.html';
        require __DIR__ . '/../view/layout/footer.php';
    });
});

$router->map('GET', '/settings', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/settings.html';
        require __DIR__ . '/../view/layout/footer.php';
    });
});

$router->map('GET', '/manage-sub', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/user_settings/sub_list.html';
        require __DIR__ . '/../view/layout/footer.php';
    });
});



/*********************** Route section ****************************/ 

$router->map('GET', '/sec1', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../view/layout/header.php';

        if (! empty($_SESSION['unlocked_sections']['sec1'])) {
            require __DIR__ . '/../view/sections/sec1/sec1.html';
        } else {
            require __DIR__ . '/../view/unlock.html';
            exit;
        }

        require __DIR__ . '/../view/layout/footer.php';
    });
});

$router->map('GET', '/sec1_sub', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../view/layout/header.php';

        if (! empty($_SESSION['unlocked_sections']['sec1'])) {
            require __DIR__ . '/../view/sections/sec1/sec1_sub.html';
        } else {
            require __DIR__ . '/../view/unlock.html';
            exit;
        }

        require __DIR__ . '/../view/layout/footer.php';
    });
});

$router->map('GET', '/sec2', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../view/layout/header.php';

        if (! empty($_SESSION['unlocked_sections']['sec2'])) {
            require __DIR__ . '/../view/sections/sec2/sec2.html';
        } else {
            require __DIR__ . '/../view/unlock.html';
            exit;
        }

        require __DIR__ . '/../view/layout/footer.php';
    });
});

$router->map('GET', '/sec3', function () use ($jwt) {
    return $jwt->handle(function () {
        require __DIR__ . '/../view/layout/header.php';

        if (! empty($_SESSION['unlocked_sections']['sec3'])) {
            require __DIR__ . '/../view/sections/sec3/sec3.html';
        } else {
            require __DIR__ . '/../view/unlock.html';
            exit;
        }

        require __DIR__ . '/../view/layout/footer.php';
    });
});
