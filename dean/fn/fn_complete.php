<?php
include("../../fn/config.php");

if (!isset($_GET['id'])) {
    die("Error: 'id' not provided in the request.");
}

$id = $_GET['id'];

$query = "UPDATE estimate_score SET dean_status = 'complete' ,dean_date = NOW() WHERE id = ? AND status='complete'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script> window.location = '../dean.php'; </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
