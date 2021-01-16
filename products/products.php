<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

$msg = ""; 
$productId_d = 0;
  
// If upload button is clicked ... 
if (isset($_POST['upload'])) { 

    $filename = $_FILES["uploadfile"]["name"]; 
    $tempname = $_FILES["uploadfile"]["tmp_name"];     
    $folder = "images/".$filename; 
    $product_name = $_POST['product_name'];
    $type = $_POST['type'];
    $product_details = $_POST['product_details'];
    // $productImage = $_POST['productImage'];
    $price = $_POST['price'];
    // Get all the submitted data from the form 
    $sql= "INSERT INTO product (product_name, type, product_details, image, price) 
    VALUES ('$product_name', '$type', '$product_details', '$filename', '$price')"; 

    // Execute query 
    mysqli_query($link, $sql); 
        
    // Now let's move the uploaded image into the folder: image 
    if (move_uploaded_file($tempname, $folder))  { 
        $msg = "Image uploaded successfully"; 
    }else{ 
        $msg = "Failed to upload image"; 
    } 
} 
$result = mysqli_query($link, "SELECT * FROM image"); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./products.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./products.js?v=<?php echo time(); ?>">
    <title>Products</title>
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
            <div class="row">
                <div class="col-6">
                    <h3>Products</h3>
                </div>
                <div class="col-6">
                    <button onclick="openModalAdd()" class="btn-purple float-right" style="margin-top: 10px;"  id="addProductBtn" data-target="#addProduct"> Add Product </button>
                </div>
            </div>
            <div class="div-line"></div>

            <div class="body">		
				<div class="remove-messages"></div>

				<div class="card card-table content">
                    <table id="productTable" style="margin-top: 17px";>
                        <thead>
                            <tr class="bg-gray">
                                <th style="width:10%;">Photo</th>							
                                <th>Product Name</th>
                                <th>Type</th>							
                                <th>Product Details</th>
                                <th>Price</th>
                                <th style="width:15%;">Options</th>
                            </tr>
                        </thead>

                        <?php
                            $link = mysqli_connect("localhost", "root", "", "yummy_shop");
        
                            if($link === false){
                                die("ERROR: Could not connect. " . mysqli_connect_error());
                            }

                            $sql ="SELECT * FROM product";
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result)>0){
                                    while($row = mysqli_fetch_array($result)){
                                        $productId = $row['product_id'];
                                        echo "<tr>";
                                        echo "<td>" . "<img class='product-image' src='images/" . $row['image'] . "' >" ."</td>";
                                        echo "<td>" . $row['product_name'] ."</td>";
                                        echo "<td>" . $row['type'] ."</td>";
                                        echo "<td>" . $row['product_details'] ."</td>";
                                        echo "<td>MYR " . $row['price'] ."</td>";
                                        echo "<td>" . 
                                                '<div class="row">' .
                                                    '<button onclick="editProduct('. $productId .')" data-target="#editProduct" class="btn-outline" style="margin: 7px 4px;">Edit</button>' .
                                                    '<button onclick="openModalDelete('. $productId .')" data-target="#removeProduct" class="btn-outline mr-5">Delete</button>' .
                                                '</div>' .
                                            "</td>";
                                        echo "</tr>";
                                    }
                                    mysqli_free_result($result);
                                }
                                else{
                                    echo "No records matching your query were found.";
                                }
                            }
                            else{
                                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                            }
                        ?>

                    </table><!-- /table -->
                </div>
			</div> <!-- /panel-body -->

                <!--/PopUp-->
                <div id="modal-order" class="modal">
                    <div class="modal-content" style="margin-top: 50px;">
                        <span onclick="closeModalAdd()" class="close">&times;</span>
                        <div class="fade" id="addProduct" tabindex="-1" role="dialog">
                            <div class="dialog">
                                <div class="content">
                                    <form method="POST" action="" enctype="multipart/form-data"> 
                                    <div class="header">
                                        <h4 class="title"> Add Product</h4>
                                    </div>

                                    <div class="body" style="max-height:450px; overflow:auto;">

                                        <div id="add-product-messages"></div>

                                        <div class="form-group">
                                            <label for="productImage" class="form-label col-sm-3">Product Image: </label>
                                            <div class="col-sm-8">
                                                <!-- the avatar markup -->
                                                <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>							
                                                <div class="kv-avatar center-block">	
                                                    <input class="form-control" type="file" name="uploadfile" value=""/> 
                                                </div>
                                            </div>
                                        </div> <!-- /form-group-->	     	           	       

                                        <div class="form-group">
                                            <label for="product_name" class="form-label col-sm-3">Product Name: </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="product_name" value=""/> 
                                            </div>
                                        </div> <!-- /form-group-->	    

                                        <div class="form-group">
                                            <label for="type" class="form-label col-sm-3">Type: </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="type" value=""/> 
                                            </div>
                                        </div> <!-- /form-group-->	        	 

                                        <div class="form-group">
                                            <label for="product_details" class="form-label col-sm-3">Product details: </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="product_details" value=""/> 
                                            </div>
                                        </div> <!-- /form-group-->	     	                 	       

                                        <div class="form-group">
                                            <label for="price" class="form-label col-sm-3">Price: </label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="price" value=""/> 
                                            </div>
                                        </div><!-- /form-group-->
                                    
                                    </div> 
                                    
                                    <div class="mfooter">
                                        <button type="submit" name="upload" class="btn-purple" id="createProductBtn" > Save Changes</button>
                                    </div>      
                                    </form>     
                                </div>  
                            </div>
                        </div> 
                    </div>
                    <!--/PopUp-->
                </div>

                <!-- editProduct -->
                <div id="modal-edit" class="modal">
                    <div class="modal-content" style="margin-top: 50px;">
                        <span onclick="closeModalEdit()" class="close">&times;</span>
                        <div class="fade" id="editProduct" tabindex="-1" role="dialog">
                            <div class="dialog">
                                <div class="content">
                                <form method="POST" action="../editProduct.php" enctype="multipart/form-data"> 
                                <div class="header">
                                    <h4 class="title"> Edit Product</h4>
                                </div>

                                <div class="body" style="max-height:450px; overflow:auto;">

                                    <div id="add-product-messages"></div>

                                    <div class="form-group">
                                        <label for="productImage" class="form-label col-sm-3">Product Image: </label>
                                        <div class="col-sm-8">
                                            <!-- the avatar markup -->
                                            <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>							
                                            <div class="kv-avatar center-block">	
                                                <input class="form-control" type="file" name="uploadfile" value=""/> 
                                            </div>
                                        </div>
                                    </div> <!-- /form-group-->	     	           	       

                                    <div class="form-group">
                                        <label for="product_name" class="form-label col-sm-3">Product Name: </label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="product_name" value=""/> 
                                        </div>
                                    </div> <!-- /form-group-->	    

                                    <div class="form-group">
                                        <label for="type" class="form-label col-sm-3">Type: </label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="type" value=""/> 
                                        </div>
                                    </div> <!-- /form-group-->	        	 

                                    <div class="form-group">
                                        <label for="product_details" class="form-label col-sm-3">Product details: </label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="product_details" value=""/> 
                                        </div>
                                    </div> <!-- /form-group-->	     	                 	       

                                    <div class="form-group">
                                        <label for="price" class="form-label col-sm-3">Price: </label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="price" value=""/> 
                                        </div>
                                    </div><!-- /form-group-->
                                
                                </div> 
                                
                                <div class="mfooter">
                                    <button type="submit" name="edit" class="btn-purple" id="createProductBtn2" > Save Changes</button>
                                </div>      
                                </form>

                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
                

                <!-- removeProduct -->
                <div id="modal-delete" class="modal">
                    <div class="modal-content" style="margin-top: 50px;">
                        <span onclick="closeModalDelete()" class="close">&times;</span>
                        <div class="fade" id="removeProduct" tabindex="-1" role="dialog">
                            <div class="dialog">
                                <div class="content">
                                <!-- <form method="POST" action="./removeProduct.php" enctype="multipart/form-data">  -->
                                    <div class="header">
                                        <h4 class="title"> Remove Product</h4>
                                    </div>
                                    <div class="modal-body">

                                        <div class="removeProductMessages"></div>

                                        <p>Do you really want to remove ?</p>
                                    </div>
                                    <div class="mfooter">
                                        <button onclick="closeModalDelete()" type="button" class="btn-purple" data-dismiss="modal">  Close</button>
                                        <!-- <a class="btn-purple" id="removeProductBtn" > Save Changes</a> -->
                                        <a id="delete-product" class="btn-purple" id="removeProductBtn" style="margin-right: 7px; text-decoration: none; padding: 11px 7px; height: 35px;"> 
                                            Save Changes
                                        </a>
                                    </div>
                                <!-- </form> -->
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </section>


    
    <script src="products.js?v=<?php echo time(); ?>"></script>
    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
    
</body>
</html>