<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

// Define variables and initialize with empty values
$product_id = $customer_id = $date = $shipping = $quantity = "";
// $product_err = $customer_err = $date_err = $shipping_err = "";

// Processing form data when form is submitted
if(isset($_POST['save'])) {

    if(isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
    }
    if(isset($_POST['customer_id'])) {
        $customer_id = $_POST['customer_id'];
    }
    
    $date = $_POST['date'];
    $shipping = $_POST['shipping'];
    $quantity = $_POST['quantity'];
    $user_id = "";

    // Check data before inserting in database
    if((!empty($product_id)) && (!empty($customer_id)) && (!empty($date)) && (!empty($shipping)) && (!empty($quantity))) {

        // Prepare an insert statement
        $sql = "INSERT INTO orders (user_id, payment_status, fulfillment_status, order_date, shipping_cost) VALUES (?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_customer_id, $param_payment_status, $param_fulfillment_status, $param_order_date, $param_shipping);

            // Set parameters
            $param_customer_id = $customer_id;
            $param_payment_status = 'PENDING';
            $param_fulfillment_status = 'PROCESSING';
            $param_order_date = $date;
            $param_shipping = $shipping;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {

                // get order_id from orders table

                $sql = "SELECT order_id, user_id FROM orders WHERE user_id = $customer_id";
                $result = mysqli_query($link, $sql);

                if (mysqli_num_rows($result) > 0) {

                    while($row = mysqli_fetch_assoc($result)) {
                        $order_id = $row['order_id'];
                    }

                    // Prepare an insert statement
                    $sql = "INSERT INTO order_detail (order_id, product_id, order_quantity) VALUES (?, ?, ?)";

                    if($stmt = mysqli_prepare($link, $sql)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "sss", $param_order_id, $param_product_id, $param_quantity);

                        // Set parameters
                        $param_order_id = $order_id;
                        $param_product_id = $product_id;
                        $param_quantity = $quantity;

                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt)){
                            // echo "Order detail data inserted :)";
                        } else{
                            echo "Something went wrong. Please try again later.";
                        }
                    } else {
                        echo 'Failed to insert details';
                    }
                } else {
                    echo "0 results";
                }
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

$orders = "";
$searchOrders = "0";
$noResult = "";
$noOrder = "";

