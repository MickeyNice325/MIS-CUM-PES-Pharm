<?php
include("nav.php");
include("../../fn/config.php");
include("../../fn/session.php");

if (!isset($_SESSION['code'])) {
    header("Location: ../../login.php");
    exit();
}
$code = $_SESSION['code'];

$records_per_page = PHP_INT_MAX;    

$status_filter = "";
if (isset($_GET['status'])) {
    $status_filter = mysqli_real_escape_string($conn, $_GET['status']);
}

if ($status_filter && $status_filter !== 'all') {
    $sql_count .= " AND job_code IN (SELECT code FROM job WHERE name = '$status_filter')";
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$search2 = isset($_GET['search2']) ? mysqli_real_escape_string($conn, $_GET['search2']) : "";

$sql_total_records = "SELECT COUNT(*) AS total FROM staff WHERE level != 'admin' AND (fname LIKE CONCAT('%', ?, '%') OR lname LIKE CONCAT('%', ?, '%'))";
$stmt_total_records = mysqli_prepare($conn, $sql_total_records);
mysqli_stmt_bind_param($stmt_total_records, 'ss', $search, $search);
mysqli_stmt_execute($stmt_total_records);
$query_total_records = mysqli_stmt_get_result($stmt_total_records);
$total_records = mysqli_fetch_assoc($query_total_records)['total'];


$sql_staff = "SELECT * FROM staff WHERE level != 'admin' AND (fname LIKE CONCAT('%', ?, '%') OR lname LIKE CONCAT('%', ?, '%')) LIMIT ? OFFSET ?";
$stmt_staff = mysqli_prepare($conn, $sql_staff);
mysqli_stmt_bind_param($stmt_staff, 'ssii', $search, $search, $records_per_page, $offset);
mysqli_stmt_execute($stmt_staff);
$query_staff = mysqli_stmt_get_result($stmt_staff);


function fetch_name_by_code($conn, $table, $code) {
    $sql = "SELECT name FROM $table WHERE code = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $code);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['name'];
    }
    return 'N/A';
}


