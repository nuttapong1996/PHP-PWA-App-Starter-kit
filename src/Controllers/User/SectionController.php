<?php 

namespace App\Controllers\User;





class SectionController
{
    public function handle($section)
    {
        $root = str_replace('src\Controllers\User', '', __DIR__);
require_once $root . 'vendor\autoload.php';
        // กรองเฉพาะ section ที่อนุญาต
        $allowed = ['salary', 'profile', 'report'];

        if (!in_array($section, $allowed)) {
            http_response_code(404);
            echo 'Page not found.';
            return;
        }

        // โหลด view ตามชื่อ section
        require $root . "/view/{$section}.html";
    }
}