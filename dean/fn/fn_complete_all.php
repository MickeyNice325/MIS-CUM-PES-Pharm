<?php
include("../../fn/config.php");

$query = "UPDATE estimate_score SET dean_status = 'complete' , dean_date = NOW() WHERE status = 'complete'";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "success";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
header("Location: ../dean.php");
exit();
?>
