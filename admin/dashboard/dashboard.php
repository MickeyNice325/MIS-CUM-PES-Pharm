<?php 
include("../../fn/config.php");
include("../../fn/session.php");
include('nav.php');


$selected_year = isset($_GET['year']) ? $_GET['year'] : date('Y');


$sql = "
    SELECT 
        CASE 
            WHEN score BETWEEN 90 AND 100 THEN '90 - 100'
            WHEN score BETWEEN 80 AND 89 THEN '80 - 89'
            WHEN score BETWEEN 70 AND 79 THEN '70 - 79'
            WHEN score BETWEEN 60 AND 69 THEN '60 - 69'
            WHEN score BETWEEN 50 AND 59 THEN '0 - 59'
            WHEN score BETWEEN 0 AND 49 THEN '0 - 49'
        END AS evaluation_score,
        duty_code,
        round,
        COUNT(*) as count 
    FROM estimate_score 
    WHERE ests_year = $selected_year
    AND status = 'complete'
    AND dean_status = 'complete'
    GROUP BY evaluation_score, duty_code, round";

$result = $conn->query($sql);

$data = [
    '1,2,3' => [
        'round1' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, '50 - 59' => 0, '0 - 49'=> 0], 
        'round2' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, '50 - 59' => 0, '0 - 49'=> 0]
    ],
    '4,5,10' => [
        'round1' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, '50 - 59' => 0, '0 - 49'=> 0]
    ],
    '7' => [
        'round1' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, '50 - 59' => 0, '0 - 49'=> 0], 
        'round2' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, '50 - 59' => 0, '0 - 49'=> 0]
    ],
    '8,6' => [
        'round1' => ['90 - 100' => 0, '80 - 89' => 0, '70 - 79' => 0, '60 - 69' => 0, '50 - 59' => 0, '0 - 49'=> 0]
    ]
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $duty_code = $row['duty_code'];
        $round = 'round' . $row['round'];
        if (in_array($duty_code, ['1', '2', '3'])) {
            $data['1,2,3'][$round][$row['evaluation_score']] += $row['count'];
        } elseif (in_array($duty_code, ['4', '5', '10'])) {
            $data['4,5,10']['round1'][$row['evaluation_score']] += $row['count'];
        } elseif ($duty_code == '7') {
            $data['7'][$round][$row['evaluation_score']] += $row['count'];
        } elseif (in_array($duty_code, ['8', '6'])) {
            $data['8,6']['round1'][$row['evaluation_score']] += $row['count'];
        }
    }
}


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


$years_result = $conn->query("SELECT DISTINCT ests_year FROM estimate_score ORDER BY ests_year DESC");
$years = [];
if ($years_result->num_rows > 0) {
    while ($year_row = $years_result->fetch_assoc()) {
        $years[] = $year_row['ests_year'];
    }
}


$sql_individual_scores = "
    SELECT 
        s.fname, s.lname, es.score, es.duty_code, s.job_code, es.round
    FROM estimate_score es
    JOIN staff s ON es.staff_code = s.code 
    WHERE es.ests_year = $selected_year";

$result_individual_scores = $conn->query($sql_individual_scores);

$individual_scores = [];
if ($result_individual_scores->num_rows > 0) {
    while ($row = $result_individual_scores->fetch_assoc()) {
        $duty_code = $row['duty_code'];
        $job_code = $row['job_code'];
        $round = 'round' . $row['round'];
        $name = $row['fname'] . ' ' . $row['lname'];

        $combined_duty_code = '';
        $combined_job_code = '';

        if (in_array($duty_code, ['1', '2', '3'])) {
            $combined_duty_code = '1,2,3';
        } elseif (in_array($duty_code, ['4', '5', '10'])) {
            $combined_duty_code = '4,5,10';
        } elseif (in_array($duty_code, ['8', '6'])) {
            $combined_duty_code = '8,6';
        } else {
            $combined_duty_code = $duty_code;
        }

        if (!isset($individual_scores[$combined_duty_code][$round])) {
            $individual_scores[$combined_duty_code][$round] = [];
        }
        $individual_scores[$combined_duty_code][$round][$name] = $row['score'];

        if (!isset($individual_scores[$job_code][$round])) {
            $individual_scores[$job_code][$round] = [];
        }
        $individual_scores[$job_code][$round][$name] = $row['score'];
    }
}

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
    <link rel="stylesheet" href="css/body.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif !important;
        }
        #lineChartContainer {
            display: none;
        }
    </style>
    <title>Dashboard</title>
