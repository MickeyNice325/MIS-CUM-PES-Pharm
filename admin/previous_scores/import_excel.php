<?php
require '../../vendor/autoload.php';
include('../../fn/config.php');
use PhpOffice\PhpSpreadsheet\IOFactory;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ['status' => 'error', 'message' => 'ไม่มีการอัปโหลดไฟล์'];

if (isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $filename = $_FILES['file']['name'];
    $year = substr($filename, 0, 4);
    $round = substr($filename, -6); 

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray(null, true, true, true);

    if ($conn->connect_error) {
        $response['message'] = "การเชื่อมต่อล้มเหลว: " . $conn->connect_error;
        echo json_encode($response);
        exit();
    }

    $stmt_check_staff = $conn->prepare("SELECT duty_code FROM staff WHERE code = ?");
    $stmt_duplicate_check = $conn->prepare("SELECT * FROM estimate_score WHERE staff_code = ? AND duty_code = ? AND ests_year = ? AND round = ?");
    $stmt_insert = $conn->prepare("INSERT INTO estimate_score (staff_code, duty_code, performance, behavior, score, results, status, ests_year, round, dean_status) VALUES (?, ?, ?, ?, ?, ?, 'complete', ?, ?, 'complete')");
    $stmt_update = $conn->prepare("UPDATE estimate_score SET performance = ?, behavior = ?, score = ?, results = ? WHERE staff_code = ? AND duty_code = ? AND ests_year = ? AND round = ?");

    foreach ($data as $row) {
        // Mapping Excel columns to variables
        $staff_code = $row['B']; 
        $performance = $row['D']; 
        $behavior = $row['E'];
        $score = $row['F']; 
        $results = $row['G'];

        $stmt_check_staff->bind_param("s", $staff_code);
        $stmt_check_staff->execute();
        $result = $stmt_check_staff->get_result();

        if ($result->num_rows > 0) {
            $staff_row = $result->fetch_assoc();
            $duty_code = $staff_row['duty_code'];

            $stmt_duplicate_check->bind_param("ssss", $staff_code, $duty_code, $year, $round);
            $stmt_duplicate_check->execute();
            $dup_result = $stmt_duplicate_check->get_result();

            if ($dup_result->num_rows == 0) {
                $stmt_insert->bind_param("ssssssii", $staff_code, $duty_code, $performance, $behavior, $score, $results, $year, $round);

                if ($stmt_insert->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'อัพโหลดไฟล์เสร็จแล้ว';
                } else {
                    $response['message'] = "Error in estimate_score: " . $stmt_insert->error . " for staff code: " . $staff_code;
                    echo json_encode($response);
                    exit();
                }
            } else {
                $stmt_update->bind_param("sssssssi", $performance, $behavior, $score, $results, $staff_code, $duty_code, $year, $round);

                if ($stmt_update->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'อัพโหลดไฟล์เสร็จแล้ว';
                } else {
                    $response['message'] = "เกิดข้อผิดพลาด " . $stmt_update->error . " for staff code: " . $staff_code;
                    echo json_encode($response);
                    exit();
                }
            }
        } else {
            continue;
        }
    }

    $stmt_check_staff->close();
    $stmt_duplicate_check->close();
    $stmt_insert->close();
    $stmt_update->close();
    $conn->close();

    echo json_encode($response);
    exit();
}

echo json_encode($response);
?>
