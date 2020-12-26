<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

$orderId = $_GET['order_id']; 

if($_GET['order_id']) {

    $sql = "SELECT * FROM orders WHERE order_id = $orderId";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $orders = "";
    }

    $sql = "SELECT * FROM order_detail WHERE order_id = $orderId";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $orderDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $orderDetails = "";
    }
}

if(isset($_POST['PENDING'])) {

    // Prepare an update statement
    $sql = "UPDATE orders SET payment_status = ? WHERE order_id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "si", $param_payment_status, $param_id);
        
        // Set parameters
        $param_payment_status = "SUCCESS";
        $param_id = $orderId;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $sql = "SELECT * FROM orders WHERE order_id = $orderId";
            $result = mysqli_query($link, $sql);

            if(mysqli_num_rows($result) > 0) {
                $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                $orders = "";
            }

            $sql = "SELECT * FROM order_detail WHERE order_id = $orderId";
            $result = mysqli_query($link, $sql);

            if(mysqli_num_rows($result) > 0) {
                $orderDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                $orderDetails = "";
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Update error!";
    }
    
    // Close connection
    mysqli_close($link);
}

if(isset($_POST['SUCCESS'])) {

    // Prepare an update statement
    $sql = "UPDATE orders SET payment_status = ? WHERE order_id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "si", $param_payment_status, $param_id);
        
        // Set parameters
        $param_payment_status = "PENDING";
        $param_id = $orderId;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $sql = "SELECT * FROM orders WHERE order_id = $orderId";
            $result = mysqli_query($link, $sql);

            if(mysqli_num_rows($result) > 0) {
                $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                $orders = "";
            }

            $sql = "SELECT * FROM order_detail WHERE order_id = $orderId";
            $result = mysqli_query($link, $sql);

            if(mysqli_num_rows($result) > 0) {
                $orderDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                $orderDetails = "";
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Update error!";
    }
    
    // Close connection
    mysqli_close($link);
}

