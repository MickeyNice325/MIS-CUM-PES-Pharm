<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
include("../../../fn/config.php");
include("../../../fn/session.php");

// Retrieve parameters from the query string
$duty_code = isset($_GET['duty_code']) ? $_GET['duty_code'] : '';
$round = isset($_GET['round']) ? $_GET['round'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Fetch data from the database based on the received parameters
$sql = "SELECT 
    s.fname, 
    s.lname, 
    es.performance, 
    es.behavior, 
    es.score, 
    es.results,
    p.name AS position_name
FROM estimate_score es
JOIN staff s ON es.staff_code = s.code
JOIN position p ON s.position_code = p.code
WHERE es.duty_code IN ($duty_code) 
AND es.round = '$round'
AND es.ests_year = '$year'
AND es.status = 'complete'
AND es.dean_status = 'complete'";

$result = $conn->query($sql);
$scores = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $scores[] = $row;
    }
}

// mPDF configuration
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/../font/TH Sarabun PSK',
    ]),
    'fontdata' => $fontData + [
        'thsarabun' => [
            'R' => 'THSarabun.ttf',
            'I' => 'THSarabun Italic.ttf',
            'B' => 'THSarabun Bold.ttf',
            'BI' => 'THSarabun BoldItalic.ttf',
        ]
    ],
    'default_font' => 'thsarabun'
]);

$lastyear = $year - 1;

if ($type === 'ข้าราชการ') {
    if ($round === '1') {
        $date_range = 'ประจำปีงบประมาณ '.$year.' (1 มิถุนายน ' . $lastyear . ' - 30 พฤศจิกายน  ' . $lastyear . ')';
    } else {
        $date_range = 'ประจำปีงบประมาณ '.$year.' (1 ธันวาคม ' . $lastyear . ' - 31 พฤษภาคม ' . $year . ')';
    }
} elseif ($type === 'ลูกจ้างประจำ') {
    if ($round === '1') {
        $date_range = 'ประจำปีงบประมาณ '.$year.' (1 ตุลาคม ' . $lastyear . ' - 30 พฤศจิกายน ' . $lastyear . ')';
    } else {
        $date_range = 'ประจำปีงบประมาณ '.$year.' (1 ธันวาคม ' . $lastyear . ' - 30 กันยายน ' . $year . ')';
    }
} elseif ($type === 'พนักงานมหาวิทยาลัย' || $type === 'พนักงานชั่วคราว') {
    $date_range = '1 มิถุนายน ' . $lastyear . ' - 31 พฤษภาคม ' . $year . '';
} else {
    $date_range = '';
}

// สร้างเนื้อหา HTML สำหรับ PDF
$html = '
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ผลการประเมิน</title>
    <style>
        body { font-family: "thsarabun"; }
        .txt { font-size: 20px; text-align: justify; }
        .border {
            border: 1px solid black;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
        }
        thead th {
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .padding-left {
            padding-left: 10px;
        }
        p.txt2 {
            font-weight: bold;
            margin-top: 5px;
            font-size: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div>
        <p class="txt2">ผลการประเมินการปฏิบัติงานของ' . $type . '</p>
        <p class="txt2">' . $date_range . '</p>
    </div>
    <div>
        <table class="border txt" width="100%">
            <thead>
                <tr>
                    <th class="border text-center" style="width:5%">ลำดับ</th>
                    <th class="border text-center" style="width:30%">ชื่อ-สกุล</th>
                    <th class="border text-center" style="width:25%">ตำแหน่ง</th>
                    <th class="border text-center" style="width:15%">ผลการประเมิน</th>
                </tr>
            </thead>
            <tbody>';

if ($result->num_rows > 0) {
    foreach ($scores as $index => $row) {
        $html .= '
        <tr>
            <td class="border text-center">' . ($index + 1) . '</td>
            <td class="border padding-left">' . $row['fname'] . ' ' . $row['lname'] . '</td>
            <td class="border padding-left">' . $row['position_name'] . '</td>
            <td class="border text-center">' . $row['results'] . '</td> 
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="7" class="text-center">No data available</td></tr>';
}

$html .= '
            </tbody>
        </table>
    </div>
</body>
</html>
';


$mpdf->AddPage();
$mpdf->WriteHTML($html);

// Output the PDF to the browser
$filename = 'evaluation_reports.pdf';
$mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
?>
