<?php 
include("nav.php");
include("../../fn/config.php");
include("../../fn/session.php");

if (!isset($_SESSION['code'])) {
    header("Location: ../../login.php");
    exit();
}
$code = $_SESSION['code'];

$limit = PHP_INT_MAX; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$start = ($page - 1) * $limit;

$search = "";
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
} elseif (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$status_filter = "";
if (isset($_GET['status'])) {
    $status_filter = mysqli_real_escape_string($conn, $_GET['status']);
}

$sql_count = "SELECT COUNT(*) AS total 
              FROM staff 
              WHERE level='employee' AND code LIKE '%$search%'";

if ($status_filter && $status_filter !== 'all') {
    $sql_count .= " AND duty_code IN (SELECT code FROM duty WHERE name = '$status_filter')";
}

$count_result = mysqli_query($conn, $sql_count);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $limit);

$sql = "SELECT e.ests_year, e.staff_code, 
               s.fname, s.lname, 
               CONCAT(s.fname, ' ', s.lname) AS name, 
               d.name_sort AS name_sort, e.score, e.round, e.id
        FROM estimate_score e
        JOIN staff s ON e.staff_code = s.code
        JOIN duty d ON s.duty_code = d.code
        WHERE CONCAT(s.fname, ' ', s.lname) LIKE '%$search%'";



$sql .= " ORDER BY s.code, e.ests_year, e.round LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn); // Check for SQL query error
    exit();
}

$counter = 1; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กรอกคะแนนย้อนหลัง</title>
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
        <div class="container2">
            <form class="container" style="width:25%" method="POST">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="ค้นหาด้วยชื่อพนักงาน" name="search" value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-outline-success">ค้นหา</button>
                </div>
            </form>
        </div>
        <form id="uploadForm" method="post" enctype="multipart/form-data">
        <div class="col">
            <h6>อัปโหลดไฟล์ลงฐานข้อมูล:</h6>
            <ul>
                <li>รองรับไฟล์ประเภท .xlsx และ .xls</li>
            </ul>
            <div class="alert alert-warning">
                <strong>หมายเหตุ:</strong> ต้องตั้งชื่อไฟล์ให้มีรูปแบบดังนี้: ปี (4 ตัวแรก) และรอบที่ (ตัวหลังสุด) เช่น 2565 (1 ต.ค.64-31 มี.ค.65) ครั้งที่ 1
            </div>
        </div>

            
            <div class="input-group w-25">
                <input type="file" name="file" id="file" accept=".xlsx, .xls" class="form-control" aria-label="Upload">
                <button type="submit" class="btn btn-outline-secondary">อัปโหลด</button>
            </div>
        </form>

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
        
        <form id="deleteSelectedForm" method="POST" action="fn/delete_selected_staff.php">
    <div class="container">
        <div class="d-flex flex-row-reverse bd-highlight">
            <button type="submit" class="btn btn-outline-danger mt-3 p-2 bd-highlight mb-1">ลบที่เลือก</button>
        </div>
        <table class="table table-light text-center table-striped">
            
            <thead>
                
                <tr>
                    <th>ลำดับ</th>
                    <th>ปี</th>
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อ - นามสกุล</th>
                    <th>ประเภทพนักงาน</th>
                    <th>คะแนน</th>
                    <th>รอบที่</th>
                    <th>จัดการ</th>
                    <th>เลือก</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr data-status='" . htmlspecialchars($row['name_sort']) . "'>";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['ests_year']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['staff_code']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['lname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name_sort']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['score']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['round']) . "</td>";
                        echo "<td><a type='button' class='btn btn-outline-danger delete-btn' data-id='" . htmlspecialchars($row['id']) . "' href='fn/delete_staff.php?id=" . htmlspecialchars($row['id']) . "'>ลบ</a></td>";
                        echo "<td><input type='checkbox' class='row-select' name='selected_ids[]' value='" . htmlspecialchars($row['id']) . "'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>ไม่พบข้อมูล</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
    </div>
</form>

        
    </div>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JavaScript -->
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function (event) {
            event.preventDefault(); 

            var formData = new FormData(this);

            fetch('import_excel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: data.message
                    }).then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อผิดพลาด',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการอัปโหลด'
                });
                console.error('Error:', error);
            });
        });

        document.getElementById('filter').addEventListener('change', function() {
            var selectedStatus = this.value;
            var rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(function(row) {
                if (selectedStatus === 'all') {
                    row.style.display = '';
                } else {
                    var status = row.getAttribute('data-status');
                    if (status === selectedStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });

        document.querySelectorAll('.delete-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                var deleteUrl = this.getAttribute('href');
                var staffId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการลบข้อมูลนี้จริงหรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ลบเลย!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
        document.getElementById('deleteSelectedForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var selectedCheckboxes = document.querySelectorAll('.row-select:checked');

    if (selectedCheckboxes.length > 0) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบข้อมูลที่เลือกหรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบเลย!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); 
            }
        });
    } else {
        Swal.fire({
            icon: 'info',
            title: 'ไม่พบข้อมูลที่เลือก',
            text: 'กรุณาเลือกข้อมูลที่ต้องการลบ'
        });
    }
});

    </script>
</body>
</html>
