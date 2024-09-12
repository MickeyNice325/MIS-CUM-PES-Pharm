<?php 
include("nav.php");
include("../fn/config.php");
include("../fn/session.php");

if (!isset($_SESSION['code'])) {
    header("Location: ../login.php");
    exit();
}

$code = $_SESSION['code'];

$sql_staff = "SELECT * FROM staff WHERE code = '$code'";
$query_staff = mysqli_query($conn, $sql_staff);
if (!$query_staff) {
    die('Error: ' . mysqli_error($conn));
}
$row_staff = mysqli_fetch_array($query_staff);

if (!$row_staff) {
    die('No staff data found for the given code.');
}

$dep_code = $row_staff['dep_code'];
$sql_dep = "SELECT name FROM department WHERE code = '$dep_code'";
$query_dep = mysqli_query($conn, $sql_dep);
if (!$query_dep) {
    die('Error: ' . mysqli_error($conn));
}
$row_dep = mysqli_fetch_array($query_dep);
$dep_name = $row_dep ? $row_dep['name'] : 'N/A';

$job_code = $row_staff['job_code'];
$sql_job = "SELECT name FROM job WHERE code = '$job_code'";
$query_job = mysqli_query($conn, $sql_job);
if (!$query_job) {
    die('Error: ' . mysqli_error($conn));
}
$row_job = mysqli_fetch_array($query_job);
$job_name = $row_job ? $row_job['name'] : 'N/A';

$unit_name = "N/A";
if ($row_staff['unit_code'] != null) {
    $unit_code = $row_staff['unit_code'];
    $sql_unit = "SELECT name FROM unit WHERE code = '$unit_code'";
    $query_unit = mysqli_query($conn, $sql_unit);
    if (!$query_unit) {
        die('Error: ' . mysqli_error($conn));
    }
    $row_unit = mysqli_fetch_array($query_unit);
    $unit_name = $row_unit ? $row_unit['name'] : 'N/A';
}

$duty_name = "N/A";
if ($row_staff['duty_code'] != null) {
    $duty_code = $row_staff['duty_code'];
    $sql_duty = "SELECT name FROM duty WHERE code = '$duty_code'";
    $query_duty = mysqli_query($conn, $sql_duty);
    if (!$query_duty) {
        die('Error: ' . mysqli_error($conn));
    }
    $row_duty = mysqli_fetch_array($query_duty);
    $duty_name = $row_duty ? $row_duty['name'] : 'N/A';
}

$position_name = "N/A";
if ($row_staff['position_code'] != null) {
    $position_code = $row_staff['position_code'];
    $sql_position = "SELECT name FROM position WHERE code = '$position_code'";
    $query_position = mysqli_query($conn, $sql_position);
    if (!$query_position) {
        die('Error: ' . mysqli_error($conn));
    }
    $row_position = mysqli_fetch_array($query_position);
    $position_name = $row_position ? $row_position['name'] : 'N/A';
}

$sql_supervisor = "SELECT staff_code FROM committee WHERE com_code = '$code' AND status ='supervisor'";
$query_supervisor = mysqli_query($conn, $sql_supervisor);
if (!$query_supervisor) {
    die('Error: ' . mysqli_error($conn));
}
$supervisor_staff_codes = [];
while ($row_supervisor = mysqli_fetch_assoc($query_supervisor)) {
    $supervisor_staff_codes[] = $row_supervisor['staff_code'];
}

$sql_committee = "SELECT staff_code FROM committee WHERE com_code = '$code' AND status ='committee'";
$query_committee = mysqli_query($conn, $sql_committee);
if (!$query_committee) {
    die('Error: ' . mysqli_error($conn));
}
$committee_staff_codes = [];
while ($row_committee = mysqli_fetch_assoc($query_committee)) {
    $committee_staff_codes[] = $row_committee['staff_code'];
}

