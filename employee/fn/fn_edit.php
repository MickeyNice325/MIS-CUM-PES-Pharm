<?php
include('../../fn/config.php');
include('../../fn/session.php');

$id = $_REQUEST['id'];

// Prepare the SQL statement
$sql = "UPDATE estimate SET status='edit' WHERE id = ? ";
$stmt = mysqli_prepare($conn, $sql);

$response = array('status' => 'error', 'message' => 'Unknown error');

if ($stmt) {
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $response['status'] = 'success';
        $response['message'] = 'ส่งคำขอสำเร็จ';
    } else {
        $response['message'] = 'Error executing query: ' . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    $response['message'] = 'Error preparing query: ' . mysqli_error($conn);
}
mysqli_close($conn);

echo json_encode($response);
?>
