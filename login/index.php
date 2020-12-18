<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
require_once "config.php";
 
$username = $password = "";
$username_err = $password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            header("location: welcome.php");
                        } else{
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    $username_err = "No account found with that username.";
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
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="./index.css">
    <title>Login</title>
</head>
<body>

    <span class="line-1"></span>
    <span class="line-2"></span>
    <span class="line-3"></span>

    <div class="container">
        <div class="col-4 mx-auto card">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SEFL"]); ?>" method="post">
                <h3 class="text-gray">Sign in to your account</h3>
                <div class="form-group my-10">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" name="email">
                </div>
                <div class="form-group mt-10">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" type="password" name="password">
                </div>
                <div>
                    <a class="btn-link" href="#">Forgot your password?</a>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>
        <div class="col-auto mx-auto mt-20">
            <p class="text-small">Don't have an account? <a class="btn-link" href="#" style="margin-left: 5px;">Sign up</a></p>
        </div>
    </div>
    
</body>
</html>