<?php
require_once("vendor/autoload.php");
use Minishlink\WebPush\VAPID;

// var_dump(VAPID::createVapidKeys());

if(file_exists('includes/key/public_key.text') && file_exists('includes/key/private_key.text')){
    echo "<script>
            alert('VAPID keys already exist');
            setTimeout(()=>{
                window.location.href = 'index.php';
            },0);
        </script>";
}else{
    file_put_contents('includes/key/public_key.text', VAPID::createVapidKeys()['publicKey']);
    file_put_contents('includes/key/private_key.text', VAPID::createVapidKeys()['privateKey']);
    echo "<script>
            alert('VAPID keys created successfully and saved in includes/key folder');
            setTimeout(()=>{
                window.location.href = 'index.php';
            },0);
        </script>";
}


