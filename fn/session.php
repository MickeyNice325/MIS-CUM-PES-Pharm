<?php
session_start();


if (!isset($_SESSION["code"])) {
    
    header("Location: ../login.php");
    exit;
}


$code = $_SESSION["code"];
?>

