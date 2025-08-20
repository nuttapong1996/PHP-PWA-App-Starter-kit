<?php
use AltoRouter as Router;
use App\Middleware\JwtMiddleware;
use Dotenv\Dotenv;
require_once 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->setBasePath($_ENV['BASE_PATH']);

$access_token_name = $_ENV['APP_NAME'] . '_access_token';
$refresh_token_name = $_ENV['APP_NAME'] . '_refresh_token';
$basepath          = $_ENV['BASE_PATH'];
$secret            = $_ENV['SECRET_KEY'];

$jwt = new JwtMiddleware($access_token_name, $refresh_token_name, $basepath, $secret);


require_once __DIR__ . '/routes/auth.php';
require_once __DIR__ . '/routes/user.php';
require_once __DIR__ . '/routes/web.php';
require_once __DIR__ . '/routes/push.php';

// ตรวจสอบและรัน route
$match = $router->match();
if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}
