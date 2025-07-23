<?php
if (isset($_GET['p'])) {
    switch ($_GET['p']) {
        case 'login':
            require "view/login.php";
            break;
        case 'home' :
            require "view/main.php";
            break;
        case 'logout' :
            require "api/user/logout.php";
            break;
        default:
            require "view/login.php";
            break;
    }
}else{
    require "view/login.php";
}
