<?php
if (isset($_GET['p'])) {
    switch ($_GET['p']) {
        case 'login':
            require "login.php";
            break;
        case 'home' :
            require "main.php";
            break;
        case 'logout' :
            require "api/user/logout.php";
            break;
        default:
            require "login.php";
            break;
    }
}else{
    require "login.php";
}
