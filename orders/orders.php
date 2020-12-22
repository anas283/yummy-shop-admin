<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}
$card_empty = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./orders.css?v=<?php echo time(); ?>">
    <title>Orders</title>
</head>
<body onload="setDefault()">

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
            <a href="../orders/orders.php" id="order" class="row nav-item tablinks active">
                <div>
                    <ion-icon class="ion-icon logo-active" name="cart-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-active">Orders</p>
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
            <a href="../customers/customers.php" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="person-outline"></ion-icon>
                </div>
                <div>
                    <p>Customers</p>
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
        <div class="tabcontent">
            <div class="row">
                <div class="col-6">
                    <h3>My orders</h3>
                </div>
                <div class="col-6">
                    <button id="test1" onclick="openModalOrder()" class="btn-purple float-right" style="margin-top: 10px;">Create order</button>
                </div>
            </div>
            <div class="row order-nav">
                <div class="tablinks2" onclick="openTabOrders(event,'all')" id="default">
                    <button class="btn-light">All</button>
                </div>
                <div class="tablinks2" onclick="openTabOrders(event,'processing')">
                    <button class="btn-light">Processing</button>
                </div>
                <div class="tablinks2" onclick="openTabOrders(event,'completed')">
                    <button class="btn-light">Completed</button>
                </div>
                <div class="tablinks2" onclick="openTabOrders(event,'cancelled')">
                    <button class="btn-light">Cancelled</button>
                </div>
            </div>
    
            <div id="all" class="card card-table tabcontent2">
                <?php if(!empty($card_empty)) : ?>
                    <div class="card card-empty">
                        <h4 class="text-dark text-center">Manage orders</h4>
                        <p class="text-secondary text-center -mt-10">When your orders start coming in, you will be able to view and process them here.</p>
                    </div>
                <?php endif; ?>

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
                    <tr class="bg-gray">
                        <th>ORDER</th>
                        <th>DATE</th>
                        <th>CUSTOMER</th>
                        <th>ORDER TOTAL</th>
                        <th>PAYMENT</th>
                        <th>FULFILLMENT</th>
                        <th>ITEM(S)</th>
                    </tr>
                    <tr onclick="openOrderDetails()">
                        <td><button class="btn-empty">#1</button></td>
                        <td><button class="btn-empty">December 16, 2020 10:04 AM</button></td>
                        <td>-</td>
                        <td>MYR69.00</td>
                        <td><span class="pending">Pending</span></td>
                        <td><span class="processing">Processing</span></td>
                        <td>1</td>
                    </tr>
                    <tr onclick="openOrderDetails()">
                        <td><button class="btn-empty">#2</button></td>
                        <td><button class="btn-empty">December 17, 2020 7:50 AM</button></td>
                        <td>-</td>
                        <td>MYR89.00</td>
                        <td><span class="success">Success</span></td>
                        <td><span class="success">Completed</span></td>
                        <td>3</td>
                    </tr>
                    <tr onclick="openOrderDetails()">
                        <td><button class="btn-empty">#3</button></td>
                        <td><button class="btn-empty">December 19, 2020 9:15 AM</button></td>
                        <td>-</td>
                        <td>MYR45.00</td>
                        <td><span class="failed">Failed</span></td>
                        <td><span class="failed">Cancelled</span></td>
                        <td>6</td>
                    </tr>
                </table>
            </div>
    
            <div id="processing" class="tabcontent2">
                <div class="card card-empty">
                    <h4 class="text-dark text-center">Manage orders</h4>
                    <p class="text-secondary text-center -mt-10">When your orders start coming in, you will be able to view and process them here.</p>
                </div>
            </div>
            <div id="completed" class="tabcontent2">
                <div class="card card-empty">
                    <h4 class="text-dark text-center">Manage orders</h4>
                    <p class="text-secondary text-center -mt-10">When your orders start coming in, you will be able to view and process them here.</p>
                </div>     
            </div>
            <div id="cancelled" class="tabcontent2">
                <div class="card card-empty">
                    <h4 class="text-dark text-center">Manage orders</h4>
                    <p class="text-secondary text-center -mt-10">When your orders start coming in, you will be able to view and process them here.</p>
                </div>
            </div>
        </div>
    
        <div id="modal-order" class="modal">
            <div class="modal-content">
                <span onclick="closeModalOrder()" class="close">&times;</span>
                <h4>Create order</h4>
    
                <form action="">
                    <div class="form-group mt-10">
                        <label class="form-label">Product name</label>
                        <input class="form-control" type="text">
                    </div>
                    <div class="form-group mt-10">
                        <label class="form-label">Product price</label>
                        <input class="form-control" type="number">
                    </div>
                    <div class="form-group mt-10">
                        <label class="form-label">Date</label>
                        <input class="form-control" type="date">
                    </div>
                </form>
    
                <div class="row justify-content-end">
                    <button onclick="closeModalOrder()" class="btn-outline" style="margin-right: 7px;">
                        Cancel
                    </button>
                    <button class="btn-purple">Save</button>
                </div>
            </div>
        </div>
    </section>

    <script src="./orders.js?v=<?php echo time(); ?>"></script>
    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>