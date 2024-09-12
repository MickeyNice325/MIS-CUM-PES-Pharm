<?php
include('../../fn/config.php');
include('../../fn/session.php');

$assessor_code = $_REQUEST['assessor_code'];

// Prepare the SQL statement
$sql = "DELETE FROM estimate WHERE assessor_code = ? AND status = 'waitsubmit'";
$stmt = $conn->prepare($sql);

$response = array('status' => 'error', 'message' => 'Unknown error');

if ($stmt) {
    // Bind parameters
    $stmt->bind_param("s", $assessor_code);

    // Execute the statement
    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'ยกเลิกสำเร็จ';
    } else {
        $response['message'] = 'Error executing query: ' . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    $response['message'] = 'Error preparing query: ' . $conn->error;
}

// Close the database connection
$conn->close();

echo json_encode($response);
?>