$current_year = date("Y") + 543 ;
$years = [];
for ($i = 3; $i >= 1; $i--) {
    $years[] = $current_year - $i;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>พนักงาน</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: "Noto Sans Thai", sans-serif;
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5 shadow">
        <table class="table table-light">
            <tbody>
                <tr>
                    <td class="text-end p-0 me-2" style="width:30%">
                        <div class="container mt-4">
                            <h6>รหัสพนักงาน :</h6>
                            <h6>ชื่อ - สกุล :</h6>
                            <h6>สังกัด :</h6>
                            <h6>งาน :</h6>
                            <h6>หน่วยงาน :</h6>
                            <h6>ตำแหน่งงาน :</h6>
                        </div>
                    </td>
                    <td class="text-start p-0 ms-2" style="width:30%">
                        <div class="container mt-4">
                            <h6><?= htmlspecialchars($row_staff['code']) ?></h6>
                            <h6><?= htmlspecialchars($row_staff['fname']) ?> <?= htmlspecialchars($row_staff['lname']) ?></h6>
                            <h6><?= htmlspecialchars($dep_name) ?></h6>
                            <h6><?= htmlspecialchars($job_name) ?></h6>
                            <h6><?= htmlspecialchars($unit_name) ?></h6>
                            <h6><?= htmlspecialchars($position_name) ?></h6>

                        </div>
                    </td>
                    <td class="align-middle text-center" style="width:25%">
                        <img src="../img/user.png" alt="Avatar Logo" style="width:150px;" class="rounded-pill">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container mt-5 shadow">
        <h3>ผลการประเมินประจำปี</h3><hr>
        <?php 
        $sql_estimate = "SELECT * FROM estimate_score WHERE staff_code = '$code' ORDER BY ests_year DESC";
        $query_estimate = mysqli_query($conn, $sql_estimate);
        if (!$query_estimate) {
            die('Error: ' . mysqli_error($conn));
        }
        
        $data = [];
        while ($row_estimate = mysqli_fetch_assoc($query_estimate)) {
            $year = $row_estimate['ests_year'];
            $round = $row_estimate['round'];
            $data[$year][$round] = $row_estimate;
        }

        if (!empty($data)) {
            foreach ($data as $year => $rounds) {
                ?>
                <div class="container mt-3">
                    <table class="table table-striped table-hover mt-3">
                        <thead>
                            <tr>
                                <th class="text-center">ปี <?= htmlspecialchars($year) ?></th>
                                <th class="text-center">ผลงาน</th>
                                <th class="text-center">พฤติกรรม</th>
                                <th class="text-center">คะแนนที่ได้</th>
                                <th class="text-center">ผลการประเมิน</th>
                                <th class="text-center">การรับทราบผลการประเมิน</th>
                                <th class="text-center" style="width:20%">ดาวน์โหลดไฟล์</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($rounds as $round => $row_estimate) {
                                $id = $row_estimate['id'];
                                $score = $row_estimate['score'];
                                $performance = $row_estimate['performance'];
                                $behavior = $row_estimate['behavior'];
                                $results = $row_estimate['results'];
                                $status = $row_estimate['status'];
                                $dean_status = $row_estimate['dean_status'];
                                $duty_code_final_score = $row_estimate['duty_code'];
                                if ($status == 'waitcomplete') {
                                    $statusn = 'ยังไม่รับทราบผลการประเมิน';
                                } else if ($status == 'appeal') {
                                    $statusn = 'ทบทวนการประเมิน'; 
                                } else if ($status == 'complete') {
                                    $statusn = 'ได้รับทราบผลการประเมินแล้ว';
                                }
                                ?>
                                <tr>
                                    <td class="text-center">ครั้งที่ <?= htmlspecialchars($round) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($performance) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($behavior) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($score) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($results) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($statusn) ?></td>
                                    <td class="text-center">
                                        <?php if (htmlspecialchars($dean_status) == "complete") {
                                            if (htmlspecialchars($duty_code_final_score) <= '6' && htmlspecialchars($duty_code_final_score) >= '4') { ?>
                                                <a href="pdf/employee.php?id=<?= $id; ?>" class="btn btn-outline-success">ดาวน์โหลดไฟล์ PDF</a>
                                            <?php } elseif (htmlspecialchars($duty_code_final_score) == '7') { ?>
                                                <a href="pdf/employeepajum.php?id=<?= $id; ?>" class="btn btn-outline-success">ดาวน์โหลดไฟล์ PDF</a>
                                            <?php } elseif (htmlspecialchars($duty_code_final_score) <= '3') { ?>
                                                <a href="pdf/official.php?id=<?= $id; ?>" class="btn btn-outline-success">ดาวน์โหลดไฟล์ PDF</a>
                                            <?php } elseif (htmlspecialchars($duty_code_final_score) == '8') { ?>
                                                <a href="pdf/Temporary_employee.php?id=<?= $id; ?>" class="btn btn-outline-success">ดาวน์โหลดไฟล์ PDF</a>
                                            <?php }
                                        } else { ?>
                                            <span class="text-primary">รอคณบดีดำเนินการ</span>
                                        <?php } ?>
                                    </td>

                                </tr>
                                <?php if ($status == 'waitcomplete') { ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <button class="btn btn-outline-success w-25" onclick="acceptEvaluation(<?= htmlspecialchars($row_estimate['id']) ?>, '<?= htmlspecialchars($row_staff['code']) ?>')">ยอมรับ</button>
                                        <button class="btn btn-outline-danger w-25" onclick="appealEvaluation(<?= htmlspecialchars($row_estimate['id']) ?>, '<?= htmlspecialchars($row_staff['code']) ?>')">อุธรณ์</button>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <hr>
                </div>
                <?php
            }
        } else {
            echo "<div class='alert alert-warning' role='alert'>ไม่มีข้อมูลผลการประเมิน</div><hr>";
        }
        ?>
    </div>
    
    <script>
        function acceptEvaluation(id, staffCode) {
            Swal.fire({
                title: 'คุณแน่ใจหรือ?',
                text: "คุณต้องการยอมรับการประเมินนี้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ยอมรับ!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'fn/fn_accept.php';
                    form.innerHTML = `<input type="hidden" name="id" value="${id}">
                                      <input type="hidden" name="staff_code" value="${staffCode}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        }

        function appealEvaluation(id, staffCode) {
            Swal.fire({
                title: 'คุณแน่ใจหรือ?',
                text: "คุณต้องการอุธรณ์การประเมินนี้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, อุธรณ์!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'fn/fn_appeal.php';
                    form.innerHTML = `<input type="hidden" name="id" value="${id}">
                                      <input type="hidden" name="staff_code" value="${staffCode}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        }
    </script>
    
    <?php if (!empty($committee_staff_codes)) { ?>
    <div class="container mt-5 mb-5 bg-light shadow">
        <h3>ข้อมูลผู้รับการประเมิน</h3><hr>0
        <table class="table table-light text-center table-striped">
            <thead>
                <tr>
                    <th rowspan="2" style="width:15%">รหัสพนักงาน</th>
                    <th rowspan="2" style="width:20%">ชื่อ - สกุล</th>
                    <?php foreach ($years as $year) { ?>
                        <th colspan="2"><?= $year ?></th>
                    <?php } ?>
                    <th rowspan="2" style="width:15%">ระดับคะแนน</th>
                    <th rowspan="2" style="width:15%">ความเห็นจากผู้บังคับบัญชา</th>
                </tr>
                <tr>
                    <?php foreach ($years as $year) { ?>
                        <th style="width:5%">1</th>
                        <th style="width:5%">2</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $committee_staff_codes_str = implode("','", $committee_staff_codes);
                $sql_scores = "SELECT es.id, es.ests_year, es.staff_code, s.fname, s.lname, es.score, es.round
                               FROM estimate_score es
                               JOIN staff s ON es.staff_code = s.code
                               WHERE es.staff_code IN ('$committee_staff_codes_str') ORDER BY es.staff_code ,s.fname";
                $query_scores = mysqli_query($conn, $sql_scores);
                if (!$query_scores) {
                    die('Error: ' . mysqli_error($conn));
                }

                $data = [];
                while ($row_scores = mysqli_fetch_array($query_scores)) {
                    $staff_code = $row_scores['staff_code'];
                    if (!isset($data[$staff_code])) {
                        $data[$staff_code] = [
                            'staff_code' => $staff_code,
                            'fname' => $row_scores['fname'],
                            'lname' => $row_scores['lname'],
                        ];
                        foreach ($years as $year) {
                            $data[$staff_code][$year . '_1'] = '';
                            $data[$staff_code][$year . '_2'] = '';
                        }
                    }
                    $year = $row_scores['ests_year'];
                    $round = $row_scores['round'];
                    $data[$staff_code][$year . '_' . $round] = $row_scores['score'];
                }

                foreach ($data as $row) {
                    $staff_code = $row['staff_code'];
                    $sql_estimate = "SELECT est_lvl, est_opinion FROM estimate WHERE staff_code = '$staff_code' AND est_year = '$current_year' ";
                    $query_estimate = mysqli_query($conn, $sql_estimate);
                    $est_lvl = 'รอการประเมิน';
                    $est_opinion = 'รอการประเมิน';
                    if ($query_estimate) {
                        $row_estimate = mysqli_fetch_array($query_estimate);
                        if ($row_estimate) {
                            $est_lvl = $row_estimate['est_lvl'];
                            $est_opinion = $row_estimate['est_opinion'];
                        }
                    }
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['staff_code']) ?></td>
                    <td><?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?></td>
                    <?php foreach ($years as $year) { ?>
                        <td><?= htmlspecialchars($row[$year . '_1'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($row[$year . '_2'] ?: '-') ?></td>
                    <?php } ?>
                    <td><?= htmlspecialchars($est_lvl) ?></td>
                    <td><?= htmlspecialchars($est_opinion) ?></td>
                </tr>
                <?php 
                } ?>
            </tbody>
        </table><hr>
    </div>
    <?php } ?>
    
    <?php if (!empty($supervisor_staff_codes)) { 
        $supervisor_staff_codes_str = implode("','", $supervisor_staff_codes);
    ?>
    <div class="container mt-5 mb-5 bg-light shadow">
        <h3>ผลการประเมินจากผู้บังคับบัญชาชั้นต้น</h3><hr>
        <table class="table table-light text-center table-striped">
            <thead>
                <tr>
                    <th rowspan="2" style="width:10%">รหัสพนักงาน</th>
                    <th rowspan="2" style="width:15%">ชื่อ - สกุล</th>
                    <th colspan="6">ปี</th>
                    <th rowspan="2" style="width:10%">ระดับคะแนน</th>
                    <th rowspan="2" style="width:10%">ความเห็นจากผู้บังคับบัญชา</th>
                    <th rowspan="2">สถานะการประเมิน</th>
                </tr>
                <tr>
                    <?php foreach ($years as $year) { ?>
                        <th style="width:5%"><?= $year ?> <br>(1)</th>
                        <th style="width:5%"><?= $year ?> <br>(2)</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT es.id, es.ests_year, es.staff_code, s.fname, s.lname, es.score, es.round
                                        FROM estimate_score es
                                        JOIN staff s ON es.staff_code = s.code
                                        WHERE es.staff_code IN ('$supervisor_staff_codes_str')");
                $stmt->execute();
                $result = $stmt->get_result();
                $data = [];
                while ($row_scores = $result->fetch_assoc()) {
                    $staff_code = $row_scores['staff_code'];
                    if (!isset($data[$staff_code])) {
                        $data[$staff_code] = [
                            'staff_code' => $staff_code,
                            'fname' => $row_scores['fname'],
                            'lname' => $row_scores['lname'],
                        ];
                        foreach ($years as $year) {
                            $data[$staff_code][$year . '_1'] = '';
                            $data[$staff_code][$year . '_2'] = '';
                        }
                    }
                    $year = $row_scores['ests_year'];
                    $round = $row_scores['round'];
                    $data[$staff_code][$year . '_' . $round] = $row_scores['score'];
                }

                foreach ($data as $row) {
                    $staff_code = $row['staff_code'];
                    $stmt = $conn->prepare("SELECT * FROM estimate WHERE staff_code = ? AND assessor_code = ? ");
                    $stmt->bind_param("ss", $staff_code, $code);
                    $stmt->execute();
                    $row_estimate = $stmt->get_result()->fetch_assoc();
                    
                    if ($row_estimate) {
                        $est_lvl = $row_estimate['est_lvl'];
                        $est_opinion = $row_estimate['est_opinion'];
                        $status = $row_estimate['status'];
                        $id = $row_estimate['id'];
                    } else {
                        $est_lvl = 'รอดำเนินการ';
                        $est_opinion = 'รอดำเนินการ';
                        $status = 'รอดำเนินการ';
                    }
                    
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['staff_code']) ?></td>
                    <td><?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?></td>
                    <?php foreach ($years as $year) { ?>
                        <td><?= htmlspecialchars($row[$year . '_1'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($row[$year . '_2'] ?: '-') ?></td>
                    <?php } ?>
                    <td><?= htmlspecialchars($est_lvl) ?></td>
                    <td><?= htmlspecialchars($est_opinion) ?></td>
                    <td style="width:20%">
                        <?php if ($status == 'complete')  { ?>
                            <button type="button" class="btn btn-outline-success w-100" onclick="edit('fn/fn_edit.php?id=<?= $id; ?>')">ขอแก้ไขข้อมูลการประเมิน</button>
                        <?php }else if ($status == 'edit'){ ?>
                            <button type="button" class="btn btn-warning w-100" onclick="edit('fn/fn_edit.php?id=<?= $id; ?>')">รอยืนยันคำขอแก้ไข</button>
                        <?php }
                        else if ($status == 'waitsubmit') { ?>
                            <button type="button" class="btn btn-outline-warning w-100" onclick="openEvaluationModalButUpdate('<?= htmlspecialchars($row['staff_code']) ?>')">แก้ไข</button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-outline-success w-100" onclick="openEvaluationModal('<?= htmlspecialchars($row['staff_code']) ?>')">ประเมิน</button>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                
            </tbody>
        </table>
        <?php if ( $status == 'complete' || $status == 'edit'){}else{?>
        
        <div class="col col-lg-2 w-50">
            <button type="button" class="btn btn-outline-success w-25 mb-5" onclick="confirmAction('fn/fn_confirm.php?assessor_code=<?= htmlspecialchars($_SESSION['code']) ?>')">ยืนยันการส่งคะแนน</button>
            <button type="button" class="btn btn-outline-danger w-25 mb-5" onclick="cancelAction('fn/cancel.php?assessor_code=<?= htmlspecialchars($_SESSION['code']) ?>')">ยกเลิก</button>
        </div><?php } ?>
    </div>

    <div class="modal fade" id="evaluationModal" tabindex="-1" aria-labelledby="evaluationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="evaluationModalLabel">การประเมิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="evaluationForm" method="POST" action="fn/fn_submit_est.php">
                        <div class="mb-3">
                            
                            <label for="staffCode" class="form-label">ชื่อพนักงาน</label>
                            <input type="text" class="form-control" id="modalStaffname" name="staffCode" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="staffCode" class="form-label">รหัสพนักงาน</label>
                            <input type="text" class="form-control" id="modalStaffCode" name="staffCode" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="score" class="form-label">คะแนน</label>
                            <select class="form-select" id="modalScore" name="score">
                                <option value="A+">A+</option>
                                <option value="A">A</option>
                                <option value="B+">B+</option>
                                <option value="B">B</option>
                                <option value="C+">C+</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="opinion" class="form-label">ข้อคิดเห็น</label>
                            <textarea class="form-control" id="modalOpinion" name="opinion" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success float-end">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.18/dist/sweetalert2.all.min.js"></script>
    <script>
        function filterAssessments() {
            var selectedOption = $('#yearSelect').val();
            if (selectedOption === 'ประเมินแล้ว') {
                $('.table-striped tbody tr').hide().filter(function() {
                    return $(this).find('td:nth-child(11) button').text().trim() === 'ประเมินแล้ว';
                }).show();
            } else if (selectedOption === 'ยังไม่ได้ประเมิน') {
                $('.table-striped tbody tr').hide().filter(function() {
                    return $(this).find('td:nth-child(11) button').text().trim() === 'ยังไม่ได้ประเมิน';
                }).show();
            } else {
                $('.table-striped tbody tr').show();
            }
        }

        function openEvaluationModal(staffCode) {
            $('#modalStaffCode').val(staffCode);
            $('#modalOpinion').val('');
            $('#evaluationModal').modal('show');
            $('#modalStaffname').val("<?= htmlspecialchars($row['fname']) . ' ' . htmlspecialchars($row['lname']) ?>");

        }

        function openEvaluationModalButUpdate(staffCode) {
            $('#modalStaffCode').val(staffCode);
            $('#modalStaffname').val("<?= htmlspecialchars($row['fname']) . ' ' . htmlspecialchars($row['lname']) ?>"); 
            $.ajax({
                url: 'fetch_estimation_data.php',
                type: 'POST',
                data: { staffCode: staffCode },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#modalScore').val(data.score);
                    $('#modalOpinion').val(data.opinion);
                   
                }
            });
            $('#evaluationModal').modal('show');
        }

        $('#evaluationForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'fn/fn_submit_est.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถบันทึกข้อมูลได้',
                    });
                }
            });
        });

        function confirmAction(url) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "การกระทำนี้ไม่สามารถยกเลิกได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ยืนยัน!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        function edit(url) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการส่งคำขอแก้ไขการประเมินหรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ยกเลิก!',
            cancelButtonText: 'ไม่'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message,
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถลบข้อมูลได้',
                        });
                    }
                });
            }
        });
    }
        function cancelAction(url) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการยกเลิกการกระทำนี้?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ยกเลิก!',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
        function cancelAction(url) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการยกเลิกการกระทำนี้?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ยกเลิก!',
            cancelButtonText: 'ไม่'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message,
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถลบข้อมูลได้',
                        });
                    }
                });
            }
        });
    }
    function confirmAction(url) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "การกระทำนี้ไม่สามารถยกเลิกได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ยืนยัน!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message,
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถบันทึกข้อมูลได้',
                        });
                    }
                });
            }
        });
    }
    </script>
    </div>
    <?php } ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXlkoJtPGv7CsoLfoG2JjQ6g6JqS6YN2U6pKPKYpL0Lud5a3v49I1wAAYAx5" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cV6H1IFGOaOZ3AtS3kztTC1mtxWDb5sKZyU4U4qqvswBbgf6B22V3xG6uUTzTqt" crossorigin="anonymous"></script>
</body>
</html>
