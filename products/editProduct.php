<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

$productId = $_GET['productId']; 

if($_GET['productId']) {

    $sql = "SELECT * FROM product WHERE product_id = $productId";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $products = "";
    }
}

// Define variables and initialize with empty values
$product_image = $product_name = $product_type = $product_detail = $product_price = "";
$product_image_err = $product_name_err = $product_type_err = $product_detail_err = $product_price_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_image = $_FILES["uploadfile"]["name"]; 
    $tempname = $_FILES["uploadfile"]["tmp_name"];     
    $folder = "images/".$product_image; 

    if(empty($_POST["product_name"])){
        $product_name_err = "Please enter product name.";
    } else {
        $product_name = $_POST['product_name'];
    }

    if(empty($_POST["product_type"])){
        $product_type_err = "Please enter product type.";
    } else {
        $product_type = $_POST['product_type'];
    }

    if(empty($_POST["product_detail"])){
        $product_detail_err = "Please enter product detail.";
    } else {
        $product_detail = $_POST['product_detail'];
    }

    if(empty($_POST["product_price"])){
        $product_price_err = "Please enter product price.";
    } else {
        $product_price = $_POST['product_price'];
    }

    // Check input errors before inserting in database
    if(empty($product_name_err) && empty($product_type_err) && empty($product_detail_err) && empty($product_price_err)){
        if(empty($product_image)) {
            // Prepare an update statement
            $sql = "UPDATE product SET product_name = ?, type = ?, product_details = ?, price = ? WHERE product_id = ?";

            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssssi", $param_product_name, $param_product_type, $param_product_detail, $param_product_price, $param_product_id);
                
                // Set parameters
                $param_product_name = $product_name;
                $param_product_type = $product_type;
                $param_product_detail = $product_detail;
                $param_product_price = $product_price;
                $param_product_id = $productId;

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){

                    // Redirect to customer page
                    header("location: ./products.php?edited=true");
                } else{
                    echo "Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        } else {
            // Prepare an update statement
            $sql = "UPDATE product SET product_name = ?, type = ?, product_details = ?, image = ?, price = ? WHERE product_id = ?";

            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssssi", $param_product_name, $param_product_type, $param_product_detail, $param_product_image, $param_product_price, $param_product_id);
                
                // Set parameters
                $param_product_name = $product_name;
                $param_product_type = $product_type;
                $param_product_detail = $product_detail;
                $param_product_image = $product_image;
                $param_product_price = $product_price;
                $param_product_id = $productId;

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Now let's move the uploaded image into the folder: image 
                    if (move_uploaded_file($tempname, $folder))  { 
                        $msg = "Image uploaded successfully"; 
                    } else{ 
                        $msg = "Failed to upload image"; 
                    }

                    // Redirect to customer page
                    header("location: ./products.php?edited=true");
                } else{
                    echo "Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
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
    <link rel="stylesheet" href="./products.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../customers/customers.css?v=<?php echo time(); ?>">
    <title>Edit product</title>
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
            <a href="../products/products.php" class="row nav-item tablinks active">
                <div>
                    <ion-icon class="ion-icon logo-active" name="bag-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-active">Products</p>
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
        <div id="overview" class="tabcontent">
            <div class="col-6 mx-auto">
                <h3 class="text-secondary-2 uppercase">Products</h3>
                <div class="row">
                    <h2 class="add-customer-text">Edit product</h2>
                </div>
                <div class="div-line"></div>
            </div>

            <div class="col-6 mx-auto">
                <div id="processing" class="tabcontent2">
                    <div class="card card-form">
                        <?php foreach ($products as $product) : ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="productImage" class="form-label col-sm-3">Product Image: </label>
                                    <img class="product-image mt-10" src="images/<?php echo $product['image']; ?>" alt="product-image">
                                    <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>							
                                    <input class="form-control" type="file" name="uploadfile" value="<?php echo $product['image']; ?>"/>
                                    <small class="error-msg"><?php echo $product_image_err; ?></small>
                                </div>	     	           	       

                                <div class="form-group mt-10">
                                    <label for="product_name" class="form-label col-sm-3">Product Name: </label>
                                    <input class="form-control" type="text" name="product_name" value="<?php echo $product['product_name']; ?>"/>
                                    <small class="error-msg"><?php echo $product_name_err; ?></small>
                                </div>	    

                                <div class="form-group mt-10">
                                    <label for="type" class="form-label col-sm-3">Type: </label>
                                    <input class="form-control" type="text" name="product_type" value="<?php echo $product['type']; ?>"/>
                                    <small class="error-msg"><?php echo $product_type_err; ?></small>
                                </div>	        	 

                                <div class="form-group mt-10">
                                    <label for="product_details" class="form-label col-sm-3">Product details: </label>
                                    <input class="form-control" type="text" name="product_detail" value="<?php echo $product['product_details']; ?>"/>
                                    <small class="error-msg"><?php echo $product_detail_err; ?></small>
                                </div>	     	                 	       

                                <div class="form-group mt-10">
                                    <label for="price" class="form-label col-sm-3">Price: </label>
                                    <input class="form-control" type="number" name="product_price" value="<?php echo $product['price']; ?>"/>
                                    <small class="error-msg"><?php echo $product_price_err; ?></small>
                                </div>

                                <div class="row justify-content-end mt-10">
                                    <a href="./products.php" class="btn-outline" style="margin-right: 7px; text-decoration: none; padding-top: 10px; height: 29px;">Cancel</a>
                                    <button type="submit" class="btn-purple">Save</button>
                                </div>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>