<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คณบดี</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: "Noto Sans Thai", sans-serif;
        }
        .divbg {
            background-color: #F4FFE3;
        }
    </style>
</head>
<body>

<?php 
include("nav.php");
include("../fn/config.php");
include("../fn/session.php");

if (!isset($_SESSION['code'])) {
    header("Location: ../login.php");
    exit();
}

$current_year = date("Y") + 543;
$last_year = $current_year - 1;

$code = $_SESSION['code'];
$search = isset($_POST['search']) ? $_POST['search'] : "";

$query = "SELECT estimate_score.*, staff.fname, staff.lname, staff.photo, staff.dep_code, staff.job_code, staff.unit_code, staff.position_code, duty.name_sort AS duty_name, duty.name_sort AS name_sort
          FROM estimate_score 
          LEFT JOIN staff ON estimate_score.staff_code = staff.code
          LEFT JOIN duty ON staff.duty_code = duty.code
          WHERE (staff.code LIKE '%$search%' AND (ests_year = '$current_year' OR ests_year = '$last_year'))
          ORDER BY estimate_score.ests_year DESC";

$result = mysqli_query($conn, $query);
?>

    <form class="mt-5 container">
        <div class="d-flex justify-content-end mb-3 ">
            
            <div class="col-md-3">
                <label for="filter">สถานะ</label>
                <select id="statusSelect" class="form-select" onchange="filterAssessments()">
                    <option value="all">ทั้งหมด</option>
                    <option value="waitcomplete">รอผู้ถูกประเมินยืนยัน</option>
                    <option value="complete_not_acknowledged">ผู้ถูกประเมินยืนยันแล้ว</option>
                    <option value="appeal">ผู้ถูกประเมินขอยื่นอุธรธ์</option>
                </select>
            </div>
            <div class="container mb-3">
            <label for="filter">ประเภทพนักงาน:</label>
            <select id="filter" class="form-select" style="width: 25%;">
                <option value="all">ทั้งหมด</option>
                <option value="ข้าราชการ">ข้าราชการ</option>
                <option value="ลูกจ้างประจำ">ลูกจ้างประจำ</option>
                <option value="พนักงานมหาวิทยาลัย">พนักงานมหาวิทยาลัยประจำ</option>
                <option value="พนักงานมหาวิทยาลัยชั่วคราว(พนักงาน ส่วนงาน)">พนักงานมหาวิทยาลัยชั่วคราว</option>
            </select>
        </div>

        <div class="container mt-4 text-end">
        <button type="button" class="btn btn-success w-50" id="awareAll">กดเพื่อรับทราบทั้งหมด</button>
        </div>
        </div>
        

    </form>
    

    <div class="container mt-5 mb-5 shadow divbg">
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th style="width:15%">ชื่อ - สกุล</th>
                    <th style="width:15%">ประเภทพนักงาน</th>
                    <th style="width:10%">คะแนนประเมิน</th>
                    <th style="width:10%">ผลการประเมิน</th>
                    <th style="width:5%">รอบที่</th>
                    <th style="width:5%">ปี</th>
                    <th style="width:40%">สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { 
                    $duty_full_name = is_null($row['name_sort']) ? "N/A" : htmlspecialchars($row['name_sort']);
                    $row_status = htmlspecialchars($row['status']);
                    $dean_status = htmlspecialchars($row['dean_status']);
                    $status2 = '';
                    if ($row_status === 'complete' && $dean_status === '') {
                        $status2 = 'complete_not_acknowledged';
                    } else {
                        $status2 = $row_status;
                    }
                ?>
                <tr data-status="<?= $row_status ?>" data-status2="<?= $status2 ?>" data-employee-type="<?= $duty_full_name ?>">
                    <td><?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?></td>
                    <td><?= htmlspecialchars($duty_full_name) ?></td>
                    <td><?= htmlspecialchars($row['score']) ?></td>
                    <td><?= htmlspecialchars($row['results']) ?></td>
                    <td><?= htmlspecialchars($row['round']) ?></td>
                    <td><?= htmlspecialchars($row['ests_year']) ?></td>
                    <td class="align-middle text-center" style="width:25%">
                        <div class="d-flex justify-content-center mt-3">
                            <?php 
                                if ($dean_status == 'complete' && $row_status == 'complete') { ?>
                                    <label class="btn btn-primary me-2 w-50">รับทราบแล้ว</label>
                                <?php } else { 
                                    if ($row_status == 'waitcomplete') { ?>
                                        <label class="btn btn-warning me-2 w-50">รอผู้ถูกประเมินยืนยัน</label>
                                    <?php } else if ($row_status == 'complete') { ?>
                                        <label class="btn btn-outline-primary me-2 w-50">ผู้ถูกประเมินยืนยันแล้ว</label>
                                        <button id="btn-<?= htmlspecialchars($row['id']) ?>" class="btn btn-success w-25 aware-btn">กดเพื่อรับทราบ</button>
                                    <?php } else if ($row_status == 'appeal') { ?>
                                        <label class="btn btn-danger me-2 w-50">ผู้ถูกประเมินขอยื่นอุธรธ์</label>
                                    <?php } 
                                } ?>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('filter').addEventListener('change', function() {
        var selectedStatus = this.value;
        var rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(function(row) {
            if (selectedStatus === 'all') {
                row.style.display = '';
            } else {
                var status = row.getAttribute('data-employee-type');
                if (status === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });    

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.aware-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                var id = this.id.split('-')[1];
                Swal.fire({
                    title: 'ยืนยันการอนุมัติ?',
                    text: "คุณแน่ใจหรือว่าต้องการอนุมัติพนักงานคนนี้?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, อนุมัติเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'fn/fn_complete.php?id=' + id;
                    }
                });
            });
        });

        document.getElementById('awareAll').addEventListener('click', function() {
            Swal.fire({
                title: 'ยืนยันการอนุมัติทั้งหมด?',
                text: "คุณแน่ใจหรือว่าต้องการอนุมัติพนักงานทั้งหมด?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, อนุมัติเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'fn/fn_complete_all.php';
                }
            });
        });
    });

    function filterAssessments() {
        var selectedOption = document.getElementById('statusSelect').value;
        var rows = document.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            if (selectedOption === 'all') {
                row.style.display = '';
            } else if (selectedOption === 'complete_not_acknowledged') {
                if (row.getAttribute('data-status') === 'complete' && row.getAttribute('data-status2') === 'complete_not_acknowledged') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            } else if (selectedOption === 'not_acknowledged') {
                if (row.getAttribute('data-status2') === 'not_acknowledged') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            } else {
                if (row.getAttribute('data-status') === selectedOption) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }
</script>

</body>
</html>
