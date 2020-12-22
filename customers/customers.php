<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

$sql = "SELECT * FROM customer";
$result = mysqli_query($link, $sql);
$customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./customers.css?v=<?php echo time(); ?>">
    <title>Customers</title>
</head>
<body>

    <div id="mySidenav" class="sidenav">
        <div>
            <a class="logo" href="../home/home.php">
                <img class="shop-logo" src="../images/shop_logo.png" alt="">
            </a>
        </div>
        <div class="navs">
            <a href="../home/home.php" id="overview-tab" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="home-outline"></ion-icon>
                </div>
                <div>
                    <p>Overview</p>
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
            <a href="../products/products.php" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="bag-outline"></ion-icon>
                </div>
                <div>
                    <p>Products</p>
                </div>
            </div>
            <a href="../customers/customers.php" class="row nav-item tablinks active">
                <div>
                    <ion-icon class="ion-icon logo-active" name="person-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-active">Customers</p>
                </div>
            </a>
            <a href="../settings/settings.php" class="row nav-item tablinks">
                <div>    
                    <ion-icon class="ion-icon" name="settings-outline"></ion-icon>
                </div>
                <div>
                    <p>Settings</p>
                </div>
            </a>
        </div>
    </div>

    <section id="main">
        <nav class="main-nav">
            <div class="nav-item justify-content-end">
                <div class="custom-select" onclick="selectMenu()">
                    <select name="profile" id="profile-menu">
                        <option value="username"><?php echo htmlspecialchars($_SESSION["username"]); ?></option>
                        <option value="account">Account profile</option>
                        <option value="change-pass">Change password</option>
                        <option value="logout">Logout</option>
                    </select>
                </div>
            </div>
        </nav>
        <div id="overview" class="tabcontent">
            <div class="row">
                <div class="col-6">
                    <h3>Customers</h3>
                </div>
                <div class="col-6">
                    <button id="test1" onclick="openCustomerForm()" class="btn-purple float-right" style="margin-top: 10px;">Add customer</button>
                </div>
            </div>
            <div class="div-line"></div>

            <?php if (empty($customers)) : ?>
                <div class="card card-empty">
                    <h4 class="text-dark text-center">Understand your customers</h4>
                    <p class="text-secondary text-center -mt-10">Once you start making sales, you'll find their details and purchase history here.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($customers)) : ?>
                <div class="card card-table content">
                    <div>
                        <form action="" class="row">
                            <div>
                                <ion-icon class="search-icon" name="search-outline"></ion-icon>
                            </div>
                            <div class="form-group">
                                <input style="border: 0; margin-top: 5px;" class="form-control search-input" type="text" name="serach" placeholder="Search">
                            </div>
                        </form>
                    </div>
                    <table>
                        <thead>
                            <tr class="bg-gray">
                                <th>NAME</th>
                                <th>ORDERS</th>
                                <th>LAST_ORDER</th>
                                <th>TOTAL SPENT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer) : ?>
                                <tr>
                                    <td>
                                        <button class="btn-empty">
                                            <?php echo $customer['first_name'] . ' ' . $customer['last_name'] ?>
                                        </button>
                                    </td>
                                    <td>
                                        0
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        MYR0.00
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="./customers.js?v=<?php echo time(); ?>"></script>
    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>