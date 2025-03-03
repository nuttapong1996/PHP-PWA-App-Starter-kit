<?php 
session_start();
if(isset($_SESSION['username'])) {
    header('Location: main.php');
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
    <!-- ไฟล์ manifest สำหรับ PWA -->
    <link rel="manifest" href="manifest.json">
    <!-- ไฟล์ font.css ที่เก็บเอา Font -->
    <link rel="stylesheet" href="css/font.css">
    <title>PHP PWA Webpush</title>
</head>
<body>
<div class="container ibm-plex-sans-thai-regular">
    <div class="row justify-content-center mt-5">
        <div class="col-sm-12 col-md-8 col-lg-6">
        <h1 class="text-center">PHP PWA Webpush (per user)</h1>
        <form action="backends/login_proc.php" method="POST">
            <div class="card">
                <div class="card-title">
                    <h4 class="text-center mt-3 mb-0">Login</h4>
                </div>
                <div class="card-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control">
                        <label for="username">Username :</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control">
                        <label for="password">Password :</label>
                    </div>
                   <div class="text-center">
                    <button type="submit" class="btn btn-primary w-75">Login</button>
                   </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
</body>
</html>

