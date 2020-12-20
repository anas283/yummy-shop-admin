<?php
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
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
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
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
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username,  $param_email, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: index.php");
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

    <div class="container">
        <div class="col-4 mx-auto card">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h3 class="text-gray mt-10">Create an account</h3>
                <div class="form-group my-10 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label class="form-label" for="username">Username</label>
                    <input class="form-control" type="text" name="username" value="<?php echo $username; ?>">
                    <small class="error-msg"><?php echo $username_err; ?></small>
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