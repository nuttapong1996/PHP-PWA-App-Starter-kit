<?php
use App\Controllers\User\UserController;

header('Content-Type: application/json;  charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$root = dirname(__DIR__,2);
require_once $root . '/vendor/autoload.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['usercode'])) {

        $usercode = $_GET['usercode'];
        $stmt     = $userController->getUserProfileByCode($usercode);

        $resultCount = $stmt->rowCount();

        if ($resultCount > 0) {
            http_response_code(200);
            $arr             = [];
            $arr['response'] = [];
            $arr['count']    = $resultCount;
            $arr['code']     = 200;
            $arr['status']   = 'success';
            $arr['message']  = $resultCount . ' records';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $r = $row;
                array_push($arr['response'], $r);
            }
            echo json_encode($arr);
        } else {
            http_response_code(204);
            echo json_encode([
                'response' => [],
                'count'    => 0,
                'code'     => 204,
                'status'   => 'success',
                'message'  => 'No records found.',
            ]);
        }
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Usercode required']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