if(isset($_POST['search'])) {

    $search = $_POST['search'];

    $sql = "SELECT orders.order_id, orders.order_date, users.user_id, users.first_name, users.last_name, orders.payment_status, orders.fulfillment_status, order_detail.order_quantity
    FROM orders
    INNER JOIN order_detail on orders.order_id = order_detail.order_id
    INNER JOIN users ON orders.user_id = users.user_id
    WHERE orders.order_id = $search";

    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $searchOrders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $orders = "0";
    } else {
        $searchOrders = "";
        $noResult = "y";
    }
} else {
    $sql = "SELECT * FROM users WHERE level = 'CUSTOMER'";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $users = "";
    }

    $sql = "SELECT * FROM orders";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $searchOrders = "0";
    } else {
        $orders = "";
        $noOrder = "y";
    }

    $sql = "SELECT * FROM order_detail";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $orderDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $orderDetails = "";
    }

    $sql = "SELECT * FROM orders WHERE fulfillment_status = 'PROCESSING'";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $orders_processing = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $orders_processing = "";
    }

    $sql = "SELECT * FROM orders WHERE fulfillment_status = 'COMPLETED'";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $orders_completed = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $orders_completed = "";
    }

    $sql = "SELECT * FROM orders WHERE fulfillment_status = 'CANCELLED'";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $orders_cancelled = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $orders_cancelled = "";
    }

    $sql = "SELECT * FROM product";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $products = "";
    }
}

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
                <img class="shop-logo" src="../images/yummy-logo.png" alt="">
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
        </div>
    </div>

    <section id="main">
        <nav class="main-nav">
            <div class="nav-item justify-content-end">
                <div class="custom-select" onclick="selectMenu()">
                    <select name="profile" id="profile-menu">
                        <option value="username"><?php echo htmlspecialchars($_SESSION["username"]); ?></option>
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
    
            <div id="all" class="tabcontent2">
                <?php if($noResult == 'y' || empty($searchOrders) && !$searchOrders == '0') : ?>
                    <div class="card card-empty justify-content-center" style="padding-bottom: 35px;">
                        <div class="mx-auto">
                            <p class="text-secondary text-center mt-20">Oops! We could not find any orders</p>
                            <div class="justify-content-center">
                                <button onclick="refresh()" class="btn-outline">Reset search</button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(empty($orders) && $searchOrders == '0') : ?>
                    <div class="card card-empty">
                        <h4 class="text-dark text-center">Manage orders</h4>
                        <p class="text-secondary text-center -mt-10">When your orders start coming in, you will be able to view and process them here.</p>
                    </div>
                <?php endif; ?>

                <?php if($noResult != 'y' && $noOrder != 'y') : ?>
                    <div class="card card-table">
                        <div>
                            <form method="post" class="row">
                                <div>
                                    <ion-icon class="search-icon" name="search-outline"></ion-icon>
                                </div>
                                <div class="form-group">
                                    <input id="search" class="form-control search-input" type="text" name="search" placeholder="Search" style="border: 0; margin-top: 5px;">
                                </div>
                            </form>
                        </div>

                        <?php if(!empty($searchOrders) && empty($orders)) : ?>
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
                                <?php foreach ($searchOrders as $order) : ?>
                                    <tr onclick="openOrderDetails(<?php echo $order['order_id'] ?>, <?php echo $order['user_id'] ?>)">
                                        <td>
                                            <button class="btn-empty">
                                                #<?php echo $order['order_id'] ?>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn-empty">
                                                <?php echo $order['order_date'] ?>
                                            </button>
                                        </td>
                                        <td>
                                            <?php echo $order['first_name'] . ' ' . $order['last_name'] ?>
                                        </td>
                                        <td>
                                            <?php foreach ($orderDetails as $orderDetail) : ?>
                                                <?php foreach ($products as $product) : ?>
                                                    <?php if($product['product_id'] == $orderDetail['product_id']) : ?>
                                                        MYR <?php echo $product['price'] * $orderDetail['order_quantity'] + $order['shipping_cost']; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?> 
                                            <?php endforeach; ?> 
                                        </td>
                                        <td>
                                            <?php if($order['payment_status'] == 'SUCCESS') : ?>
                                                <span class="success">
                                                    <?php echo $order['payment_status'] ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if($order['payment_status'] == 'PENDING') : ?>
                                                <span class="pending">
                                                    <?php echo $order['payment_status'] ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if($order['payment_status'] == 'FAILED') : ?>
                                                <span class="failed">
                                                    <?php echo $order['payment_status'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($order['fulfillment_status'] == 'COMPLETED') : ?>
                                                <span class="success">
                                                    <?php echo $order['fulfillment_status'] ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if($order['fulfillment_status'] == 'PROCESSING') : ?>
                                                <span class="processing">
                                                    <?php echo $order['fulfillment_status'] ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if($order['fulfillment_status'] == 'CANCELLED') : ?>
                                                <span class="failed">
                                                    <?php echo $order['fulfillment_status'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo $order['order_quantity'] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>

                        <?php if(!empty($orders && empty($searchOrders))) : ?>
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
                                <?php foreach ($orders as $order) : ?>
                                    <?php foreach ($users as $user) : ?>
                                        <?php if($order['user_id'] == $user['user_id']) : ?>
                                            <tr onclick="openOrderDetails(<?php echo $order['order_id'] ?>, <?php echo $user['user_id'] ?>)">
                                                <td>
                                                    <button class="btn-empty">
                                                        #<?php echo $order['order_id'] ?>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn-empty">
                                                        <?php echo $order['order_date'] ?>
                                                    </button>
                                                </td>
                                                <td>
                                                    <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                                </td>
                                                <td>
                                                    <?php foreach ($orderDetails as $orderDetail) : ?>
                                                        <?php foreach ($products as $product) : ?>
                                                            <?php if($product['product_id'] == $orderDetail['product_id'] && $orderDetail['order_id'] == $order['order_id']) : ?>
                                                                MYR <?php echo $product['price'] * $orderDetail['order_quantity'] + $order['shipping_cost']; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?> 
                                                    <?php endforeach; ?> 
                                                </td>
                                                <td>
                                                    <?php if($order['payment_status'] == 'SUCCESS') : ?>
                                                        <span class="success">
                                                            <?php echo $order['payment_status'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if($order['payment_status'] == 'PENDING') : ?>
                                                        <span class="pending">
                                                            <?php echo $order['payment_status'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if($order['payment_status'] == 'FAILED') : ?>
                                                        <span class="failed">
                                                            <?php echo $order['payment_status'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($order['fulfillment_status'] == 'COMPLETED') : ?>
                                                        <span class="success">
                                                            <?php echo $order['fulfillment_status'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if($order['fulfillment_status'] == 'PROCESSING') : ?>
                                                        <span class="processing">
                                                            <?php echo $order['fulfillment_status'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if($order['fulfillment_status'] == 'CANCELLED') : ?>
                                                        <span class="failed">
                                                            <?php echo $order['fulfillment_status'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php foreach ($orderDetails as $orderDetail) : ?>
                                                        <?php if($order['order_id'] == $orderDetail['order_id']) : ?>
                                                            <?php echo $orderDetail['order_quantity']; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>    
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?> 
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
    
            <div id="processing" class="tabcontent2">
                <?php if(empty($orders_processing)) : ?>
                    <div class="card card-empty">
                        <h4 class="text-dark text-center">Manage orders</h4>
                        <p class="text-secondary text-center -mt-10">When your orders start coming in, you will be able to view and process them here.</p>
                    </div>
                <?php endif; ?>

                <?php if(!empty($orders_processing)) : ?>
                    <div class="card card-table">
                        <div>
                            <form action="" class="row">
                                <div>
                                    <ion-icon class="search-icon" name="search-outline"></ion-icon>
                                </div>
                                <div class="form-group">
                                    <input style="border: 0; margin-top: 5px;" class="form-control search-input" type="text" name="search" placeholder="Search">
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
                            <?php foreach ($orders_processing as $order) : ?>
                                <?php foreach ($users as $user) : ?>
                                    <?php if($order['user_id'] == $user['user_id']) : ?>
                                        <tr onclick="openOrderDetails(<?php echo $order['order_id'] ?>, <?php echo $user['user_id'] ?>)">
                                            <td>
                                                <button class="btn-empty">
                                                    #<?php echo $order['order_id'] ?>
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn-empty">
                                                    <?php echo $order['order_date'] ?>
                                                </button>
                                            </td>
                                            <td>
                                                <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                            </td>
                                            <td>
                                                <?php foreach ($orderDetails as $orderDetail) : ?>
                                                    <?php foreach ($products as $product) : ?>
                                                        <?php if($product['product_id'] == $orderDetail['product_id'] && $orderDetail['order_id'] == $order['order_id']) : ?>
                                                            MYR <?php echo $product['price'] * $orderDetail['order_quantity'] + $order['shipping_cost']; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?> 
                                                <?php endforeach; ?> 
                                            </td>
                                            <td>
                                                <?php if($order['payment_status'] == 'SUCCESS') : ?>
                                                    <span class="success">
                                                        <?php echo $order['payment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['payment_status'] == 'PENDING') : ?>
                                                    <span class="pending">
                                                        <?php echo $order['payment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['payment_status'] == 'FAILED') : ?>
                                                    <span class="failed">
                                                        <?php echo $order['payment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($order['fulfillment_status'] == 'COMPLETED') : ?>
                                                    <span class="success">
                                                        <?php echo $order['fulfillment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['fulfillment_status'] == 'PROCESSING') : ?>
                                                    <span class="processing">
                                                        <?php echo $order['fulfillment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['fulfillment_status'] == 'CANCELLED') : ?>
                                                    <span class="failed">
                                                        <?php echo $order['fulfillment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php foreach ($orderDetails as $orderDetail) : ?>
                                                    <?php if($order['order_id'] == $orderDetail['order_id']) : ?>
                                                        <?php echo $orderDetail['order_quantity']; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>    
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?> 
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div id="completed" class="tabcontent2">
                <?php if(empty($orders_completed)) : ?>
                    <div class="card card-empty">
                        <h4 class="text-dark text-center">Manage orders</h4>
                        <p class="text-secondary text-center -mt-10">When your orders start coming in, you will be able to view and process them here.</p>
                    </div>
                <?php endif; ?>   

                <?php if(!empty($orders_completed)) : ?>
                    <div class="card card-table">
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
                            <?php foreach ($orders_completed as $order) : ?>
                                <?php foreach ($users as $user) : ?>
                                    <?php if($order['user_id'] == $user['user_id']) : ?>
                                        <tr onclick="openOrderDetails(<?php echo $order['order_id'] ?>, <?php echo $user['user_id'] ?>)">
                                            <td>
                                                <button class="btn-empty">
                                                    #<?php echo $order['order_id'] ?>
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn-empty">
                                                    <?php echo $order['order_date'] ?>
                                                </button>
                                            </td>
                                            <td>
                                                <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                            </td>
                                            <td>
                                                <?php foreach ($orderDetails as $orderDetail) : ?>
                                                    <?php foreach ($products as $product) : ?>
                                                        <?php if($product['product_id'] == $orderDetail['product_id'] && $orderDetail['order_id'] == $order['order_id']) : ?>
                                                            MYR <?php echo $product['price'] * $orderDetail['order_quantity'] + $order['shipping_cost']; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?> 
                                                <?php endforeach; ?> 
                                            </td>
                                            <td>
                                                <?php if($order['payment_status'] == 'SUCCESS') : ?>
                                                    <span class="success">
                                                        <?php echo $order['payment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['payment_status'] == 'PENDING') : ?>
                                                    <span class="pending">
                                                        <?php echo $order['payment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['payment_status'] == 'FAILED') : ?>
                                                    <span class="failed">
                                                        <?php echo $order['payment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($order['fulfillment_status'] == 'COMPLETED') : ?>
                                                    <span class="success">
                                                        <?php echo $order['fulfillment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['fulfillment_status'] == 'PROCESSING') : ?>
                                                    <span class="processing">
                                                        <?php echo $order['fulfillment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['fulfillment_status'] == 'CANCELLED') : ?>
                                                    <span class="failed">
                                                        <?php echo $order['fulfillment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php foreach ($orderDetails as $orderDetail) : ?>
                                                    <?php if($order['order_id'] == $orderDetail['order_id']) : ?>
                                                        <?php echo $orderDetail['order_quantity']; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>    
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?> 
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?> 
            </div>
            <div id="cancelled" class="tabcontent2">
                <?php if(empty($orders_cancelled)) : ?>
                    <div class="card card-empty">
                        <h4 class="text-dark text-center">Manage orders</h4>
                        <p class="text-secondary text-center -mt-10">When your orders start coming in, you will be able to view and process them here.</p>
                    </div>
                <?php endif; ?>

                <?php if(!empty($orders_cancelled)) : ?>
                    <div class="card card-table">
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
                            <?php foreach ($orders_cancelled as $order) : ?>
                                <?php foreach ($users as $user) : ?>
                                    <?php if($order['user_id'] == $user['user_id']) : ?>
                                        <tr onclick="openOrderDetails(<?php echo $order['order_id'] ?>, <?php echo $user['user_id'] ?>)">
                                            <td>
                                                <button class="btn-empty">
                                                    #<?php echo $order['order_id'] ?>
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn-empty">
                                                    <?php echo $order['order_date'] ?>
                                                </button>
                                            </td>
                                            <td>
                                                <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                            </td>
                                            <td>
                                                <?php foreach ($orderDetails as $orderDetail) : ?>
                                                    <?php foreach ($products as $product) : ?>
                                                        <?php if($product['product_id'] == $orderDetail['product_id'] && $orderDetail['order_id'] == $order['order_id']) : ?>
                                                            MYR <?php echo $product['price'] * $orderDetail['order_quantity'] + $order['shipping_cost']; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?> 
                                                <?php endforeach; ?> 
                                            </td>
                                            <td>
                                                <?php if($order['payment_status'] == 'SUCCESS') : ?>
                                                    <span class="success">
                                                        <?php echo $order['payment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['payment_status'] == 'PENDING') : ?>
                                                    <span class="pending">
                                                        <?php echo $order['payment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['payment_status'] == 'FAILED') : ?>
                                                    <span class="failed">
                                                        <?php echo $order['payment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($order['fulfillment_status'] == 'COMPLETED') : ?>
                                                    <span class="success">
                                                        <?php echo $order['fulfillment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['fulfillment_status'] == 'PROCESSING') : ?>
                                                    <span class="processing">
                                                        <?php echo $order['fulfillment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($order['fulfillment_status'] == 'CANCELLED') : ?>
                                                    <span class="failed">
                                                        <?php echo $order['fulfillment_status'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php foreach ($orderDetails as $orderDetail) : ?>
                                                    <?php if($order['order_id'] == $orderDetail['order_id']) : ?>
                                                        <?php echo $orderDetail['order_quantity']; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>    
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?> 
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    
        <div id="modal-order" class="modal">
            <div class="modal-content" style="margin-top: 50px;">
                <span onclick="closeModalOrder()" class="close">&times;</span>
                <h4 class="mt-10">Create order</h4>
    
                <form name="order-form" method="post">
                    <div class="form-group mt-10">
                        <label class="form-label">Product</label>
                        <select class="btn-outline-select" id="product" name="product_id" style="width: 100%">
                            <option value="">Choose product</option>
                            <?php foreach ($products as $product) : ?> 
                                <?php echo "<option value=" . $product['product_id'] . "> " . " " . $product['product_name'] .  " </option>"; ?>
                            <?php endforeach; ?>
                        </select>
                        <small id="product-err" class="error-msg"></small>
                    </div>
                    <div class="form-group mt-10">
                        <label class="form-label">Customer</label>
                        <select class="btn-outline-select" id="customer" name="customer_id" style="width: 100%">
                            <option value="">Choose customer</option>
                            <?php foreach ($users as $user) : ?>
                                <?php echo "<option value=" . $user['user_id'] . "> " . " " . $user['first_name'] . " " . $user['last_name'] .  " </option>"; ?>
                            <?php endforeach; ?>
                        </select>
                        <small id="customer-err" class="error-msg"></small>
                    </div>
                    <div class="form-group mt-10">
                        <label class="form-label">Date</label>
                        <input class="form-control" type="datetime-local" name="date" id="date">
                        <small id="date-err" class="error-msg"></small>
                    </div>
                    <div class="row">
                        <div class="form-group mt-10 col-6 mr-10">
                            <label class="form-label">Shipping</label>
                            <input class="form-control" type="number" name="shipping" id="shipping" placeholder="MYR">
                            <small id="shipping-err" class="error-msg"></small>
                        </div>
                        <div class="form-group mt-10 col-6">
                            <label class="form-label">Quantity</label>
                            <input class="form-control" type="number" name="quantity" id="quantity">
                            <small id="quantity-err" class="error-msg"></small>
                        </div>
                    </div>
                    <div class="row justify-content-end mt-10">
                        <button onclick="closeModalOrder()" class="btn-outline" style="margin-right: 7px;">
                            Cancel
                        </button>
                        <button onclick="validateForm()" id="submit-btn" name="save" type="button" class="btn-purple">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="./orders.js?v=<?php echo time(); ?>"></script>
    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>