<?php 
include("nav.php");
include("../../fn/config.php");
include("../../fn/session.php");

if (!isset($_SESSION['code'])) {
    header("Location: ../../login.php");
    exit();
}
$code = $_SESSION['code'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบสิทธิ์ของคณะกรรมการ</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: "Noto Sans Thai", sans-serif;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
        $sql1 = "SELECT c.staff_code, c.staff_name as staff_name,
                GROUP_CONCAT(CONCAT(com.fname, ' ', com.lname) SEPARATOR '<br>') as com_name
                FROM committee c 
                JOIN staff com ON c.com_code = com.code 
                WHERE c.status = 'committee'
                GROUP BY c.staff_code, c.staff_name";
        $result1 = $conn->query($sql1);

        if (!$result1) {
            die("Query failed: " . $conn->error);
        }

        $sql2 = "SELECT com.code as com_code, 
                CONCAT(com.fname, ' ', com.lname) as com_name,
                GROUP_CONCAT(CONCAT(s.fname, ' ', s.lname) SEPARATOR '<br>') as staff_name
                FROM committee c
                JOIN staff s ON c.staff_code = s.code
                JOIN staff com ON c.com_code = com.code
                WHERE c.status = 'committee'
                GROUP BY com.code, com.fname, com.lname";
        $result2 = $conn->query($sql2);

        if (!$result2) {
            die("Query failed: " . $conn->error);
        }
    ?>

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2>ตรวจสอบรายชื่อพนักงาน</h2>
                    <a href="pdf/list_committee.php" class="btn btn-outline-success mt-1 mb-1">เปิดไฟล์ PDF</a>
                </div>
                <table class="table table-light text-center table-striped">
                    <thead>
                        <tr>
                            <th style="width:50%">รายชื่อพนักงาน</th>
                            <th style="width:50%">คณะกรรมการประเมิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result1->num_rows > 0) {
                                while ($row1 = $result1->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row1['staff_name'] . "</td>";
                                    echo "<td>" . $row1['com_name'] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>No data available</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <div class="d-flex mb-2">
                    <h2>ตรวจสอบสิทธิ์การประเมินของคณะกรรมการ</h2>
                </div>
                <table class="table table-light text-center table-striped mt-2">
                    <thead>
                        <tr>
                            <th style="width:50%">รายชื่อคณะกรรมการ</th>
                            <th style="width:50%">รายชื่อพนักงานที่สามารถประเมินได้</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result2->num_rows > 0) {
                                while ($row2 = $result2->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row2['com_name'] . "</td>";
                                    echo "<td>" . $row2['staff_name'] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>No data available</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
