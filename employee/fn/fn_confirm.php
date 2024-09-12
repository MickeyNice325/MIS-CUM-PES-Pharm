<?php
include('../../fn/config.php');
include('../../fn/session.php');

$assessor_code = $_REQUEST['assessor_code'];

// Prepare the SQL statement
$sql = "UPDATE estimate SET status='complete' WHERE assessor_code = ? AND status='waitsubmit'";
$stmt = mysqli_prepare($conn, $sql);

$response = array('status' => 'error', 'message' => 'Unknown error');

if ($stmt) {
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $assessor_code);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $response['status'] = 'success';
        $response['message'] = 'บันทึกสำเร็จ';
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
