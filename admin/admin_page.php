<?php 
include("nav.php");
include("../fn/config.php");
include("../fn/session.php");

// Check if session is set and user is logged in
if (!isset($_SESSION['code'])) {
    header("Location: ../login.php");
    exit();

    
}
$year = date('Y') + 543;
$code = $_SESSION['code'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/card.css">
    <style>
        body {
            font-family: "Noto Sans Thai", sans-serif;
        }
        .hover-box {
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease;
            background-color: #a8c43c;
            color: white;
            border-radius: 8px;
            height: 250px; /* กำหนดความสูงให้คงที่ */
        }
        .hover-box:hover {
            transform: scale(1.05);
        }
        .card {
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
            margin-bottom: 5px; /* ลด margin-bottom */
            background-color: #a8c43c;
            color: white;
            border: none;
            border-radius: 8px;
            height: 100%; /* ให้การ์ดมีความสูงเต็ม */
        }
        .card img {
            width: 50%;
            height: auto;
            transition: transform 0.3s ease;
        }
        .card:hover img {
            transform: scale(1.05);
        }
        .card p {
            margin-top: 10px;
        }
        .container {
            padding-left: 20px; /* เพิ่ม padding ซ้าย */
            padding-right: 20px; /* เพิ่ม padding ขวา */
        }
        .row > [class*="col-"] {
            padding-left: 10px;  /* ลด padding ระหว่างคอลัมน์ */
            padding-right: 10px; /* ลด padding ระหว่างคอลัมน์ */
            margin-bottom: 10px; /* ลด margin-bottom ของคอลัมน์ */
        }
    </style>
    <title>AdminPage</title>
</head>
<body>
    <div class="container mt-5 justify-content-center">
        <div class="row row-cols-1 row-cols-md-4 g-3 justify-content-center mb-4">
            <div class="col">
                <a href="previous_scores/previous_score.php" class="text-decoration-none">
                    <div class="card hover-box">
                        <img src="../img/pen.png" class="img-fluid mx-auto d-block" alt="Pen Icon">
                        <p class="mt-5">เพิ่มข้อมูลย้อนหลัง</p>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="appeal/appeal.php" class="text-decoration-none">
                    <div class="card hover-box">
                        <img src="../img/doc.png" class="img-fluid mx-auto d-block" alt="Doc Icon">
                        <p class="mt-5">ขอทบทวน<br>ผลการประเมิน</p>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="rights/rights.php" class="text-decoration-none">
                    <div class="card hover-box">
                        <img src="../img/mobsit.png" class="img-fluid mx-auto d-block" alt="Rights Icon">
                        <p class="mt-5">กำหนดสิทธิ์</p>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="list_edit_score/list_edit_score.php" class="text-decoration-none">
                    <div class="card hover-box">
                        <img src="../img/useredit.png" class="img-fluid mx-auto d-block" alt="Edit Icon">
                        <p class="mt-5">ตรวจสอบผลการ<br>ให้คะแนน</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center">
            <div class="col">
                <a href="being_evaluated/being_evaluated.php?year=<?= $year ?>" class="text-decoration-none">
                    <div class="card hover-box">
                        <img src="../img/usera.png" class="img-fluid mx-auto d-block" alt="Evaluated Icon">
                        <p class="mt-5" >ผู้ได้รับการประเมิน</p>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="check_committee/check_committee.php" class="text-decoration-none">
                    <div class="card hover-box">
                        <img src="../img/committee.png" class="img-fluid mx-auto d-block" alt="Evaluator Icon">
                        <p class="mt-5">ตรวจสอบรายชื่อกรรมการ</p>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="dashboard/dashboard.php?year=<?= $year ?>" class="text-decoration-none">
                    <div class="card hover-box">
                        <img src="../img/graph.png" class="img-fluid mx-auto d-block" alt="Salary Icon">
                        <p class="mt-5">Dashboard<br></p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
        <!-- Modal content here -->
        <div class="container mt-5 justify-content-center">
            <div class="row">
                <!-- Add your modal content here -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/c48bdc1c17.js" crossorigin="anonymous"></script>
</body>
</html>
