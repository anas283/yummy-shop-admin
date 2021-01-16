<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

if(isset($_GET['productId'])) { 
    $productId = $_GET['productId'];

    $sql = "DELETE FROM product WHERE product_id = $productId";

    if(mysqli_query($link, $sql)) {
        header("location: ./products.php");
    } else {
        // echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>