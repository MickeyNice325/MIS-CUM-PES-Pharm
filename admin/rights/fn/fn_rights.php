<?php
include("../../../fn/config.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$response = ['status' => 'error', 'message' => 'Invalid data'];

if (isset($data['selectedStaff']) && isset($data['unselectedStaff']) && isset($data['selectedStaff2']) && isset($data['unselectedStaff2']) && isset($data['com_code'])) {
    $com_code = $data['com_code'];

    $conn->autocommit(FALSE);

    try {
        // มอบสิทธิ์สำหรับกรรมการ
        foreach ($data['selectedStaff'] as $staff) {
            $staff_code = $staff['staffCode'];
            $sql_check = "SELECT * FROM committee WHERE com_code = ? AND staff_code = ?";
            $stmt_check = mysqli_prepare($conn, $sql_check);
            if (!$stmt_check) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_check, 'ss', $com_code, $staff_code);
            if (!mysqli_stmt_execute($stmt_check)) {
                throw new Exception('Execute failed: ' . mysqli_error($conn));
            }
            $result_check = mysqli_stmt_get_result($stmt_check);

            if (mysqli_num_rows($result_check) == 0) {
                $sql_insert = "INSERT INTO committee (com_code, staff_code, staff_name, dep_name, unit_name, status) VALUES (?, ?, ?, ?, ?, 'committee')";
                $stmt_insert = mysqli_prepare($conn, $sql_insert);
                if (!$stmt_insert) {
                    throw new Exception('Prepare failed: ' . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt_insert, 'sssss', $com_code, $staff_code, $staff['staffName'], $staff['department'], $staff['unit']);
                if (!mysqli_stmt_execute($stmt_insert)) {
                    throw new Exception('Execute failed: ' . mysqli_error($conn));
                }
            } else {
                $sql_update = "UPDATE committee SET status = 'committee' WHERE com_code = ? AND staff_code = ?";
                $stmt_update = mysqli_prepare($conn, $sql_update);
                if (!$stmt_update) {
                    throw new Exception('Prepare failed: ' . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt_update, 'ss', $com_code, $staff_code);
                if (!mysqli_stmt_execute($stmt_update)) {
                    throw new Exception('Execute failed: ' . mysqli_error($conn));
                }
            }
        }

        // มอบสิทธิ์สำหรับหัวหน้า
        foreach ($data['selectedStaff2'] as $staff) {
            $staff_code = $staff['staffCode'];
            $sql_check = "SELECT * FROM committee WHERE com_code = ? AND staff_code = ?";
            $stmt_check = mysqli_prepare($conn, $sql_check);
            if (!$stmt_check) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_check, 'ss', $com_code, $staff_code);
            if (!mysqli_stmt_execute($stmt_check)) {
                throw new Exception('Execute failed: ' . mysqli_error($conn));
            }
            $result_check = mysqli_stmt_get_result($stmt_check);

            if (mysqli_num_rows($result_check) == 0) {
                $sql_insert = "INSERT INTO committee (com_code, staff_code, staff_name, dep_name, unit_name, status) VALUES (?, ?, ?, ?, ?, 'supervisor')";
                $stmt_insert = mysqli_prepare($conn, $sql_insert);
                if (!$stmt_insert) {
                    throw new Exception('Prepare failed: ' . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt_insert, 'sssss', $com_code, $staff_code, $staff['staffName'], $staff['department'], $staff['unit']);
                if (!mysqli_stmt_execute($stmt_insert)) {
                    throw new Exception('Execute failed: ' . mysqli_error($conn));
                }
            } else {
                $sql_update = "UPDATE committee SET status = 'supervisor' WHERE com_code = ? AND staff_code = ?";
                $stmt_update = mysqli_prepare($conn, $sql_update);
                if (!$stmt_update) {
                    throw new Exception('Prepare failed: ' . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt_update, 'ss', $com_code, $staff_code);
                if (!mysqli_stmt_execute($stmt_update)) {
                    throw new Exception('Execute failed: ' . mysqli_error($conn));
                }
            }
        }

        // ถอนสิทธิ์
        foreach ($data['unselectedStaff'] as $staff) {
            $staff_code = $staff['staffCode'];
            $sql_delete = "DELETE FROM committee WHERE com_code = ? AND staff_code = ? AND status = 'committee'";
            $stmt_delete = mysqli_prepare($conn, $sql_delete);
            if (!$stmt_delete) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_delete, 'ss', $com_code, $staff_code);
            if (!mysqli_stmt_execute($stmt_delete)) {
                throw new Exception('Execute failed: ' . mysqli_error($conn));
            }
        }

        // ถอนสิทธิ์สำหรับหัวหน้า
        foreach ($data['unselectedStaff2'] as $staff) {
            $staff_code = $staff['staffCode'];
            $sql_delete = "DELETE FROM committee WHERE com_code = ? AND staff_code = ? AND status = 'supervisor'";
            $stmt_delete = mysqli_prepare($conn, $sql_delete);
            if (!$stmt_delete) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_delete, 'ss', $com_code, $staff_code);
            if (!mysqli_stmt_execute($stmt_delete)) {
                throw new Exception('Execute failed: ' . mysqli_error($conn));
            }
        }

        $conn->commit();
        $response = ['status' => 'success'];
    } catch (Exception $e) {
        $conn->rollback();
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Invalid data'];
}

echo json_encode($response);
?>
