<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./home.css?v=<?php echo time(); ?>">
    <title>Home</title>
</head>
<body id="body">

    <div id="mySidenav" class="sidenav">
        <div>
            <a class="logo" href="#"><b>Logo</b></a>
        </div>
        <div class="navs">
            <a href="../home/home.php" id="overview-tab" class="row nav-item tablinks active">
                <div>
                    <ion-icon class="ion-icon logo-active" name="home-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-active">Overview</p>
                </div>
            </a>
            <a href="../orders/orders.php" id="order" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="cart-outline"></ion-icon>
                </div>
                <div>
                    <p>Orders</p>
                </div>
            </a>
            <a href="#" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="bag-outline"></ion-icon>
                </div>
                <div>
                    <p>Products</p>
                </div>
            </div>
            <a href="#" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="person-outline"></ion-icon>
                </div>
                <div>
                    <p>Customers</p>
                </div>
            </a>
            <a href="#" class="row nav-item tablinks">
                <div>    
                    <ion-icon class="ion-icon" name="settings-outline"></ion-icon>
                </div>
                <div>
                    <p>Settings</p>
                </div>
            </a>
            <a href="#" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="home-outline"></ion-icon>
                </div>
                <div>
                    <p>Online store</p>
                </div>
            </a>
        </div>
    </div>

    <section id="main">
        <div id="overview" class="tabcontent">
            <h3>Overview</h3>
            <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
            <a href="../logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </div>
    </section>

    <script src="./home.js"></script>
    <script src="../orders/orders.js"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>

</body>
</html>