if(isset($_POST['status'])) {
    $status = $_POST['status'];

    // Check fulfillment status before update
    if($status == 'processing') {
        $status = "PROCESSING";
    }
    else if($status == 'completed') {
        $status = "COMPLETED";
    }
    else if($status == 'cancelled') {
        $status = "CANCELLED";
    }

    if(!empty($status)) {

        // Prepare an update statement
        $sql = "UPDATE orders SET fulfillment_status = ? WHERE order_id = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_fulfillment_status, $param_id);
            
            // Set parameters
            $param_fulfillment_status = $status;
            $param_id = $orderId;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $sql = "SELECT * FROM orders WHERE order_id = $orderId";
                $result = mysqli_query($link, $sql);

                if(mysqli_num_rows($result) > 0) {
                    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
                } else {
                    $orders = "";
                }

                $sql = "SELECT * FROM order_detail WHERE order_id = $orderId";
                $result = mysqli_query($link, $sql);

                if(mysqli_num_rows($result) > 0) {
                    $orderDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
                } else {
                    $orderDetails = "";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Update error!";
        }
        
        // Close connection
        mysqli_close($link);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./order-details.css?v=<?php echo time(); ?>">
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
            <a href="../orders/orders.php" id="order" class="row nav-item tablinks active">
                <div>
                    <ion-icon class="ion-icon logo-active" name="cart-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-active"">Orders</p>
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
            <?php foreach ($orders as $order) : ?>
                <h3 class="text-secondary-2">Orders</h3>
                <div class="row">
                    <h1 class="order-number mr-5">
                        #<?php echo $order['order_id']; ?>
                    </h1>
                    <small class="order-date">
                        <?php echo $order['order_date'] ?>
                    </small>
                    <!-- <small class="order-date">December 16, 2020 10:04 AM</small> -->
                </div>
                <div class="row">
                    <?php if($order['payment_status'] == 'SUCCESS') : ?>
                        <span class="chip mr-5 success">
                            <?php echo $order['payment_status'] ?>
                        </span>
                    <?php endif; ?>
                    <?php if($order['payment_status'] == 'PENDING') : ?>
                        <span class="chip mr-5 pending">
                            <?php echo $order['payment_status'] ?>
                        </span>
                    <?php endif; ?>
                    <?php if($order['payment_status'] == 'FAILED') : ?>
                        <span class="chip mr-5 failed">
                            <?php echo $order['payment_status'] ?>
                        </span>
                    <?php endif; ?>

                    <?php if($order['fulfillment_status'] == 'COMPLETED') : ?>
                        <span class="chip success">
                            <?php echo $order['fulfillment_status'] ?>
                        </span>
                    <?php endif; ?>
                    <?php if($order['fulfillment_status'] == 'PROCESSING') : ?>
                        <span class="chip processing">
                            <?php echo $order['fulfillment_status'] ?>
                        </span>
                    <?php endif; ?>
                    <?php if($order['fulfillment_status'] == 'CANCELLED') : ?>
                        <span class="chip failed">
                            <?php echo $order['fulfillment_status'] ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="row mt-5">
                    <button onclick="printOrder()" class="btn-outline" style="margin-right: 7px;">
                        Print
                    </button>
                    <button onclick="openModalCancel()" class="btn-outline" style="margin-right: 7px;">
                        Cancel order
                    </button>
                </div>
        
                <div class="row">
                    <div class="card col-6 card-details mt-20 mr-20 h-fit">
                        <?php foreach ($orderDetails as $orderDetail) : ?>
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="text-medium font-weight-medium">
                                        The Guide To The Galaxy
                                    </h4>
                                </div>
                                <div class="col-6 justify-content-end">
                                    <h4 class="text-medium font-weight-normal">
                                        MYR0.00 x <?php echo $orderDetail['order_quantity']; ?>
                                    </h4>
                                </div>
                            </div>
                            
                            <div class="line"></div>
                
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="text-medium font-weight-medium">
                                        Shipping
                                    </h4>
                                </div>
                                <div class="col-6 justify-content-end">
                                    <h4 class="text-medium font-weight-normal">
                                        RM<?php echo $orderDetail['shipping_cost']; ?>
                                    </h4>
                                </div>
                            </div>
                
                            <div class="line"></div>
                
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="text-medium font-weight-medium">
                                        Total
                                    </h4>
                                </div>
                                <div class="col-6 justify-content-end">
                                    <h4 class="text-medium font-weight-normal">MYR0.00</h4>
                                </div>
                            </div>
                
                            <div class="row my-10">
                                <?php foreach ($orders as $order) : ?>
                                    <?php if($order['payment_status'] == 'PENDING') : ?>
                                        <button onclick="openModalPaid()" class="btn-outline" style="margin-right: 7px;">
                                            Mark as paid
                                        </button>
                                    <?php endif; ?>
                                    <?php if($order['payment_status'] == 'SUCCESS') : ?>
                                        <button onclick="openModalPaid()" class="btn-outline" style="margin-right: 7px;">
                                            Mark as unpaid
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <form method="post">
                                    <select onchange="selectStatus()" class="btn-outline-select" name="status" id="status">
                                        <?php if($order['fulfillment_status'] == 'PROCESSING') : ?>
                                            <option value="processing">Processing</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        <?php endif; ?>
                                        <?php if($order['fulfillment_status'] == 'COMPLETED') : ?>
                                            <option value="completed">Completed</option>
                                            <option value="processing">Processing</option>
                                            <option value="cancelled">Cancelled</option>
                                        <?php endif; ?>
                                        <?php if($order['fulfillment_status'] == 'CANCELLED') : ?>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="processing">Processing</option>
                                            <option value="completed">Completed</option>
                                        <?php endif; ?>
                                    </select>
                                    <button id="status-btn" type="submit" style="display: none;"></button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
        
                    <div class="col-6 mt-20">
                        <div class="card card-details">
                            <?php foreach ($orderDetails as $orderDetail) : ?>
                                <div class="row w-full">
                                    <div class="col-6">
                                        <h4 class="text-medium font-weight-medium">
                                        Note
                                        </h4>
                                    </div>
                                    <div class="col-6 float-right">
                                        <button onclick="openModalNote()" class="btn-empty float-right mt-10">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                                <div class="line"></div>
                                <div class="col-auto">
                                    <?php if(empty($orderDetail['order_note'])) : ?>
                                        <h4 class="text-medium font-weight-normal">
                                            &#8210;
                                        </h4>
                                    <?php endif; ?>

                                    <?php if(!empty($orderDetail['order_note'])) : ?>
                                        <h4 class="text-medium font-weight-normal">
                                            <?php echo $orderDetail['order_note']; ?>
                                        </h4>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
        
                        <div class="card card-details mt-10">
                            <div class="col-auto">
                                <h4 class="text-medium font-weight-medium">
                                    Customer
                                </h4>
                            </div>
                            <div class="line"></div>
                            <div class="col-auto">
                                <h4 class="text-medium font-weight-normal">-</h4>
                            </div>
                        </div>
                
                        <div class="card card-details mt-10">
                            <div class="row w-full">
                                <div class="col-6">
                                    <h4 class="text-medium font-weight-medium">
                                        Shipping address
                                    </h4>
                                </div>
                                <div class="col-6 float-right">
                                    <button class="btn-empty float-right mt-10">
                                        Edit
                                    </button>
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="col-auto">
                                <h4 class="text-medium font-weight-normal">-</h4>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <div id="modal-cancel" class="modal">
        <div class="modal-content">
            <span onclick="closeModalCancel()" class="close">&times;</span>
            <h4 class="modal-title">Cancel order</h4>
            <div class="modal-line"></div>
            <p class="text-medium font-weight-normal my-20">Are you sure you want to cancel your this order?</p>

            <div class="row justify-content-end">
                <button onclick="closeModalCancel()" class="btn-outline" style="margin-right: 7px;">No</button>
                <button class="btn-purple">Yes</button>
            </div>
        </div>
    </div>

    <div id="modal-note" class="modal">
        <div class="modal-content">
            <span onclick="closeModalNote()" class="close">&times;</span>
            <h4 class="modal-title">Add note</h4>
            <div class="modal-line"></div>

            <form action="">
                <div class="form-group mt-10">
                    <label class="form-label">Message</label>
                    <textarea class="form-control" name="note" id="note" style="height: 80px; padding-top: 10px;"></textarea>
                </div>
            </form>

            <div class="row justify-content-end">
                <button onclick="closeModalNote()" class="btn-outline" style="margin-right: 7px;">Cancel</button>
                <button class="btn-purple">Save</button>
            </div>
        </div>
    </div>

    <div id="modal-paid" class="modal">
        <form name="paid-form" method="post">
            <?php foreach ($orders as $order) : ?>
                <div class="modal-content">
                    <span onclick="closeModalPaid()" class="close">&times;</span>
                    <h4 class="modal-title">Mark order</h4>
                    <div class="modal-line"></div>
                    <?php if($order['payment_status'] == 'PENDING') : ?>
                        <p class="text-medium font-weight-normal my-20">Are you sure you want to mark this order as paid?</p>
                    <?php endif; ?>
                    <?php if($order['payment_status'] == 'SUCCESS') : ?>
                        <p class="text-medium font-weight-normal my-20">Are you sure you want to mark this order as unpaid?</p>
                    <?php endif; ?>

                    <div class="row justify-content-end">
                        <button onclick="closeModalPaid()" class="btn-outline" style="margin-right: 7px;">No</button>
                        <button type="submit" name="<?php echo $order['payment_status']; ?>" class="btn-purple">Yes</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>                              
    </div>

    <script src="./order-details.js?v=<?php echo time(); ?>"></script>
    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>