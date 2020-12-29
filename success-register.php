<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
    <title>Welcome</title>
</head>
<body>

    <span class="line-1"></span>
    <span class="line-2"></span>
    <span class="line-3"></span>

    <div class="container">
        <div class="col-4 mx-auto card">
            <div class="justify-content-center">
                <ion-icon class="check-icon" name="checkmark-circle-outline"></ion-icon>
            </div>
            <h3 class="text-green text-center mt-10">Yay!</h3>
            <p class="text-secondary text-center">Your account has been successfully registered.</p>
            <div class="justify-content-center">
                <a href="index.php" class="btn-purple mx-auto" style="text-decoration: none; padding-top: 10px; height: 29px;">Login</a>
            </div>
        </div>
    </div>

    <style>
        .check-icon {
            width: 100px;
            height: 100px;
            color: #2bca8d;
        }

        .text-green {
            color: #2bca8d;
        }

        .text-secondary {
            margin-top: -10px;
        }
    </style>

    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>