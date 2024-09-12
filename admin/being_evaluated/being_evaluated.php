    <?php 
    include("nav.php");
    include("../../fn/config.php");
    include("../../fn/session.php");

    if (!isset($_SESSION['code'])) {
        header("Location: ../../login.php");
        exit();
    }

    $code = $_SESSION['code'];
    $search = isset($_POST['search']) ? $_POST['search'] : "";
    $selected_year = isset($_POST['year']) ? $_POST['year'] : date("Y") + 543;

    $query = "SELECT estimate_score.*, staff.fname, staff.lname, staff.photo, staff.dep_code, staff.job_code, staff.unit_code, staff.position_code, duty.name_sort AS duty_name 
            FROM estimate_score 
            LEFT JOIN staff ON estimate_score.staff_code = staff.code
            LEFT JOIN duty ON estimate_score.duty_code = duty.code
            WHERE (staff.fname LIKE '%$search%' OR staff.lname LIKE '%$search%') 
            AND ests_year = '$selected_year' 
            ORDER BY staff.code";


    $result = mysqli_query($conn, $query);

    // Fetch distinct years
    $yearQuery = "SELECT DISTINCT ests_year FROM estimate_score ORDER BY ests_year DESC";
    $yearResult = mysqli_query($conn, $yearQuery);

    // Check if any years are returned
    if (!$yearResult) {
        die("Database query failed: " . mysqli_error($conn));
    }

    $years = [];
    while ($row = mysqli_fetch_assoc($yearResult)) {
        $years[] = $row['ests_year'];
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ผู้ที่ได้รับการประเมิน</title>
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
            .divbg {
                background-color: #F4FFE3;
            }
            .staff-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }
            .staff-col {
                width: calc(33.333% - 20px); /* Adjust width and margin as needed */
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <form id="uploadForm" action="import_excel.php" method="post" enctype="multipart/form-data" class="mt-5 container">
        <div class="col">
            <div class="alert alert-warning">
                <strong>หมายเหตุ:</strong> ต้องตั้งชื่อไฟล์ให้มีรูปแบบดังนี้: ปี (4 ตัวแรก) และรอบที่ (ตัวหลังสุด) เช่น 2567 (1 ต.ค.66-31 มี.ค.67) ครั้งที่ 1
            </div>
        </div>
            <h6>อัปโหลดลงฐานข้อมูล || .xlsx .xls</h6>
            <div class="input-group w-25">
                <input type="file" name="file" id="file" accept=".xlsx, .xls" class="form-control" aria-label="Upload">
                <button type="submit" class="btn btn-outline-secondary">อัปโหลด</button>
            </div>
        </form>


        

        <form class="mt-5 container">
            <h6>พิมพ์ไฟล์ PDF</h6>
            <a href="pdf/official.php?year=<?= urlencode($selected_year) ?>" class="btn btn-outline-success">ข้าราชการ</a>
            <a href="pdf/Temporary_employee.php?year=<?= urlencode($selected_year) ?>" class="btn btn-outline-success">พนักงานชั่วคราว</a>
            <a href="pdf/employee.php?year=<?= urlencode($selected_year) ?>" class="btn btn-outline-success">พนักงานมหาวิทยาลัย</a>
            <a href="pdf/employeepajum.php?year=<?= urlencode($selected_year) ?>" class="btn btn-outline-success">พนักงานประจำ</a>

        </form>

        <div class="container staff-container">
        <form class="mt-5 container" method="POST">
            <div class="d-flex mb-3">
                <div class="me-2">
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        <option value="">เลือกปี</option> <!-- Default option -->
                        <?php
                        foreach ($years as $year) {
                            $selected = ($year == $selected_year) ? 'selected' : '';
                            echo "<option value='$year' $selected>$year</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="me-2">
                    <input type="text" class="form-control" placeholder="ค้นหาด้วยชื่อพนักงาน" name="search" value="<?= htmlspecialchars($search) ?>">
                </div>
                <button type="submit" class="btn btn-outline-success">ค้นหา</button>
            </div>
                    <div class="col-md-2">
                        <select id="statusSelect" class="form-select" onchange="filterAssessments()">
                            <option value="all">ทั้งหมด</option>
                            <option value="wait">ยังไม่ได้อนุมัติ</option>
                            <option value="waitcomplete">รอผู้ถูกประเมินยืนยัน</option>
                            <option value="complete">ผู้ถูกประเมินยืนยันแล้ว</option>
                            <option value="appeal">ผู้ถูกประเมินขอยื่นอุธรธ์</option>
                        </select>
                    </div>
        </form>

        <div class="container mt-5 mb-5 shadow divbg">
                <table class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th style="width:10%">รหัสพนักงาน</th>
                            <th style="width:20%">ชื่อ - สกุล</th>
                            <th style="width:5%">รอบที่</th>
                            <th style="width:5%">ปี</th>
                            <th style="width:10%">คะแนน</th>
                            <th style="width:10%">ผลการประเมิน</th>
                            <th style="width:10%">สถานะคณบดี</th>
                            <th style="width:40%"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { 
                        $duty_name = is_null($row['duty_name']) ? "N/A" : htmlspecialchars($row['duty_name']);
                        ?>
                        <tr data-status="<?= htmlspecialchars($row['status']) ?>">
                            <td><?= htmlspecialchars($row['staff_code']) ?></td>
                            <td><?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?></td>
                            <td><?= htmlspecialchars($row['round']) ?></td>
                            <td><?= htmlspecialchars($row['ests_year']) ?></td>
                            <td><?= htmlspecialchars($row['score']) ?></td>
                            <td><?= htmlspecialchars($row['results']) ?></td>
                            <td>
                                <?php if (htmlspecialchars($row['dean_status']) == 'complete') { ?>
                                    ทราบแล้ว
                                <?php } else { ?>
                                    ยังไม่ทราบ
                                <?php } ?>
                            </td>
                            <td class="align-middle text-center" style="width:100%">
                                <div class="d-flex justify-content-center mt-3">
                                    <?php if (htmlspecialchars($row['status']) == 'waitcomplete') { ?>
                                        <label class="btn btn-warning me-2 w-50">รอผู้ถูกประเมินยืนยัน</label>
                                    <?php } else if (htmlspecialchars($row['status']) == 'complete') { ?>
                                        <label class="btn btn-outline-primary me-2 w-50">ผู้ถูกประเมินยืนยันแล้ว</label>
                                    <?php } else if (htmlspecialchars($row['status']) == 'appeal') { ?>
                                        <label class="btn btn-outline-danger me-2 w-50">ผู้ถูกประเมินขอยื่นอุธรธ์</label>
                                    <?php } else if (htmlspecialchars($row['status']) == 'wait') { ?>
                                        <a href="#" id="approve-<?= htmlspecialchars($row['id']) ?>" class="btn btn-outline-success me-2 w-25 approve-btn">อนุมัติ</a>
                                        <a href="#" id="deny-<?= htmlspecialchars($row['id']) ?>" class="btn btn-outline-danger w-25 deny-btn">ปฏิเสธ</a>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

    </div>

        
        
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

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

        document.querySelectorAll('.approve-btn').forEach(function(button) {
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

        document.querySelectorAll('.deny-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); 
                var id = this.id.split('-')[1]; 

                Swal.fire({
                    title: 'ยืนยันการปฏิเสธ?',
                    text: "คุณแน่ใจหรือว่าต้องการปฏิเสธพนักงานคนนี้?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ปฏิเสธเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'fn/fn_cancel.php?id=' + id;
                    }
                });
            });
        });

        function filterAssessments() {
        var selectedOption = document.getElementById('statusSelect').value;
        var rows = document.querySelectorAll('tbody tr'); // Select all table rows

        rows.forEach(function(row) {
            if (selectedOption === 'all') {
                row.style.display = '';
            } else if (row.getAttribute('data-status') === selectedOption) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    </script>
    </body>
    </html>
