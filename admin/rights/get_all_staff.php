<?php
include("../../fn/config.php");

$search2 = isset($_GET['search2']) ? mysqli_real_escape_string($conn, $_GET['search2']) : "";

$sql_all_staff = "SELECT s.code, s.fname, s.lname, d.name as dep_name, u.name as unit_name 
                  FROM staff s
                  LEFT JOIN department d ON s.dep_code = d.code
                  LEFT JOIN unit u ON s.unit_code = u.code
                  WHERE s.level != 'admin' AND (s.code LIKE CONCAT('%', ?, '%') OR s.fname LIKE CONCAT('%', ?, '%') OR s.lname LIKE CONCAT('%', ?, '%'))";
$stmt_all_staff = mysqli_prepare($conn, $sql_all_staff);
mysqli_stmt_bind_param($stmt_all_staff, 'sss', $search2, $search2, $search2);
mysqli_stmt_execute($stmt_all_staff);
$result_all_staff = mysqli_stmt_get_result($stmt_all_staff);

$all_staff = [];
while ($row = mysqli_fetch_assoc($result_all_staff)) {
    $all_staff[] = $row;
}

echo json_encode($all_staff);
?>
