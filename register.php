<?php
require_once "config.php";
 
// Define variables and initialize with empty values
$first_name = $last_name = $phone_number = $email = $password = $confirm_password = "";
$first_name_err = $last_name_err = $phone_number_err = $email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate form
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter first name.";
    } else {
        $first_name = $_POST["first_name"];
    }

    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter last name.";
    } else {
        $last_name = $_POST["last_name"];
    }

    if(empty(trim($_POST["phone_number"]))){
        $phone_number_err = "Please enter last name.";
    } else {
        $phone_number = $_POST["phone_number"];
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT user_id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
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
    if(empty($first_name_err) && empty($last_name_err) && empty($phone_number_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (first_name, last_name, phone_number, email, level, password) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_first_name, $param_last_name, $param_phone_number, $param_email, $param_level, $param_password);
            
            // Set parameters
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_phone_number = $phone_number;
            $param_email = $email;
            $param_level = "ADMIN";
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: success-register.php");
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
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
    <title>Register</title>
</head>
<body>

    <span class="line-1"></span>
    <span class="line-2"></span>
    <span class="line-3"></span>

    <div class="container mb-100">
        <div class="col-4 mx-auto card">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h3 class="text-gray mt-10">Create an account</h3>
                <div class="form-group my-10 <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
                    <label class="form-label" for="first_name">First Name</label>
                    <input class="form-control" type="text" name="first_name" value="<?php echo $first_name; ?>">
                    <small class="error-msg"><?php echo $first_name_err; ?></small>
                </div>
                <div class="form-group my-10 <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input class="form-control" type="text" name="last_name" value="<?php echo $last_name; ?>">
                    <small class="error-msg"><?php echo $last_name_err; ?></small>
                </div>
                <div class="form-group my-10 <?php echo (!empty($phone_number_err)) ? 'has-error' : ''; ?>">
                    <label class="form-label" for="phone_number">Phone Number</label>
                    <input class="form-control" type="number" name="phone_number" value="<?php echo $phone_number; ?>">
                    <small class="error-msg"><?php echo $phone_number_err; ?></small>
                </div>
                <div class="form-group my-10 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" name="email" value="<?php echo $email; ?>">
                    <small class="error-msg"><?php echo $email_err; ?></small>
                </div>
                <div class="form-group mt-10 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" type="password" name="password">
                    <small class="error-msg"><?php echo $password_err; ?></small>
                </div>
                <div class="form-group mt-10 <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label class="form-label" for="confirm_password">Confirm password</label>
                    <input class="form-control" type="password" name="confirm_password">
                    <small class="error-msg"><?php echo $confirm_password_err; ?></small>
                </div>
                <button type="submit" class="btn-login" style="margin-top: 20px;">Register</button>
            </form>
        </div>
        <div class="col-auto mx-auto mt-20">
            <p class="text-small">Already have an account? <a class="btn-link" href="index.php" style="margin-left: 5px;">Login</a></p>
        </div>
    </div>
    
</body>
</html>