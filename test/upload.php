<?php 
include("../../fn/config.php");
include("../../fn/session.php");
include('nav.php');

// Get the selected year from the request, default to the current year if not set
$selected_year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Query to fetch evaluation data for the selected year
$sql = "
    SELECT 
        CASE 
            WHEN score BETWEEN 90 AND 100 THEN '90 - 100'
            WHEN score BETWEEN 80 AND 89 THEN '80 - 89'
            WHEN score BETWEEN 70 AND 79 THEN '70 - 79'
            WHEN score BETWEEN 60 AND 69 THEN '60 - 69'
            ELSE 'ต่ำกว่า 60'
        END AS evaluation_score,
        duty_code,
        round,
        COUNT(*) as count 
    FROM estimate_score 
    WHERE ests_year = $selected_year
    GROUP BY evaluation_score, duty_code, round";

$result = $conn->query($sql);

$data = [
    '1,2,3' => [
        'round1' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, 'ต่ำกว่า 60' => 0], 
        'round2' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, 'ต่ำกว่า 60' => 0]
    ],
    '4,5,6' => [
        'round1' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, 'ต่ำกว่า 60' => 0], 
        'round2' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, 'ต่ำกว่า 60' => 0]
    ],
    '7' => [
        'round1' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, 'ต่ำกว่า 60' => 0], 
        'round2' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, 'ต่ำกว่า 60' => 0]
    ],
    '8' => [
        'round1' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, 'ต่ำกว่า 60' => 0], 
        'round2' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, 'ต่ำกว่า 60' => 0]
    ]
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $duty_code = $row['duty_code'];
        $round = 'round' . $row['round'];
        if (in_array($duty_code, ['1', '2', '3'])) {
            $data['1,2,3'][$round][$row['evaluation_score']] += $row['count'];
        } elseif (in_array($duty_code, ['4', '5', '6'])) {
            $data['4,5,6'][$round][$row['evaluation_score']] += $row['count'];
        } elseif ($duty_code == '7') {
            $data['7'][$round][$row['evaluation_score']] += $row['count'];
        } elseif ($duty_code == '8') {
            $data['8'][$round][$row['evaluation_score']] += $row['count'];
        }
    }
}

// Query to fetch staff count data
$sql_staff_count = "
    SELECT 
        duty_code,
        COUNT(*) as staff_count
    FROM staff
    GROUP BY duty_code";

$result_staff_count = $conn->query($sql_staff_count);

$staff_count_data = [];
if ($result_staff_count->num_rows > 0) {
    while ($row = $result_staff_count->fetch_assoc()) {
        $staff_count_data[$row['duty_code']] = $row['staff_count'];
    }
}

