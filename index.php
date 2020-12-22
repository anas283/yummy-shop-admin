<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ./home/home.php");
    exit;
}
 
require_once "config.php";
 
$email = $password = "";
$email_err = $password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($email_err) && empty($password_err)){
        $sql = "SELECT id, username, email, password FROM admin WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            $param_email = $email;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            header("location: ./home/home.php");
                        } else{
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    $email_err = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
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
    <title>Login</title>
</head>
<body>

    <span class="line-1"></span>
    <span class="line-2"></span>
    <span class="line-3"></span>

    <div class="container">
        <div class="col-4 mx-auto card">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h3 class="text-gray mt-10">Sign in to your account</h3>
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
                <div>
                    <a class="btn-link" href="#">Forgot your password?</a>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>
        <div class="col-auto mx-auto mt-20">
            <p class="text-small">Don't have an account? <a class="btn-link" href="register.php" style="margin-left: 5px;">Sign up</a></p>
        </div>
    </div>
    
</body>
</html>