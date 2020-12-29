<?php
session_start();
require_once "./config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

// Define variables and initialize with empty values
$password = $confirm_password = "";
$password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_err = "Password must have atleast 8 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE user_id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "SQL error!";
        }
    } else {
        echo "There is an error!";
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
    <link rel="stylesheet" href="./global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./customers/customers.css?v=<?php echo time(); ?>">
    <title>Change password</title>
</head>
<body>

    <div id="mySidenav" class="sidenav">
        <div>
            <a class="logo" href="./home/home.php">
                <img class="shop-logo" src="./images/yummy-logo.png" alt="">
            </a>
        </div>
        <div class="navs">
            <a href="./home/home.php" id="overview-tab" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="home-outline"></ion-icon>
                </div>
                <div>
                    <p>Overview</p>
                </div>
            </a>
            <a href="./orders/orders.php" id="order" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="cart-outline"></ion-icon>
                </div>
                <div>
                    <p>Orders</p>
                </div>
            </a>
            <a href="./products/products.php" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="bag-outline"></ion-icon>
                </div>
                <div>
                    <p>Products</p>
                </div>
            </div>
            <a href="./customers/customers.php" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon logo-active" name="person-outline"></ion-icon>
                </div>
                <div>
                    <p>Customers</p>
                </div>
            </a>
            <a href="./settings/settings.php" class="row nav-item tablinks">
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
                <h3 class="text-secondary-2 uppercase">User</h3>
                <div class="row">
                    <h2 class="add-customer-text">Change password</h2>
                </div>
                <div class="div-line"></div>
            </div>

            <div class="col-6 mx-auto">
                <div id="processing" class="tabcontent2">
                    <div class="card card-form">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group mt-10 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                <label class="form-label" for="password">Password</label>
                                <input class="form-control" type="password" name="password" value="<?php echo $password; ?>">
                                <small class="error-msg"><?php echo $password_err; ?></small>
                            </div>
                            <div class="form-group mt-10 <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                <label class="form-label" for="confirm_password">Confirm Password</label>
                                <input class="form-control" type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                                <small class="error-msg"><?php echo $confirm_password_err; ?></small>
                            </div>
                            <div class="row justify-content-end mt-10">
                                <a href="./home/home.php" class="btn-outline" style="margin-right: 7px; text-decoration: none; padding-top: 10px; height: 29px;">Cancel</a>
                                <button type="submit" class="btn-purple">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="./global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>