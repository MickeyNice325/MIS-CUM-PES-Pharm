<?php
include("../../fn/config.php");

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $sql = "SELECT * FROM staff WHERE code = '$code'";
    $query = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($query)) {
        $dep_name = fetch_name_by_code($conn, 'department', $row['dep_code']);
        $unit_name = !empty($row['unit_code']) ? fetch_name_by_code($conn, 'unit', $row['unit_code']) : 'N/A';

        $result = [
            'code' => $row['code'],
            'fname' => $row['fname'],
            'lname' => $row['lname'],
            'dep_name' => $dep_name,
            'unit_name' => $unit_name
        ];

        echo json_encode($result);
    } else {
        echo json_encode([]);
    }
}

function fetch_name_by_code($conn, $table, $code) {
    $sql = "SELECT name FROM $table WHERE code = '$code'";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        return $row['name'];
    }
    return 'N/A';
}
?>