</head>
<body>
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="yearSelect" class="form-label">เลือกปี</label>
            <select class="form-select" id="yearSelect" onchange="changeYear()">
                <?php foreach ($years as $year): ?>
                    <option value="<?php echo $year; ?>" <?php echo $year == $selected_year ? 'selected' : ''; ?>>
                        <?php echo $year; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="chartSelect" class="form-label">รูปแบบ</label>
            <select class="form-select" id="chartSelect" onchange="changeChart()">
                <option value="bar">กราฟแท่ง</option>
                <option value="line">กราฟเส้น</option>
            </select>
        </div>

        <div class="col-md-4" id="dutyCodeSelectContainer" style="display: none;">
            <label for="dutyCodeSelect" class="form-label">เลือกประเภทพนักงาน</label>
            <select class="form-select" id="dutyCodeSelect" onchange="changeDutyCode()">
                <option value="1,2,3">ข้าราชการ</option>
                <option value="4,5,10">พนักงานมหาวิทยาลัย</option>
                <option value="7">ลูกจ้างประจำ</option>
                <option value="8,6">พนักงานชั่วคราว</option>
            </select>
        </div>
        <div class="col-md-4" id="jobCodeSelectContainer" style="display: none;">
            <label for="jobCodeSelect" class="form-label">เลือกประเภทงาน</label>
            <select class="form-select" id="jobCodeSelect" onchange="changeJobCode()">
            <?php 
                $sql_job = "SELECT * FROM `job` ";
                $result = $conn->query($sql_job);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                       $job_code = $row['code'];
                        $job_name = $row['name'];
                        echo "<option value='$job_code'>$job_name</option>";
                    }
                } else {
                    echo "<option value=''>No jobs available</option>";
                }
                ?>
            </select>
        </div>

    </div>
    
    <div id="barChartContainer">
        <div class="row">
            <div class="col-md-6">
                <h6>ข้าราชการ</h6>
                <canvas id="chart1"></canvas>
            </div>
            <div class="col-md-6">
                <h6>ลูกจ้างประจำ</h6>
                <canvas id="chart3"></canvas>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <h6>พนักงานมหาวิทยาลัย</h6>
                <canvas id="chart2"></canvas>
            </div>
            <div class="col-md-6">
                <h6>พนักงานชั่วคราว</h6>
                <canvas id="chart4"></canvas>
            </div>
        </div>
    </div>

    <div id="lineChartContainer">
        
        <canvas id="lineChart"></canvas>
    </div>

    <script>
        const data = <?php echo json_encode($data); ?>;
        const individualScores = <?php echo json_encode($individual_scores); ?>;
        console.log('Individual Scores:', individualScores);
        const labels1 = ["90 - 100", "80 - 89", "70 - 79", "60 - 69", "50 - 59", "0 - 49"];
        const labels2 = ["90 - 100", "80 - 89", "70 - 79", "60 - 69", "50 - 59", "0 - 49"];
        
        const createBarChart = (ctx, title, data, labels) => {
            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: `รอบที่ 1`,
                            data: labels.map(label => data['round1'][label] || 0),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: `รอบที่ 2`,
                            data: labels.map(label => data['round2'] ? data['round2'][label] || 0 : 0),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    family: 'Noto Sans Thai',
                                    size: 14
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,

                            ticks: {
                                font: {
                                    family: 'Noto Sans Thai',
                                    size: 12
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: 'Noto Sans Thai',
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        };

        const createLineChart = (ctx, title, round1Data, round2Data, labels) => {
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: `รอบที่ 1`,
                    data: labels.map(label => round1Data[label] || 0),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    fill: false
                },
                {
                    label: `รอบที่ 2`,
                    data: labels.map(label => round2Data[label] || 0),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    fill: false
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        font: {
                            family: 'Noto Sans Thai',
                            size: 14  ,
                            maxRotation: 45, 
                            minRotation: 30, 
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            return tooltipItems[0].label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 70,
                    max: 100,
                    ticks: {
                        font: {
                            family: 'Noto Sans Thai',
                            size: 12
                        }
                    }
                },
                x: {
                    max: 100,
                    ticks: {
                        font: {
                            family: 'Noto Sans Thai',
                            size: 12
                        }
                    }
                }
            }
        }
    });
};

        let chart1, chart2, chart3, chart4, lineChart;
        const createCharts = () => {
    const chartType = document.getElementById('chartSelect').value;
    const dutyCode = document.getElementById('dutyCodeSelect') ? document.getElementById('dutyCodeSelect').value : '1,2,3';
    const jobCode = document.getElementById('jobCodeSelect') ? document.getElementById('jobCodeSelect').value : '120102,120101,120104,120105,120103';
    console.log('Duty Code:', dutyCode);
    console.log('Job Code:', jobCode);
    
    if (chart1) chart1.destroy();
    if (chart2) chart2.destroy();
    if (chart3) chart3.destroy();
    if (chart4) chart4.destroy();
    if (lineChart) lineChart.destroy();

    if (chartType === 'bar') {
        document.getElementById('barChartContainer').style.display = 'block';
        document.getElementById('lineChartContainer').style.display = 'none';
        chart1 = createBarChart(document.getElementById('chart1'), 'ข้าราชการ', data['1,2,3'], labels1);
        chart2 = createBarChart(document.getElementById('chart2'), 'พนักงานมหาวิทยาลัย', data['4,5,10'], labels2);
        chart3 = createBarChart(document.getElementById('chart3'), 'ลูกจ้างประจำ', data['7'], labels1);
        chart4 = createBarChart(document.getElementById('chart4'), 'พนักงานชั่วคราว', data['8,6'], labels2);
        document.getElementById('dutyCodeSelectContainer').style.display = 'none';
        document.getElementById('jobCodeSelectContainer').style.display = 'none';
    } else if (chartType === 'line') {
        document.getElementById('barChartContainer').style.display = 'none';
        document.getElementById('lineChartContainer').style.display = 'block';

        
        let round1Data = {}, round2Data = {};
const processScores = (codes) => {
    codes.forEach(code => {
        if (individualScores[code] && individualScores[code]['round1']) {
            Object.keys(individualScores[code]['round1']).forEach(name => {
                if (!round1Data[name]) {
                    round1Data[name] = 0;
                }
                round1Data[name] += individualScores[code]['round1'][name];
            });
        }
        if (individualScores[code] && individualScores[code]['round2']) {
            Object.keys(individualScores[code]['round2']).forEach(name => {
                if (!round2Data[name]) {
                    round2Data[name] = 0;
                }
                round2Data[name] += individualScores[code]['round2'][name];
            });
        }
    });
};

const labels = Object.keys(individualScores[dutyCode] ? individualScores[dutyCode]['round1'] || {} : {}).sort((a, b) => a.localeCompare(b, 'th'));

if (dutyCode === '1,2,3') {
    processScores(['1', '2', '3']);
} else if (dutyCode === '4,5,10') {
    processScores(['4', '5', '10']);
    round2Data = {};
} else if (dutyCode === '7') {
    processScores(['7']);
} else if (dutyCode === '8,6') {
    processScores(['8', '6']);
}

const labels2 = Object.keys(individualScores[jobCode] ? individualScores[jobCode]['round1'] || {} : {}).sort((a, b) => a.localeCompare(b, 'th'));

if (jobCode === '120101') {
    processScores(['120101']);
} else if (jobCode === '120102') {
    processScores(['120102']);
    round2Data = {};
} else if (jobCode === '120104') {
    processScores(['120104']);
} else if (jobCode === '120105') {
    processScores(['120105']);
} else if (jobCode === '120103') {
    processScores(['120103']);
}

console.log('Round 1 Data:', round1Data);
console.log('Round 2 Data:', round2Data);

lineChart = createLineChart(document.getElementById('lineChart'), 'พนักงาน', round1Data, round2Data, labels);
document.getElementById('dutyCodeSelectContainer').style.display = 'block';
document.getElementById('jobCodeSelectContainer').style.display = 'block';

    }
};



