<?php
include("../../../fn/config.php");

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['code'], $_POST['ests_year'], $_POST['ests_score'])) {
        $response['status'] = 'error';
        $response['message'] = 'Error: Insufficient POST data received.';
        echo json_encode($response);
        exit();
    }

    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $years = $_POST['ests_year'];
    $scores = $_POST['ests_score'];

    // Check if the staff code exists
    $check_query = "SELECT COUNT(*) AS count FROM staff WHERE code = '$code'";
    $check_result = mysqli_query($conn, $check_query);
    $row = mysqli_fetch_assoc($check_result);

    if ($row['count'] == 0) {
        $response['status'] = 'error';
        $response['message'] = 'Error: Staff code does not exist.';
        echo json_encode($response);
        exit();
    }

    // Check and increment the round if necessary
    $round = 1;
    $sql_round = "SELECT * FROM estimate_score WHERE staff_code = '$code' AND ests_year = '$years'";
    $round_result = mysqli_query($conn, $sql_round);
    
    if (mysqli_num_rows($round_result) > 0) {
        $round_row = mysqli_fetch_assoc($round_result);
        $round = $round_row['round'] + 1;
    }

    // Prepare the insert statement
    $insert_stmt = $conn->prepare("INSERT INTO estimate_score (ests_year, ests_score, staff_code, est_date, round) VALUES (?, ?, ?, NOW(), ?)");
    $insert_stmt->bind_param("ssss", $years, $scores, $code, $round);

    if (!$insert_stmt->execute()) {
        $response['status'] = 'error';
        $response['message'] = 'Error (INSERT): ' . $insert_stmt->error;
        echo json_encode($response);
        exit();
    }

    $response['status'] = 'success';
    $response['message'] = 'Evaluation submitted successfully.';
    echo json_encode($response);
    exit();
}
?>
