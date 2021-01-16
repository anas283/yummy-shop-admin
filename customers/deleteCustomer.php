<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}

if(isset($_GET['customerId'])) { 
    $customerId = $_GET['customerId'];

    $sql = "DELETE FROM users WHERE user_id = $customerId";

    if(mysqli_query($link, $sql)) {
        header("location: ./customers.php");
    } else {
        // echo "Error deleting customer: " . mysqli_error($conn);
    }
}
?>