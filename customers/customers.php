<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

$customer_added_msg = "";
$customer_edited_msg = "";
$customer_deleted_msg = "";

if(isset($_GET['added'])) {

    if($_GET['added'] = 'true') {
        $customer_added_msg = "Customer successfully added!";
    }
}

if(isset($_GET['edited'])) {

    if($_GET['edited'] = 'true') {
        $customer_edited_msg = "Customer successfully edited!";
    }
} 

if(isset($_GET['deleted'])) {

    if($_GET['deleted'] = 'true') {
        $customer_deleted_msg = "Customer successfully deleted!";
    }
} 

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
} else {
    $orders = "";
}

$sql = "SELECT * FROM order_detail";
$result = mysqli_query($link, $sql);

if(mysqli_num_rows($result) > 0) {
    $orderDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $orderDetails = "";
}

$sql = "SELECT * FROM product";
$result = mysqli_query($link, $sql);

if(mysqli_num_rows($result) > 0) {
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $products = "";
}

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
                <img class="shop-logo" src="../images/yummy-logo.png" alt="">
            </a>
        </div>
        <div class="navs">
            
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
        </div>
    </div>

    <?php if(!empty($customer_added_msg)) : ?>
        <div id="snackbar" class="show snackbar-success">
            <?php echo $customer_added_msg; ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($customer_edited_msg)) : ?>
        <div id="snackbar" class="show snackbar-success">
            <?php echo $customer_edited_msg; ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($customer_deleted_msg)) : ?>
        <div id="snackbar" class="show snackbar-danger">
            <?php echo $customer_deleted_msg; ?>
        </div>
    <?php endif; ?>

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

            <?php if (empty($users)) : ?>
                <div class="card card-empty">
                    <h4 class="text-dark text-center">Understand your customers</h4>
                    <p class="text-secondary text-center -mt-10">Once you start making sales, you'll find their details and purchase history here.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($searchCustomers)) : ?>
                <div class="card card-table content">
                    <table>
                        <thead>
                            <tr class="bg-gray">
                                <th>NAME</th>
                                <th class="w-100">ORDERS</th>
                                <th class="w-100">TOTAL SPENT</th>
                                <th class="w-30">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($searchCustomers as $user) : ?>
                                <tr>
                                    <td>
                                        <?php echo $user['first_name'] . ' ' . $user['last_name'] ?>
                                    </td>
                                    <td class="w-100">
                                        -
                                    </td>
                                    <td class="w-100"> 
                                        MYR 0
                                    </td>
                                    <td class="w-30">
                                        <div class="row">
                                            <?php
                                                $data = array($user['user_id'], $user['address'], $user['address2'], $user['city'], $user['zip_code'], $user['phone_number']);
                                                echo "<button onclick='openModalDetail(" . json_encode($data) . ")' class='btn-outline'>More</button>";
                                            ?>
                                            <button onclick="goToEdit(<?php echo $user['user_id']; ?>)" class="btn-outline" style="margin: 7px 4px;">Edit</button>

                                            <form name="delete-form" method="post">
                                                <input type="text" name="user_id" value="<?php echo $user['user_id'] ?>" style="display: none;">
                                                <button type="submit" name="delete-user" class="btn-outline mr-5">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <?php if (!empty($users)) : ?>
                <div class="card card-table content">
                    <div>
                        &nbsp;
                    </div>
                    <table>
                        <thead>
                            <tr class="bg-gray">
                                <th>NAME</th>
                                <th class="w-100">ORDERS</th>
                                <th class="w-100">TOTAL SPENT</th>
                                <th class="w-30">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td>
                                        <?php echo $user['first_name'] . ' ' . $user['last_name'] ?>
                                    </td>
                                    <td class="w-100">
                                        <?php $total_order = 0; ?>
                                        <?php foreach ($orders as $order) : ?>
                                            <?php if($user['user_id'] == $order['user_id']) : ?>
                                                <?php $total_order++ ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php echo $total_order ?>
                                    </td>
                                    <td class="w-100"> 
                                        <?php $total_spent = 0; ?>
                                        <?php foreach ($orders as $order) : ?>
                                            <?php foreach ($orderDetails as $orderDetail) : ?>
                                                <?php foreach ($products as $product) : ?>
                                                    <?php if($order['user_id'] == $user['user_id']) : ?>
                                                        <?php if($product['product_id'] == $orderDetail['product_id'] && $orderDetail['order_id'] == $order['order_id']) : ?>
                                                            <?php $total_spent += $product['price'] * $orderDetail['order_quantity'] + $order['shipping_cost']; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?> 
                                            <?php endforeach; ?> 
                                        <?php endforeach; ?> 
                                        MYR <?php echo number_format((float)$total_spent, 2, '.', '') ?>
                                    </td>
                                    <td class="w-30">
                                        <div class="row">
                                            <?php
                                                $data = array($user['user_id'], $user['address'], $user['address2'], $user['city'], $user['zip_code'], $user['phone_number']);
                                                echo "<button onclick='openModalDetail(" . json_encode($data) . ")' class='btn-outline'>More</button>";
                                            ?>
                                            <button onclick="goToEdit(<?php echo $user['user_id']; ?>)" class="btn-outline" style="margin: 7px 4px;">Edit</button>
                                            <button onclick="openModalDelete(<?php echo $user['user_id']; ?>)" class="btn-outline mr-5">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <div id="modal-detail" class="modal">
        <div class="modal-content" style="margin-top: 70px;">
            <span onclick="closeModalDetail()" class="close">&times;</span>
            <h4 class="mt-10">Customer details</h4>

            <div class="modal-content-detail">
                <p id="address"></p>
                <p id="address2"></p>
                <p id="city"></p>
                <p id="zip_code"></p>

                <p id="phone_number" style="margin-top: 20px;"></p>
            </div>

            <div class="row justify-content-end mt-10">
                <button onclick="closeModalDetail()" class="btn-purple">Close</button>
            </div>
        </div>
    </div>

    <div id="modal-delete" class="modal">
        <form name="cancel-form" method="post">
            <div class="modal-content">
                <span onclick="closeModalDelete()" class="close">&times;</span>
                <h4 class="modal-title">Delete customer</h4>
                <div class="modal-line"></div>
                <p class="text-medium font-weight-normal my-20">Are you sure you want to delete this customer?</p>

                <div class="row justify-content-end">
                    <button onclick="closeModalDelete()" class="btn-outline" style="margin-right: 7px;">No</button>
                    <a id="delete-customer" class="btn-purple" style="margin-right: 7px; text-decoration: none; padding: 2px 13px; padding-top: 10px; height: 28px;">Yes</a>
                </div>
            </div>
        </form>
    </div>

    <script src="./customers.js?v=<?php echo time(); ?>"></script>
    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>