<?php

require_once './functions.php';

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Set user ID
$user_id = $_SESSION['id'];

// Delete old items from the list in the db
$sql = "DELETE FROM list WHERE user_id = '$user_id' AND status = '1'";
mysqli_query($db, $sql);

header('location: index.php');
