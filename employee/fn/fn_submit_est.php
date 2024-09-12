<?php
include("../../fn/config.php");
include("../../fn/session.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffCode = $_POST['staffCode'];
    $score = $_POST['score'];
    $opinion = $_POST['opinion'];
    $assessorCode = $_SESSION['code'];

    // Convert current year to Buddhist Era (B.E.)
    $year = date('Y') + 543;

    // Check if there is an existing evaluation for this staff member
    $stmt = $conn->prepare("SELECT * FROM estimate WHERE staff_code = ? AND assessor_code = ?");
    $stmt->bind_param("ss", $staffCode, $assessorCode);
    $stmt->execute();
    $existingEvaluation = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($existingEvaluation) {
        // Update existing evaluation
        $stmt = $conn->prepare("UPDATE estimate SET est_lvl = ?, est_opinion = ?, est_date = NOW(), status = 'waitsubmit' WHERE staff_code = ? AND assessor_code = ?");
        $stmt->bind_param("ssss", $score, $opinion, $staffCode, $assessorCode);
    } else {
        // Insert new evaluation
        $stmt = $conn->prepare("INSERT INTO estimate (est_year, est_lvl, staff_code, est_opinion, est_date, assessor_code, status) VALUES (?, ?, ?, ?, NOW(), ?, 'waitsubmit')");
        $stmt->bind_param("sssss", $year, $score, $staffCode, $opinion, $assessorCode);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
