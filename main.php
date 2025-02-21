<?php 
    session_start();
    if(!isset($_SESSION['username'])) {
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="manifest" href="manifest.json">
    <script src="app.js"></script>
    <title>Push Notify</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-sm-12 col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-title">
                        <h4 class="text-center mt-3 mb-0">PHP PWA Webpush (per user)</h4>
                    </div>
                    <div class="card-body">
                        <h6 class="text-muted">Loged as, <?php echo $_SESSION['username']; ?></h6>
                        <div class="card">
                            <div class="card-body">
                                <button id="BtnSub" class="btn btn-primary w-100">Enable Notification</button>
                                <button id="BtnSend" class="btn btn-success w-100">Send</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="logout.php" class="btn btn-danger w-100">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

