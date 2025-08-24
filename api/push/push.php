<?php
use App\Controllers\Push\PushController;
use Dotenv\Dotenv;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST');

$root = dirname(__DIR__, 2);
require_once $root . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable($root);
$dotenv->load();

$input = json_decode(file_get_contents("php://input"), true);

$publicKey  = $_ENV['VAPID_PUBLIC_KEY'];
$privateKey = $_ENV['VAPID_PRIVATE_KEY'];

$PushController = new PushController();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($input['usercode']) && isset($input['title']) && isset($input['body']) && isset($input['url'])) {

        $usercode = $input['usercode'];
        $title    = $input['title'];
        $body     = $input['body'];
        $url      = $input['url'];

        $stmt      = $PushController->getAllSubByUserID($usercode);
        $endpoints = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // กำหนดตัวแปร auth สำหรับใช้ในการ ส่งแจ้งเตือน
        $auth = [
            'VAPID' => [
                'subject'    => 'mailto:me@website.com', // can be a mailto: or your website address
                'publicKey'  => trim($publicKey),
                'privateKey' => trim($privateKey),
            ],
        ];

        // กำหนดตัวแปร message สำหรับใช้ในการ ส่งแจ้งเตือนโดยกําหนดค่า title , body , url จาก form
        $message = [
            'title' => $title,
            'body'  => $body,
            'url'   => $url,
        ];

        // สร้างตัวแปร webPush เพื่อใช้ส่งแจ้งเตือน
        $webPush = new WebPush($auth);

        // วนลูปเพื่อส่งแจ้งเตือนให้แต่ละ endpoint
        foreach ($endpoints as $endpoint) {
            $subscription = Subscription::create([
                'endpoint' => $endpoint['endpoint'],
                'keys'     => [
                    'p256dh' => $endpoint['p256dh'],
                    'auth'   => $endpoint['authKey'],
                ],
            ]);
            $webPush->queueNotification(
                $subscription,
                json_encode($message)
            );

            $report = $webPush->sendOneNotification($subscription, json_encode($message));

            if ($report->isSuccess()) {
                echo json_encode([
                    'code'    => 200,
                    'status'  => 'success',
                    'title'   => 'Notification sent',
                    'message' => 'Sent notification to ' . $endpoint['endpoint'] . 'successfully',
                ]);
            } else {
                echo json_encode([
                    'code'    => 400,
                    'status'  => 'error',
                    'title'   => 'Failed to send notification',
                    'message' => $report->getReason(),
                ]);
                // ลบ endpoint ออกจาก DB ถ้าไม่ valid แล้ว
                if ($report->isSubscriptionExpired()) {
                    $PushController->deleteSubByUser($usercode, $endpoint['endpoint']);
                }
            }
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'code'    => 400,
            'status'  => 'error',
            'title'   => 'Invalid request',
            'message' => 'User not found',
        ]);
    }
}