createCharts();

function changeYear() {
    const year = document.getElementById('yearSelect').value;
    window.location.href = `dashboard.php?year=${year}`;
}

function changeChart() {
    createCharts();
}

function changeDutyCode() {
    createCharts();
}

function changeJobCode() {
    createCharts();
}
    </script>


<div class="container mt-5">
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
                            <td>
                                <?php 
                                echo (isset($staff_count_data['1']) ? $staff_count_data['1'] : 0) + 
                                     (isset($staff_count_data['2']) ? $staff_count_data['2'] : 0) + 
                                     (isset($staff_count_data['3']) ? $staff_count_data['3'] : 0); 
                                ?>
                            </td>
                            <td><?php echo array_sum($data['1,2,3']['round1']); ?></td>
                            <td>90 - 100 [ ดีเยี่ยม ]</td>
                            <td><?php echo $data['1,2,3']['round1']['90 - 100']; ?></td>
                        </tr>   
                        <tr>
                            <td rowspan="4">
                            <a href="list.php?duty_code=1,2,3&round=1&year=<?php echo $selected_year; ?>&type=ข้าราชการ" class="btn btn-outline-success">รายละเอียด</a>
                            </td>
                            <td rowspan="4"></td>
                            <td>80 - 89 [ ดีมาก ]</td>
                            <td><?php echo $data['1,2,3']['round1']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79 [ ดี ]</td>
                            <td><?php echo $data['1,2,3']['round1']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69 [ พอใช้ ]</td>
                            <td><?php echo $data['1,2,3']['round1']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60 [ ปรับปรุง ]</td>
                            <td><?php echo $data['1,2,3']['round1']['50 - 59']+$data['1,2,3']['round1']['0 - 49']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
                            <td>
                                <?php 
                                echo (isset($staff_count_data['1']) ? $staff_count_data['1'] : 0) + 
                                     (isset($staff_count_data['2']) ? $staff_count_data['2'] : 0) + 
                                     (isset($staff_count_data['3']) ? $staff_count_data['3'] : 0); 
                                ?>
                            </td>
                            <td><?php echo array_sum($data['1,2,3']['round2']); ?></td>
                            <td>90 - 100 [ ดีเยี่ยม ]</td>
                            <td><?php echo $data['1,2,3']['round2']['90 - 100']; ?></td>
                        </tr>   
                        <tr>
                            <td rowspan="4">
                            <a href="list.php?duty_code=1,2,3&round=2&year=<?php echo $selected_year; ?>&type=ข้าราชการ" class="btn btn-outline-success">รายละเอียด</a>
                            </td>
                            <td rowspan="4"></td>
                            <td>80 - 89 [ ดีมาก ]</td>
                            <td><?php echo $data['1,2,3']['round2']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>70 - 79 [ ดี ]</td>
                            <td><?php echo $data['1,2,3']['round2']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>60 - 69 [ พอใช้ ]</td>
                            <td><?php echo $data['1,2,3']['round2']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60 [ ปรับปรุง ]</td>
                            <td><?php echo $data['1,2,3']['round2']['50 - 59']+$data['1,2,3']['round2']['0 - 49']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h6 class="text-center">ลูกจ้างประจำ รอบที่ 1</h6>
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
                            <td>90 - 100 [ ดีเด่น ]</td>
                            <td><?php echo $data['7']['round1']['90 - 100']; ?></td>
                        </tr>   
                        <tr>
                            <td rowspan="3">
                            <a href="list.php?duty_code=7&round=1&year=<?php echo $selected_year; ?>&type=ลูกจ้างประจำ" class="btn btn-outline-success">รายละเอียด</a>
                            </td>
                            <td rowspan="3"></td>
                            <td>60 - 89 [ เป็นที่ยอมรับได้ ]</td>
                            <td><?php echo $data['7']['round1']['80 - 89']; + $data['7']['round1']['60 - 69']+  $data['7']['round1']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60 [ ต้องปรับปรุง ]</td>
                            <td><?php echo $data['7']['round1']['50 - 59']+$data['7']['round1']['0 - 49']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-center">ลูกจ้างประจำ รอบที่ 2</h6>
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
                            <td>90 - 100 [ ดีเด่น ]</td>
                            <td><?php echo $data['7']['round2']['90 - 100']; ?></td>
                        </tr>   
                        <tr>
                            <td rowspan="3">
                            <a href="list.php?duty_code=7&round=2&year=<?php echo $selected_year; ?>&type=ลูกจ้างประจำ" class="btn btn-outline-success">รายละเอียด</a>
                            </td>
                            <td rowspan="3"></td>
                            <td>60 - 89 [ เป็นที่ยอมรับได้ ]</td>
                            <td><?php echo $data['7']['round2']['80 - 89']+$data['7']['round2']['60 - 69']+ $data['7']['round2']['70 - 79']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 60 [ ต้องปรับปรุง ]</td>
                            <td><?php echo $data['7']['round2']['50 - 59']+$data['7']['round2']['0 - 49']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

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
                            <td>
                                <?php 
                                echo (isset($staff_count_data['4']) ? $staff_count_data['4'] : 0) + 
                                     (isset($staff_count_data['5']) ? $staff_count_data['5'] : 0) + 
                                     (isset($staff_count_data['10']) ? $staff_count_data['10'] : 0); 
                                ?>
                            </td>
                            <td><?php echo array_sum($data['4,5,10']['round1']); ?></td>
                            <td>90 - 100 [ ดีมาก ]</td>
                            <td><?php echo $data['4,5,10']['round1']['90 - 100']; ?></td>
                        </tr>   
                        <tr>
                            <td rowspan="5">
                            <a href="list.php?duty_code=4,5,10&round=1&year=<?php echo $selected_year; ?>&type=พนักงานมหาวิทยาลัย" class="btn btn-outline-success">รายละเอียด</a>
                            </td>
                            <td rowspan="5"></td>
                            <td>80 ไม่ถึง 90 [ ดี ]</td>
                            <td><?php echo $data['4,5,10']['round1']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>60 ไม่ถึง 80 [ ปานกลาง ]</td>
                            <td><?php echo $data['4,5,10']['round1']['70 - 79'] + $data['4,5,10']['round1']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>50 ไม่ถึง 60 [ พอใช้ ]</td>
                            <td><?php echo $data['4,5,10']['round1']['50 - 59']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 50 [ ต้องปรับปรุง ]</td>
                            <td><?php echo $data['4,5,10']['round1']['0 - 49']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
                            <td>
                                <?php 
                                echo (isset($staff_count_data['8']) ? $staff_count_data['8'] : 0) + 
                                     (isset($staff_count_data['6']) ? $staff_count_data['6'] : 0); 
                                ?>
                            </td>
                            <td><?php echo array_sum($data['8,6']['round1']); ?></td>
                            <td>90 - 100 [ ดีมาก ]</td>
                            <td><?php echo $data['8,6']['round1']['90 - 100']; ?></td>
                        </tr>   
                        <tr>
                            <td rowspan="5">
                            <a href="list.php?duty_code=8,6&round=1&year=<?php echo $selected_year; ?>&type=พนักงานชั่วคราว" class="btn btn-outline-success">รายละเอียด</a>
                            </td>
                            <td rowspan="5"></td>
                            <td>80 ไม่ถึง 90 [ ดี ]</td>
                            <td><?php echo $data['8,6']['round1']['80 - 89']; ?></td>
                        </tr>
                        <tr>
                            <td>60 ไม่ถึง 80 [ ปานกลาง ]</td>
                            <td><?php echo $data['8,6']['round1']['70 - 79']; + $data['8,6']['round1']['60 - 69']; ?></td>
                        </tr>
                        <tr>
                            <td>50 ไม่ถึง 60 [ พอใช้ ]</td>
                            <td><?php echo $data['8,6']['round1']['50 - 59']; ?></td>
                        </tr>
                        <tr>
                            <td>ต่ำกว่า 50 [ ต้องปรับปรุง ]</td>
                            <td><?php echo $data['8,6']['round1']['0 - 49']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-c2A8S6an8zP1yWfVdfXTXK58QhVndDpu2xJdDThfpd3C2grg4AIHg2G3J6Kcyl/K" crossorigin="anonymous"></script>
    
</body>
</html>