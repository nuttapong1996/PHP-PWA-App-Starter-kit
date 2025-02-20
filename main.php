<?php 
    session_start();
    if(!isset($_SESSION['empcode'])) {
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="manifest.json">
    <title>Push Notif</title>
</head>
<body>
    <h1>Javascript & PHP push notif demo</h1>
    <h5 id="empname"></h5>
    <button id="BtnSub">Enable Notif</button>
    <button id="BtnSend" >Send</button>
    <!-- <a id="BtnSend" href="send.php">Send</a> -->
    <br><br>
    <a href="logout.php" id="BtnLogout">Logout</a>
</body>
<script src="app.js"></script>
</html>