$selected_job_code = isset($_GET['job_code']) ? $_GET['job_code'] : ''; // Get the selected job code from URL parameters
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <div class="container mt-5">
    <form class="container col" style="width:25%" method="get" action="">
        <div class="input-group">
            <input type="text" name="search" id="search" class="form-control" placeholder="กรอกชื่อพนักงาน" value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-success" type="submit">ค้นหา</button>
        </div>

    </form>
    <div class="container mb-3">
            <label for="filter">ประเภทงาน:</label>
            <select id="filter" class="form-select" style="width: 25%;">
            <?php 
                $sql_job = "SELECT * FROM `job` ";
                $result = $conn->query($sql_job);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                       
                        $job_name = $row['name'];
                        echo "<option value='$job_name'>$job_name</option>";
                    }
                } else {
                    echo "<option value=''>No jobs available</option>";
                }
                ?>
                
            </select>
        </div>
    </div>
    <div class="container bg-light mt-4">
        <h4 class="mb-3">รายชื่อทั้งหมด</h4>
        <table class="table table-striped table-hover">
            <thead class="text-center">
                <tr>
                    <th style="width:15%">รหัสพนักงาน</th>
                    <th style="width:20%">ชื่อ-สกุล</th>
                    <th style="width:15%">สังกัด</th>
                    <th style="width:15%">งาน</th>
                    <th style="width:15%">หน่วยงาน</th>
                    <th style="width:20%">ดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="text-center">
            <?php while ($row_staff = mysqli_fetch_assoc($query_staff)) {
                $dep_name = fetch_name_by_code($conn, 'department', $row_staff['dep_code']);
                $job_name = fetch_name_by_code($conn, 'job', $row_staff['job_code']);
                $unit_name = !empty($row_staff['unit_code']) ? fetch_name_by_code($conn, 'unit', $row_staff['unit_code']) : 'N/A';
            ?>
                <tr data-status="<?= htmlspecialchars($job_name) ?>">
                    <td><?= htmlspecialchars($row_staff['code']) ?></td>
                    <td><?= htmlspecialchars($row_staff['fname']) ?> <?= htmlspecialchars($row_staff['lname']) ?></td>
                    <td><?= htmlspecialchars($dep_name) ?></td>
                    <td><?= htmlspecialchars($job_name) ?></td>
                    <td><?= htmlspecialchars($unit_name) ?></td>
                    <td>
                        <button class="btn btn-warning grant-permission-btn" data-code="<?= htmlspecialchars($row_staff['code']) ?>" data-fname="<?= htmlspecialchars($row_staff['fname']) ?>" data-lname="<?= htmlspecialchars($row_staff['lname']) ?>">กำหนดสิทธิ์</button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="grantPermissionModal" tabindex="-1" aria-labelledby="grantPermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="grantPermissionModalLabel">กำหนดสิทธิ์การประเมิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="searchForm" class="container" style="width:50%" method="get" action="">
                    <div class="input-group">
                        <input type="text" name="search2" id="search2" class="form-control" placeholder="กรอกชื่อพนักงาน">
                        <button class="btn btn-outline-success" type="submit">ค้นหา</button>
                    </div>
                </form>
                    <form id="grantPermissionForm" method="post">
                        <input type="hidden" class="form-control" id="comCode" name="com_code">
                        <table class="table table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th scope="col">กรรมการ</th>
                                    <th scope="col">สิทธิ์การประเมิน</th>
                                    <th scope="col">รหัสพนักงาน</th>
                                    <th scope="col">ชื่อ-สกุล</th>
                                    <th scope="col">สังกัด</th>
                                    <th scope="col">หน่วยงาน</th>
                                </tr>
                            </thead>
                            <tbody id="evaluationTableBody" class="text-center">
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="button" id="saveButton" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const grantPermissionBtns = document.querySelectorAll('.grant-permission-btn');
    const grantPermissionModal = new bootstrap.Modal(document.getElementById('grantPermissionModal'));
    const searchForm = document.getElementById('searchForm');
    let selectedStaffList = {};
    let selectedSupervisorList = {};

    grantPermissionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const comCode = btn.getAttribute('data-code');
            document.getElementById('comCode').value = comCode;

            loadStaffData();
            grantPermissionModal.show();
        });
    });

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        saveCheckboxStates();
        loadStaffData();
    });

    function saveCheckboxStates() {
        document.querySelectorAll('input[name="selectedStaff[]"]').forEach(cb => {
            selectedStaffList[cb.value] = {
                checked: cb.checked,
                fname: cb.dataset.fname,
                lname: cb.dataset.lname,
                dep: cb.dataset.dep,
                unit: cb.dataset.unit
            };
        });
        document.querySelectorAll('input[name="selectedStaff2[]"]').forEach(cb => {
            selectedSupervisorList[cb.value] = {
                checked: cb.checked,
                fname: cb.dataset.fname,
                lname: cb.dataset.lname,
                dep: cb.dataset.dep,
                unit: cb.dataset.unit
            };
        });
    }

    function restoreCheckboxStates() {
        document.querySelectorAll('input[name="selectedStaff[]"]').forEach(cb => {
            if (selectedStaffList.hasOwnProperty(cb.value)) {
                cb.checked = selectedStaffList[cb.value].checked;
                cb.dataset.fname = selectedStaffList[cb.value].fname;
                cb.dataset.lname = selectedStaffList[cb.value].lname;
                cb.dataset.dep = selectedStaffList[cb.value].dep;
                cb.dataset.unit = selectedStaffList[cb.value].unit;
            }
        });
        document.querySelectorAll('input[name="selectedStaff2[]"]').forEach(cb => {
            if (selectedSupervisorList.hasOwnProperty(cb.value)) {
                cb.checked = selectedSupervisorList[cb.value].checked;
                cb.dataset.fname = selectedSupervisorList[cb.value].fname;
                cb.dataset.lname = selectedSupervisorList[cb.value].lname;
                cb.dataset.dep = selectedSupervisorList[cb.value].dep;
                cb.dataset.unit = selectedSupervisorList[cb.value].unit;
            }
        });
    }

    function loadStaffData() {
        const comCode = document.getElementById('comCode').value;
        const search2 = document.getElementById('search2').value;

        fetch(`get_all_staff.php?search2=${encodeURIComponent(search2)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(allStaffData => {
            fetch(`get_committee.php?com_code=${comCode}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(committeeData => {
                const evaluationTableBody = document.getElementById('evaluationTableBody');
                evaluationTableBody.innerHTML = ''; 

                allStaffData.forEach(staff => {
                    const isChecked = committeeData.some(member => member.staff_code === staff.code && member.status === 'committee');
                    const isSupervisor = committeeData.some(member => member.staff_code === staff.code && member.status === 'supervisor');
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><input type="checkbox" class="form-check-input" name="selectedStaff[]" value="${staff.code}" data-fname="${staff.fname}" data-lname="${staff.lname}" data-dep="${staff.dep_name}" data-unit="${staff.unit_name}" ${isChecked || (selectedStaffList[staff.code] && selectedStaffList[staff.code].checked) ? 'checked' : ''}></td>
                        <td><input type="checkbox" class="form-check-input supervisor-checkbox" name="selectedStaff2[]" value="${staff.code}" data-fname="${staff.fname}" data-lname="${staff.lname}" data-dep="${staff.dep_name}" data-unit="${staff.unit_name}" ${isSupervisor || (selectedSupervisorList[staff.code] && selectedSupervisorList[staff.code].checked) ? 'checked' : ''}></td>
                        <td>${staff.code}</td>
                        <td>${staff.fname} ${staff.lname}</td>
                        <td>${staff.dep_name}</td>
                        <td>${staff.unit_name}</td>
                    `;
                    evaluationTableBody.appendChild(row);
                });

                restoreCheckboxStates();
            })
            .catch(error => {
                console.error('Error fetching committee data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถดึงข้อมูลกรรมการได้',
                });
            });
        })
        .catch(error => {
            console.error('Error fetching staff data:', error);
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถดึงข้อมูลพนักงานได้',
            });
        });
    }

    document.getElementById('saveButton').addEventListener('click', function() {
        saveCheckboxStates();

        const selectedStaff = Object.keys(selectedStaffList).filter(code => selectedStaffList[code].checked).map(code => ({
            staffCode: code,
            staffName: `${selectedStaffList[code].fname} ${selectedStaffList[code].lname}`,
            department: selectedStaffList[code].dep,
            unit: selectedStaffList[code].unit
        }));

        const unselectedStaff = Object.keys(selectedStaffList).filter(code => !selectedStaffList[code].checked).map(code => ({
            staffCode: code,
            staffName: `${selectedStaffList[code].fname} ${selectedStaffList[code].lname}`,
            department: selectedStaffList[code].dep,
            unit: selectedStaffList[code].unit
        }));

        const selectedSupervisors = Object.keys(selectedSupervisorList).filter(code => selectedSupervisorList[code].checked).map(code => ({
            staffCode: code,
            staffName: `${selectedSupervisorList[code].fname} ${selectedSupervisorList[code].lname}`,
            department: selectedSupervisorList[code].dep,
            unit: selectedSupervisorList[code].unit
        }));

        const unselectedSupervisors = Object.keys(selectedSupervisorList).filter(code => !selectedSupervisorList[code].checked).map(code => ({
            staffCode: code,
            staffName: `${selectedSupervisorList[code].fname} ${selectedSupervisorList[code].lname}`,
            department: selectedSupervisorList[code].dep,
            unit: selectedSupervisorList[code].unit
        }));

        const comCode = document.getElementById('comCode').value;

        fetch('fn/fn_rights.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                selectedStaff: selectedStaff,
                unselectedStaff: unselectedStaff,
                com_code: comCode,
                selectedStaff2: selectedSupervisors,
                unselectedStaff2: unselectedSupervisors
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถบันทึกข้อมูลได้',
            });
            console.error('Error saving form:', error);
        });
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
    </script>
</body>
</html>
