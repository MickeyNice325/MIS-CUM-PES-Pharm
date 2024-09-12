<?php 
include("../../fn/config.php");
include("../../fn/session.php");
include("nav_for_list.php");

// Get parameters from the request
$duty_code = isset($_GET['duty_code']) ? $_GET['duty_code'] : '';
$round = isset($_GET['round']) ? $_GET['round'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Query to fetch individual staff scores
$sql_individual_scores = "
    SELECT 
        s.fname, s.lname, es.score
    FROM estimate_score es
    JOIN staff s ON es.staff_code = s.code
    WHERE es.duty_code IN ($duty_code) -- รองรับหลายค่า
    AND es.round = " . ($round === 'round1' ? 1 : 2) . "
    AND es.ests_year = " . (isset($_GET['year']) ? $_GET['year'] : date('Y'));

$result_individual_scores = $conn->query($sql_individual_scores);

$individual_scores = [];
if ($result_individual_scores->num_rows > 0) {
    while ($row = $result_individual_scores->fetch_assoc()) {
        $individual_scores[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคะแนน</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif !important;
        }
    </style>
</head>
<body>
    <div class="container">
    <?php
            $sql_namelist = "SELECT es.staff_code, s.fname, s.lname, es.score 
                FROM estimate_score es
                JOIN staff s ON es.staff_code = s.code
                WHERE es.status = 'complete' 
                AND es.dean_status = 'complete' 
                AND es.ests_year = '$year' 
                AND es.duty_code IN ($duty_code)
                AND round = '$round'";
            $result_namelist = $conn->query($sql_namelist);
        ?>
        <div class="mt-5">
            <div class="row mb-2 align-items-center">
                <div class="col">
                    <h2>รายชื่อและผลคะแนนของผู้ถูกประเมิน</h2>
                </div>
                <div class="col-auto">
                    <a href="pdf/list_for_committee.php?duty_code=<?php echo htmlspecialchars($duty_code); ?>&round=<?php echo htmlspecialchars($round); ?>&year=<?php echo htmlspecialchars($year); ?>&type=<?php echo htmlspecialchars($type); ?>" class="btn btn-outline-success mt-1 mb-1 ">PDF เสนอคณะกรรมการ</a>
                </div>
                <div class="col-auto">
                    <a href="pdf/list_for_dean.php?duty_code=<?php echo htmlspecialchars($duty_code); ?>&round=<?php echo htmlspecialchars($round); ?>&year=<?php echo htmlspecialchars($year); ?>&type=<?php echo htmlspecialchars($type); ?>" class="btn btn-outline-success mt-1 mb-1">PDF เสนอคณบดี</a>
                </div>
            </div>
            <div>
                <table class="table table-light text-center table-striped">
                    <thead>
                        <tr>
                            <th style="width:50%">รายชื่อบุคลากร</th>
                            <th style="width:50%">คะแนนที่ได้</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result_namelist->num_rows > 0) {
                        while ($row = $result_namelist->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['fname'] . " " . $row['lname'] ."</td>";
                            echo "<td>" . $row['score'] . "</td>";
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
</body>
</html>
