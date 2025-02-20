<?php
session_start();
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
require ("vendor/autoload.php");

// if(isset($_SESSION['empcode'])) {
    require 'includes/connect_db.php';
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents('php://input'), true);
    $empcode = $_SESSION['empcode'];
    $sql = "SELECT empcode, endpoint , p256dh , authKey FROM push_subscribers WHERE empcode =:empcode";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':empcode', $empcode, PDO::PARAM_STR);
    $stmt->execute();

    $endpoints = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:me@website.com', // can be a mailto: or your website address
                'publicKey' => 'BEQdLcaaNBD-nYLwfVdhI8bteRKHIKr4fEn9Dnz6kX5HiRLA64VZlORjXX2ExN9YHKhMmBwHBW1WZOM4zCx11p4', // (recommended) uncompressed public key P-256 encoded in Base64-URL
                'privateKey' => 'JRFsCB91QXzaGKcFTKutlVYa7FV0lU_m-ha1n4O3kf0', // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL 
            ],
        ];

        $message = [
            'title' => $data['title'],
            'body' => $data['body'],
            'url' => $data['url']
        ];

        $webPush = new WebPush($auth);

        foreach($endpoints as $endpoint){
            $subscription = Subscription::create([
                'endpoint' => $endpoint['endpoint'],
                'keys' => [
                    'p256dh' => $endpoint['p256dh'],
                    'auth' => $endpoint['authKey']
                ]
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode($message)
            );
        }

        foreach($webPush->flush() as $report) {
            if($report->isSuccess()){
                echo json_encode(['status' => 'success']);
                // echo "Message sent successfully for {$report->getEndpoint()}.<br>";
                // echo "<a href='index.html'> go back</a>";    
            }else{
                echo json_encode(['status' => 'error']);
                // echo "Message failed to sent for {$report->getEndpoint()}: {$report->getReason()}.<br>";
            }
            // print_r($report);
        }
// }else{
//     echo json_encode(['status' => 'error']);
// }