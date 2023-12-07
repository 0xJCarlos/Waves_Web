<?php
session_start();
require_once "../database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM products WHERE id = '$id'";
    
    if($conn->query($sql) === TRUE){
        header("Location: productDashboard.php");
    }
    else{
        echo "Error borrando producto: " . $conn->error;
    }

} 
else {
    echo "No id provided";
}
?>