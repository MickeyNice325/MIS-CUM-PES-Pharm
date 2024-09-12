<?php
include("../../fn/config.php");

$input = json_decode(file_get_contents('php://input'), true);
$query = $input['query'];

if (!empty($query)) {
    $sql = "SELECT code, fname, lname FROM staff WHERE code LIKE '%$query%' OR fname LIKE '%$query%' OR lname LIKE '%$query%'";
    $result = mysqli_query($conn, $sql);

    $staff = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $staff[] = $row;
    }

    echo json_encode(['results' => $staff]);
} else {
    echo json_encode(['results' => []]);
}
?>
