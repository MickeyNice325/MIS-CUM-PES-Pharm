<?php
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $username = $_POST['username'];
    $password = $_POST['password'];

   
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    
    $query = "SELECT * FROM staff WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
   
        $user = mysqli_fetch_assoc($result);
        
        $_SESSION['code'] = $user['code'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fname'] = $user['fname'];
        $_SESSION['lname'] = $user['lname'];

        switch ($user['level']) {
            case 'admin':
                header("location: ../admin/admin_page.php");
                break;
            default:
                header("location: ../employee/employee.php");
                break;
            case 'dean':
                header("location: ../dean/dean.php");
                break;
        }
        exit();
    }else {
        
        $error = "Username หรือ password ไม่ถูกต้อง";
        header("location: ../login.php?error=" . urlencode($error));
        exit();
    }
}
?>
