<?php
include("../../fn/config.php");
include("../../fn/session.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['staff_code'])) {
    $staff_code = $_POST['staff_code'];

    if ($conn) {
        $sql = "UPDATE estimate_score SET status = 'appeal' WHERE staff_code = '$staff_code'";

        if (mysqli_query($conn, $sql)) {
            echo "
                <script>
                    window.location = '../employee.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . mysqli_error($conn) . "');
                    window.location = '../employee.php';
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('ไม่สามารถเชื่อมต่อกับฐานข้อมูลได้!');
                window.location = '../employee.php';
            </script>
        ";
    }
} else {
    echo "
        <script>
            alert('ไม่มีข้อมูลส่งมา!');
            window.location = '../employee.php';
        </script>
    ";
}
?>
