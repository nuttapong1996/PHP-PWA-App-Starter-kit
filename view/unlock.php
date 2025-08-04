
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- ไฟล์ manifest สำหรับ PWA -->
    <!-- <link rel="manifest" href="manifest.json"> -->
    <!-- ไฟล์ font.css ที่เก็บเอา Font -->
    <link rel="stylesheet" href="css/font.css">
    <title>Push Notify</title>
</head>

<body class="ibm-plex-sans-thai-regular">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-sm-12 col-md-8 col-lg-6">
                <form method="POST"  action="<?php echo $section;  ?>" class="card">
                    <div class="card-title">
                        <h4 class="text-center mt-3 mb-0">Unlock to view</h4>
                    </div>
                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <input type="password" class="form-control" id="input_lock" name="input_lock">
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary w-100" id="btnUnlock">Unlock</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>


