<?php
include("../../fn/config.php"); 

if (isset($_GET['com_code'])) {
    $com_code = $_GET['com_code'];

    $sql = "SELECT * FROM committee WHERE com_code = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $com_code);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $committee_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $committee_data[] = $row;
    }

    echo json_encode($committee_data);
} else {
    echo json_encode([]);
}
?>
