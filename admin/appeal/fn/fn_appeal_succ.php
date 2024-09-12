<?php
include("../../../fn/config.php");
include("../../../fn/session.php");

if (!isset($_SESSION['code'])) {
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['staff_code'])) {
        $staff_code = mysqli_real_escape_string($conn, $_POST['staff_code']);
        $sql_update = "UPDATE estimate_score SET status = 'complete' WHERE staff_code = '$staff_code'";
        
        if (mysqli_query($conn, $sql_update)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating record: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Staff code not set."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
header("Location: ../appeal.php");
exit();
?>
