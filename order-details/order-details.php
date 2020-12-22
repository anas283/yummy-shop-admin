<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
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
            <a href="#" class="row nav-item tablinks">
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
            <h3 class="text-secondary">Orders</h3>
            <div class="row">
                <h1 class="order-number">#1</h1>
                <small class="order-date">December 16, 2020 10:04 AM</small>
            </div>
            <div class="row">
                <span class="chip pending">Pending</span>
                <span class="chip processing">Processing</span>
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
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-medium font-weight-medium">
                                The Guide To The Galaxy
                            </h4>
                        </div>
                        <div class="col-6 justify-content-end">
                            <h4 class="text-medium font-weight-normal">MYR69.00 x 3</h4>
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
                            <h4 class="text-medium font-weight-normal">MYR7.00</h4>
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
                            <h4 class="text-medium font-weight-normal">MYR76.00</h4>
                        </div>
                    </div>
        
                    <div class="row my-10">
                        <button onclick="openModalPaid()" class="btn-outline" style="margin-right: 7px;">
                            Mark as paid
                        </button>
                        <select class="btn-outline-select" name="status" id="status">
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
    
                <div class="col-6 mt-20">
                    <div class="card card-details">
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
                            <h4 class="text-medium font-weight-normal">Testing</h4>
                        </div>
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
        <div class="modal-content">
            <span onclick="closeModalPaid()" class="close">&times;</span>
            <h4 class="modal-title">Mark order</h4>
            <div class="modal-line"></div>
            <p class="text-medium font-weight-normal my-20">Are you sure you want to mark this order as paid?</p>

            <div class="row justify-content-end">
                <button onclick="closeModalPaid()" class="btn-outline" style="margin-right: 7px;">No</button>
                <button class="btn-purple">Yes</button>
            </div>
        </div>
    </div>

    <script src="./order-details.js?v=<?php echo time(); ?>"></script>
    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>