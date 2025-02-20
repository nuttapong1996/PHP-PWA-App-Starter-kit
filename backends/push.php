<?php 
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
require "../vendor/autoload.php";
    function sendNotification($rep_no , $conn_it , $title , $body , $url){
        $sql ="SELECT 
                    sb.endpoint,
                    sb.p256dh,
                    sb.authKey
                FROM
                    tbl_subscribers_it AS sb 
                JOIN 
                    tbl_repair_code AS rc
                ON
                    sb.empcode = rc.reporter_id
                WHERE
                    rc.no_repair = :rep_no";
        $stmt = $conn_it ->prepare($sql);
        $stmt->bindParam(':rep_no',$rep_no,PDO::PARAM_STR);
        $stmt->execute();
    
        $endpoints = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // $body  =  "หมายเลขแจ้งซ่อม : " . $rep_no ."\nจนท.ผู้รับเคส : " . $endpoints[0]['technician_name'];
    
        // $url = "https://app.sqmm.myds.me:8063/sqmm-ithelp/index.php?p=case_dtl&rep_no=" . $rep_no;
    
        $message = [
            'title' => $title,
            'body' => $body,
            'url' => $url
        ];
    
        // VAPID จาก ITMS
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:me@website.com', // can be a mailto: or your website address
                'publicKey' => 'BEeiWibRksLMy4tfDh9eCHthCkeuY46FkGbfoFTkiego48zQHEsEuCVsWtpVU73taYHyGpyJ9aqtB6vN0306gsA', // (recommended) uncompressed public key P-256 encoded in Base64-URL
                'privateKey' => 'NwfOTE9aIi7gxVw0xNifptMAEcxB3dPrsLmgmMIURz4', // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL 
            ],
        ];
    
        $webPush = new WebPush($auth);
    
        foreach($endpoints as $endpoint){
    
            $subscription = Subscription::create([
                'endpoint' => $endpoint['endpoint'],
                'keys' => [
                    'p256dh' => $endpoint['p256dh'],
                    'auth' => $endpoint['authkey']
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
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }


?>