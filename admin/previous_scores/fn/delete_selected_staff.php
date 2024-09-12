<?php
include("../../../fn/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_ids'])) {
    $ids = $_POST['selected_ids'];
    $ids = implode(',', array_map('intval', $ids)); // Convert the array to a comma-separated string

    $sql = "DELETE FROM estimate_score WHERE id IN ($ids)";
    
    if (mysqli_query($conn, $sql)) {
        // Success message
        header("Location: ../previous_score.php?message=success"); // Redirect back to your page with a success message
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: ../previous_score.php?message=error"); // Redirect back with an error message
    exit();
}
?>
