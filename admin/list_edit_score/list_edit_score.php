<?php 
include("nav.php");
include("../../fn/config.php");
include("../../fn/session.php");

if (!isset($_SESSION['code'])) {
    header("Location: ../../login.php");
    exit();
}
$year = date('Y') + 543;
$code = $_SESSION['code'];
$sql = "
    SELECT 
        committee.com_code, 
        evaluator.fname AS evaluator_fname, 
        evaluator.lname AS evaluator_lname, 
        evaluated.fname AS evaluated_fname, 
        evaluated.lname AS evaluated_lname, 
        IFNULL(estimate.est_lvl, 'รอดำเนินการ') AS est_lvl, 
        IFNULL(estimate.est_opinion, 'รอดำเนินการ') AS est_opinion, 
        IFNULL(estimate.status, 'รอดำเนินการ') AS est_status,
        committee.status AS committee_status,
        estimate.id AS estimate_id
        FROM committee 
        JOIN staff AS evaluator ON committee.com_code = evaluator.code 
        LEFT JOIN estimate ON committee.com_code = estimate.assessor_code AND committee.staff_code = estimate.staff_code AND estimate.est_year = $year
        LEFT JOIN staff AS evaluated ON committee.staff_code = evaluated.code
        WHERE committee.status = 'supervisor'
        ORDER BY CASE WHEN estimate.status = 'edit' THEN 1 ELSE 2 END, committee.com_code";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบการให้ผลคะแนน</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default/default.min.css" rel="stylesheet">
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

    <div class="container mt-5 mb-5">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="filter">สถานะ</label>
                <select id="filter" class="form-select">
                    <option value="all">ทั้งหมด</option>
                    <option value="edit">ส่งคำขอแก้ไขคะแนน</option>
                    <option value="waitsubmit">รอผู้ประเมินยืนยันคะแนน</option>
                    <option value="รอดำเนินการ">รอดำเนินการ</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="search-code">ชื่อผู้ประเมิน</label>
                <input type="text" id="search-code" class="form-control" placeholder="ค้นหาด้วยชื่อผู้ประเมิน">
            </div>

        </div>
        <table class="table table-light text-center table-striped">
            <thead>
                <tr>
                    <th>รหัสผู้ประเมิน</th>
                    <th>ผู้ประเมิน</th>
                    <th>ผู้ได้รับการประเมิน</th>
                    <th>ผลการประเมิน</th>
                    <th>ข้อคิดเห็น</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr data-status='" . htmlspecialchars($row['est_status']) . "' data-code='" . htmlspecialchars($row['com_code']) . "'>";
                        echo "<td>" . htmlspecialchars($row['com_code']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['evaluator_fname'] . " " . htmlspecialchars($row['evaluator_lname'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['evaluated_fname'] . " " . htmlspecialchars($row['evaluated_lname'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['est_lvl']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['est_opinion']) . "</td>";
                        if($row['est_status'] == 'edit') {
                            echo "<td><button class='btn btn-warning edit-btn w-100' data-id='" . htmlspecialchars($row['estimate_id']) . "'>ส่งคำขอแก้ไขคะแนน</button></td>";
                        } else if ($row['est_status'] == 'complete') {
                            echo "<td><button class='btn btn-outline-success complete-btn w-100'>เสร็จสิ้น</button></td>";
                        }else if ($row['est_status'] == 'waitsubmit') {
                            echo "<td><button class='btn btn-outline-dark complete-btn w-100'>รอผู้ประเมินยืนยันคะแนน</button></td>";
                        } else {
                            echo "<td><button class='btn btn-outline-primary pending-btn w-100'>รอดำเนินการ</button></td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JavaScript -->
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "อนุมัติการขอแก้ไขคะแนน!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, อนุมัติ!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'fn/fn_apply_edit.php?id=' + id;
                    }
                })
            });
        });

        // Show SweetAlert2 based on msg parameter
        <?php if (isset($_GET['msg'])): ?>
            Swal.fire({
                title: '<?php echo $_GET['msg'] == 'success' ? 'อนุมัติ!' : 'เกิดข้อผิดพลาดในการอนุมัติข้อมูล!'; ?>',
                icon: '<?php echo $_GET['msg'] == 'success' ? 'success' : 'error'; ?>',
                confirmButtonText: 'ตกลง'
            });
        <?php endif; ?>

        function filterRows() {
            var selectedStatus = document.getElementById('filter').value;
            var searchName = document.getElementById('search-code').value.toLowerCase();
            var rows = document.querySelectorAll('tbody tr');

            rows.forEach(function(row) {
                var status = row.getAttribute('data-status');
                var evaluatorName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                var statusMatch = selectedStatus === 'all' || status === selectedStatus;
                var nameMatch = evaluatorName.includes(searchName);

                if (statusMatch && nameMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.getElementById('filter').addEventListener('change', filterRows);
        document.getElementById('search-code').addEventListener('input', filterRows);
    </script>
</body>
</html>