// Get distinct years for the dropdown
$years_result = $conn->query("SELECT DISTINCT ests_year FROM estimate_score ORDER BY ests_year DESC");
$years = [];
if ($years_result->num_rows > 0) {
    while ($year_row = $years_result->fetch_assoc()) {
        $years[] = $year_row['ests_year'];
    }
}
$conn->close();
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
    <link rel="stylesheet" href="css/body.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="yearSelect" class="form-label">เลือกปี:</label>
                <select class="form-select" id="yearSelect" onchange="changeYear()">
                    <?php foreach ($years as $year): ?>
                        <option value="<?php echo $year; ?>" <?php echo $year == $selected_year ? 'selected' : ''; ?>>
                            <?php echo $year; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Table for duty code group 1,2,3 Round 1 -->
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-center">ข้าราชการ รอบที่ 1</h6>
                <table class="table table-light text-center table-secondary w-100">
                    <thead>
                        <tr>
                            <th>จำนวนบุคลากร</th>
                            <th>จำนวนผู้ได้รับการประเมิน</th>
                            <th>ระดับการประเมิน</th>
                            <th>จำนวน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo isset($staff_count_data['1']) ? $staff_count_data['1'] : 0; ?></td>
                            <td><?php echo array_sum($data['1,2,3']['round1']); ?></td>
                            <td>90 - 100</td>
                            <td><?php echo $data['1,2,3']['round1']['90 - 100']; ?></td>
                        </tr>
                        <tr>
                            <td rowspan="4"><?php echo isset($staff_count_data['1']) ? $staff_count_data['1'] : 0; ?></td>
                            <td rowspan="4"><?php echo array_sum($data['1,2,3']['round1']); ?></td>
                            <td>80 - 89</td>
                            <td><?php echo $data['1,2,3']['round1']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79</td>
                            <td><?php echo $data['1,2,3']['round1']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69</td>
                            <td><?php echo $data['1,2,3']['round1']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60</td>
                            <td><?php echo $data['1,2,3']['round1']['ต่ำกว่า 60']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table for duty code group 1,2,3 Round 2 -->
            <div class="col-md-6">
                <h6 class="text-center">ข้าราชการ รอบที่ 2</h6>
                <table class="table table-light text-center table-secondary w-100">
                    <thead>
                        <tr>
                            <th>จำนวนบุคลากร</th>
                            <th>จำนวนผู้ได้รับการประเมิน</th>
                            <th>ระดับการประเมิน</th>
                            <th>จำนวน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo isset($staff_count_data['1']) ? $staff_count_data['1'] : 0; ?></td>
                            <td><?php echo array_sum($data['1,2,3']['round2']); ?></td>
                            <td>90 - 100</td>
                            <td><?php echo $data['1,2,3']['round2']['90 - 100']; ?></td>
                        </tr>
                        <tr>
                            <td rowspan="4"><?php echo isset($staff_count_data['1']) ? $staff_count_data['1'] : 0; ?></td>
                            <td rowspan="4"><?php echo array_sum($data['1,2,3']['round2']); ?></td>
                            <td>80 - 89</td>
                            <td><?php echo $data['1,2,3']['round2']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79</td>
                            <td><?php echo $data['1,2,3']['round2']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69</td>
                            <td><?php echo $data['1,2,3']['round2']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60</td>
                            <td><?php echo $data['1,2,3']['round2']['ต่ำกว่า 60']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table for duty code group 4,5,6 Round 1 -->
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-center">พนักงานมหาวิทยาลัย รอบที่ 1</h6>
                <table class="table table-light text-center table-secondary w-100">
                    <thead>
                        <tr>
                            <th>จำนวนบุคลากร</th>
                            <th>จำนวนผู้ได้รับการประเมิน</th>
                            <th>ระดับการประเมิน</th>
                            <th>จำนวน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo isset($staff_count_data['4']) ? $staff_count_data['4'] : 0; ?></td>
                            <td><?php echo array_sum($data['4,5,6']['round1']); ?></td>
                            <td>90 - 100</td>
                            <td><?php echo $data['4,5,6']['round1']['90 - 100']; ?></td>
                        </tr>
                        <tr>
                            <td rowspan="4"><?php echo isset($staff_count_data['4']) ? $staff_count_data['4'] : 0; ?></td>
                            <td rowspan="4"><?php echo array_sum($data['4,5,6']['round1']); ?></td>
                            <td>80 - 89</td>
                            <td><?php echo $data['4,5,6']['round1']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79</td>
                            <td><?php echo $data['4,5,6']['round1']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69</td>
                            <td><?php echo $data['4,5,6']['round1']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60</td>
                            <td><?php echo $data['4,5,6']['round1']['ต่ำกว่า 60']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table for duty code group 4,5,6 Round 2 -->
            <div class="col-md-6">
                <h6 class="text-center">พนักงานมหาวิทยาลัย รอบที่ 2</h6>
                <table class="table table-light text-center table-secondary w-100">
                    <thead>
                        <tr>
                            <th>จำนวนบุคลากร</th>
                            <th>จำนวนผู้ได้รับการประเมิน</th>
                            <th>ระดับการประเมิน</th>
                            <th>จำนวน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo isset($staff_count_data['4']) ? $staff_count_data['4'] : 0; ?></td>
                            <td><?php echo array_sum($data['4,5,6']['round2']); ?></td>
                            <td>90 - 100</td>
                            <td><?php echo $data['4,5,6']['round2']['90 - 100']; ?></td>
                        </tr>
                        <tr>
                            <td rowspan="4"><?php echo isset($staff_count_data['4']) ? $staff_count_data['4'] : 0; ?></td>
                            <td rowspan="4"><?php echo array_sum($data['4,5,6']['round2']); ?></td>
                            <td>80 - 89</td>
                            <td><?php echo $data['4,5,6']['round2']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79</td>
                            <td><?php echo $data['4,5,6']['round2']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69</td>
                            <td><?php echo $data['4,5,6']['round2']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60</td>
                            <td><?php echo $data['4,5,6']['round2']['ต่ำกว่า 60']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table for duty code group 7 Round 1 -->
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-center">พนักงานประจำ รอบที่ 1</h6>
                <table class="table table-light text-center table-secondary w-100">
                    <thead>
                        <tr>
                            <th>จำนวนบุคลากร</th>
                            <th>จำนวนผู้ได้รับการประเมิน</th>
                            <th>ระดับการประเมิน</th>
                            <th>จำนวน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo isset($staff_count_data['7']) ? $staff_count_data['7'] : 0; ?></td>
                            <td><?php echo array_sum($data['7']['round1']); ?></td>
                            <td>90 - 100</td>
                            <td><?php echo $data['7']['round1']['90 - 100']; ?></td>
                        </tr>
                        <tr>
                            <td rowspan="4"><?php echo isset($staff_count_data['7']) ? $staff_count_data['7'] : 0; ?></td>
                            <td rowspan="4"><?php echo array_sum($data['7']['round1']); ?></td>
                            <td>80 - 89</td>
                            <td><?php echo $data['7']['round1']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79</td>
                            <td><?php echo $data['7']['round1']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69</td>
                            <td><?php echo $data['7']['round1']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60</td>
                            <td><?php echo $data['7']['round1']['ต่ำกว่า 60']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table for duty code group 7 Round 2 -->
            <div class="col-md-6">
                <h6 class="text-center">พนักงานประจำ รอบที่ 2</h6>
                <table class="table table-light text-center table-secondary w-100">
                    <thead>
                        <tr>
                            <th>จำนวนบุคลากร</th>
                            <th>จำนวนผู้ได้รับการประเมิน</th>
                            <th>ระดับการประเมิน</th>
                            <th>จำนวน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo isset($staff_count_data['7']) ? $staff_count_data['7'] : 0; ?></td>
                            <td><?php echo array_sum($data['7']['round2']); ?></td>
                            <td>90 - 100</td>
                            <td><?php echo $data['7']['round2']['90 - 100']; ?></td>
                        </tr>
                        <tr>
                            <td rowspan="4"><?php echo isset($staff_count_data['7']) ? $staff_count_data['7'] : 0; ?></td>
                            <td rowspan="4"><?php echo array_sum($data['7']['round2']); ?></td>
                            <td>80 - 89</td>
                            <td><?php echo $data['7']['round2']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79</td>
                            <td><?php echo $data['7']['round2']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69</td>
                            <td><?php echo $data['7']['round2']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60</td>
                            <td><?php echo $data['7']['round2']['ต่ำกว่า 60']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table for duty code group 8 Round 1 -->
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-center">พนักงานชั่วคราว รอบที่ 1</h6>
                <table class="table table-light text-center table-secondary w-100">
                    <thead>
                        <tr>
                            <th>จำนวนบุคลากร</th>
                            <th>จำนวนผู้ได้รับการประเมิน</th>
                            <th>ระดับการประเมิน</th>
                            <th>จำนวน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo isset($staff_count_data['8']) ? $staff_count_data['8'] : 0; ?></td>
                            <td><?php echo array_sum($data['8']['round1']); ?></td>
                            <td>90 - 100</td>
                            <td><?php echo $data['8']['round1']['90 - 100']; ?></td>
                        </tr>
                        <tr>
                            <td rowspan="4"><?php echo isset($staff_count_data['8']) ? $staff_count_data['8'] : 0; ?></td>
                            <td rowspan="4"><?php echo array_sum($data['8']['round1']); ?></td>
                            <td>80 - 89</td>
                            <td><?php echo $data['8']['round1']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79</td>
                            <td><?php echo $data['8']['round1']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69</td>
                            <td><?php echo $data['8']['round1']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60</td>
                            <td><?php echo $data['8']['round1']['ต่ำกว่า 60']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table for duty code group 8 Round 2 -->
            <div class="col-md-6">
                <h6 class="text-center">พนักงานชั่วคราว รอบที่ 2</h6>
                <table class="table table-light text-center table-secondary w-100">
                    <thead>
                        <tr>
                            <th>จำนวนบุคลากร</th>
                            <th>จำนวนผู้ได้รับการประเมิน</th>
                            <th>ระดับการประเมิน</th>
                            <th>จำนวน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo isset($staff_count_data['8']) ? $staff_count_data['8'] : 0; ?></td>
                            <td><?php echo array_sum($data['8']['round2']); ?></td>
                            <td>90 - 100</td>
                            <td><?php echo $data['8']['round2']['90 - 100']; ?></td>
                        </tr>
                        <tr>
                            <td rowspan="4"><?php echo isset($staff_count_data['8']) ? $staff_count_data['8'] : 0; ?></td>
                            <td rowspan="4"><?php echo array_sum($data['8']['round2']); ?></td>
                            <td>80 - 89</td>
                            <td><?php echo $data['8']['round2']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79</td>
                            <td><?php echo $data['8']['round2']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69</td>
                            <td><?php echo $data['8']['round2']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60</td>
                            <td><?php echo $data['8']['round2']['ต่ำกว่า 60']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    
    <script>
        function changeYear() {
            const year = document.getElementById('yearSelect').value;
            window.location.href = `dashboard.php?year=${year}`;
        }
    </script>
</body>
</html>
