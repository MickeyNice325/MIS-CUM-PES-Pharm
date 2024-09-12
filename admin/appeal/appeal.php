<?php 
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
    <title>รายชื่อผู้ขอทบทวนผลการประเมิน</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default/default.min.css">
    <style>
        body {
            font-family: "Noto Sans Thai", sans-serif;
        }
        .divbg {
            background-color: #F4FFE3;
        }
    </style>
    <?php include("nav.php"); ?>
</head>
<body>
    <div class="container mt-5 divbg shadow">
        <br>
        <h4>รายชื่อผู้ขอทบทวนผลการประเมิน</h4>

<?php
$sql_estimate_score = "SELECT * FROM estimate_score WHERE status = 'appeal'";
$query_estimate_score = mysqli_query($conn, $sql_estimate_score); 

if (mysqli_num_rows($query_estimate_score) > 0) {
?>
        <table class="table table-striped table-hover mt-3">
            <thead>
                <tr class="text-center">
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อ-สกุล</th>
                    <th>สังกัด</th>
                    <th>งาน</th>
                    <th>หน่วยงาน</th>
                    <th>ตำแหน่งงาน</th>
                    <th>รายละเอียด</th>
                </tr>
            </thead>
            <tbody>
<?php
    while ($row_estimate_score = mysqli_fetch_array($query_estimate_score)) {
        $staff_code = mysqli_real_escape_string($conn, $row_estimate_score['staff_code']);

        $sql_staff = "SELECT * FROM staff WHERE code = '$staff_code'";
        $query_staff = mysqli_query($conn, $sql_staff);
        $row_staff = mysqli_fetch_array($query_staff);
        $staff_fname = htmlspecialchars($row_staff['fname']);
        $staff_lname = htmlspecialchars($row_staff['lname']);

        $dep_code = mysqli_real_escape_string($conn, $row_staff['dep_code']);
        $sql_dep = "SELECT name FROM department WHERE code = '$dep_code'";
        $query_dep = mysqli_query($conn, $sql_dep);
        $row_dep = mysqli_fetch_array($query_dep);
        $dep_name = $row_dep ? htmlspecialchars($row_dep['name']) : 'N/A';

        $job_code = mysqli_real_escape_string($conn, $row_staff['job_code']);
        $sql_job = "SELECT name FROM job WHERE code = '$job_code'";
        $query_job = mysqli_query($conn, $sql_job);
        $row_job = mysqli_fetch_array($query_job);
        $job_name = $row_job ? htmlspecialchars($row_job['name']) : 'N/A';

        $unit_code = mysqli_real_escape_string($conn, $row_staff['unit_code']);
        $sql_unit= "SELECT name FROM unit WHERE code = '$unit_code'";
        $query_unit = mysqli_query($conn, $sql_unit);
        $row_unit = mysqli_fetch_array($query_unit);
        $unit_name = $row_unit ? htmlspecialchars($row_unit['name']) : 'N/A';

        $position_code = mysqli_real_escape_string($conn, $row_staff['position_code']);
        $sql_position = "SELECT name FROM position WHERE code = '$position_code'";
        $query_position = mysqli_query($conn, $sql_position);
        $row_position = mysqli_fetch_array($query_position);
        $position_name = $row_position ? htmlspecialchars($row_position['name']) : 'N/A';
?>
                <tr class="text-center">
                    <td><?= htmlspecialchars($row_estimate_score['staff_code']) ?></td>
                    <td><?= $staff_fname ?> <?= $staff_lname ?></td>
                    <td><?= $dep_name ?></td>
                    <td><?= $job_name ?></td>
                    <td><?= $unit_name ?></td>
                    <td><?= $position_name ?></td>
                    <td>
                        <form action="fn/fn_appeal_succ.php" method="POST" class="delete-form">
                            <input type="hidden" name="staff_code" value="<?= htmlspecialchars($row_estimate_score['staff_code']) ?>">
                            <button type="submit" class="btn btn-outline-danger w-100">ลบ</button>
                        </form>
                    </td>
                </tr>
<?php 
    } 
?>
            </tbody>
        </table>
<?php 
} else {
    echo "<br> <p class='text-center'>ขณะนี้ไม่มีผู้ยื่นขอทบทวนผลการประเมิน</p> <br>";
}
?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXlkoJtPGv7CsoLfoG2JjQ6g6JqS6YN2U6pKPKYpL0Lud5a3v49I1wAAYAx5" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cV6H1IFGOaOZ3AtS3kztTC1mtxWDb5sKZyU4U4qqvswBbgf6B22V3xG6uUTzTqt" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'คุณต้องการจะลบหรือไม่?',
                    text: "ใช่, ฉันต้องการลบ.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ยอมรับ',
                    cancelButtonText: 'ยกเลิก',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        Swal.fire(
                            'ยกเลิก',
                            '',
                            'error'
                        )
                    }
                });
            });
        });
    </script>
</body>
</html>
