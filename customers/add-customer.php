<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

// Define variables and initialize with empty values
$first_name = $last_name = $email = $password = $phone_ = $address = $address2 = $city = $state = $zip_code = "";
$first_name_err = $last_name_err = $email_err = $phone_err = $address_err = $address2_err = $city_err = $state_err = $zip_code_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate form
    if(empty($_POST["first_name"])){
        $first_name_err = "Please enter first name.";
    } else {
        $first_name = $_POST['first_name'];
    }

    if(empty($_POST["last_name"])){
        $last_name_err = "Please enter last name.";
    } else {
        $last_name = $_POST['last_name'];
    }

    if(empty($_POST["email"])){
        $email_err = "Please enter last name.";
    } else {
        $email = $_POST['email'];
    }

    if(empty($_POST["phone"])){
        $phone_err = "Please enter phone number.";
    } else {
        $phone = $_POST['phone'];
    }

    if(empty($_POST["address"])){
        $address_err = "Please enter address.";
    } else {
        $address = $_POST['address'];
    }

    if(empty($_POST["address2"])){
        $address2_err = "Please enter address 2.";
    } else {
        $address2 = $_POST['address2'];
    }

    if(empty($_POST["city"])){
        $city_err = "Please enter city.";
    } else {
        $city = $_POST['city'];
    }

    if(empty($_POST["state"])){
        $state_err = "Please enter state.";
    } else {
        $state = $_POST['state'];
    }

    if(empty($_POST["zip_code"])){
        $zip_code_err = "Please enter zip code.";
    } else {
        $zip_code = $_POST['zip_code'];
    }

    // Check input errors before inserting in database
    if(empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($phone_err)
    && empty($address_err) && empty($address2_err) && empty($city_err) && empty($state_err) && empty($zip_code_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (first_name, last_name, phone_number, email, account_type, password) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_first_name, $param_last_name, $param_phone_number, $param_email, $param_account_type, $param_password);
            
            // Set parameters
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_phone_number = $phone;
            $param_email = $email;
            $param_account_type = "CUSTOMER";
            $password = "12345678";
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // get user_id from users table
                $sql = "SELECT user_id, last_name, email, password FROM users WHERE email = ?";
        
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "s", $param_email);
                    
                    $param_email = $email;
                    
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_store_result($stmt);
                        
                        if(mysqli_stmt_num_rows($stmt) == 1){
                            mysqli_stmt_bind_result($stmt, $user_id, $last_name, $email, $hashed_password);
                            if(mysqli_stmt_fetch($stmt)) {
                                echo "Email: " . $param_email . "\nId: " . $user_id;
                                
                                // Prepare an insert statement
                                $sql = "INSERT INTO customer (user_id, address, address2, city, state, zip_code) VALUES (?, ?, ?, ?, ?, ?)";
                    
                                if($stmt = mysqli_prepare($link, $sql)){
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "ssssss", $param_user_id, $param_address, $param_address2, $param_city, $param_state, $param_zip_code);
                                    
                                    // Set parameters
                                    $param_user_id = $user_id;
                                    $param_address = $address;
                                    $param_address2 = $address2;
                                    $param_city = $city;
                                    $param_state = $state;
                                    $param_zip_code = $zip_code;
                                    
                                    // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt)){
                                        // Redirect to customer page
                                        header("location: ./customers.php");
                                    } else{
                                        echo "Something went wrong. Please try again later.";
                                    }
                                }
                            }
                        } else{
                            echo "User id not found.";
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./customers.css?v=<?php echo time(); ?>">
    <title>Add customer</title>
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
                        
                        <option value="change-pass">Change password</option>
                        <option value="logout">Logout</option>
                    </select>
                </div>
            </div>
        </nav>
        <div id="overview" class="tabcontent">
            <div class="col-6 mx-auto">
                <h3 class="text-secondary-2 uppercase">Customers</h3>
                <div class="row">
                    <h2 class="add-customer-text">Add customer</h2>
                </div>
                <div class="div-line"></div>
            </div>

            <div class="col-6 mx-auto">
                <div id="processing" class="tabcontent2">
                    <div class="card card-form">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="row">
                                <div class="form-group my-10 mr-10 col-6 <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
                                    <label class="form-label" for="first_name">First Name</label>
                                    <input class="form-control" type="text" name="first_name" value="<?php echo $first_name; ?>">
                                    <small class="error-msg"><?php echo $first_name_err; ?></small>
                                </div>
                                <div class="form-group mt-10 col-6 <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <input class="form-control" type="text" name="last_name" value="<?php echo $first_name; ?>">
                                    <small class="error-msg"><?php echo $last_name_err; ?></small>
                                </div>
                            </div>
                            <div class="form-group mt-10 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" type="email" name="email" value="<?php echo $email; ?>">
                                <small class="error-msg"><?php echo $email_err; ?></small>
                            </div>
                            <div class="form-group mt-10 <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                                <label class="form-label" for="phone_number">Phone</label>
                                <input class="form-control" type="number" name="phone" value="<?php echo $phone; ?>">
                                <small class="error-msg"><?php echo $phone_err; ?></small>
                            </div>
                            <div class="form-group mt-10 <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                                <label class="form-label" for="address">Address</label>
                                <input class="form-control" type="text" name="address" value="<?php echo $address; ?>">
                                <small class="error-msg"><?php echo $address_err; ?></small>
                            </div>
                            <div class="form-group mt-10 <?php echo (!empty($address2_err)) ? 'has-error' : ''; ?>">
                                <label class="form-label" for="address">Address 2</label>
                                <input class="form-control" type="text" name="address2" value="<?php echo $address2; ?>">
                                <small class="error-msg"><?php echo $address2_err; ?></small>
                            </div>
                            <div class="form-group mt-10 <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                                <label class="form-label" for="city">City</label>
                                <input class="form-control" type="text" name="city" value="<?php echo $city; ?>">
                                <small class="error-msg"><?php echo $city_err; ?></small>
                            </div>
                            <div class="row">
                                <div class="form-group mt-10 mr-10 col-6 <?php echo (!empty($state_err)) ? 'has-error' : ''; ?>">
                                    <label class="form-label" for="state">State</label>
                                    <input class="form-control" type="text" name="state" value="<?php echo $state; ?>">
                                    <small class="error-msg"><?php echo $state_err; ?></small>
                                </div>
                                <div class="form-group mt-10 col-6 <?php echo (!empty($zip_code_err)) ? 'has-error' : ''; ?>">
                                    <label class="form-label" for="zip_code">Zip Code</label>
                                    <input class="form-control" type="text" name="zip_code" value="<?php echo $zip_code; ?>">
                                    <small class="error-msg"><?php echo $zip_code_err; ?></small>
                                </div>
                            </div>
                            <div class="row justify-content-end mt-10">
                                <a href="./customers.php" class="btn-outline" style="margin-right: 7px; text-decoration: none; padding-top: 10px; height: 29px;">Cancel</a>
                                <button type="submit" class="btn-purple">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>