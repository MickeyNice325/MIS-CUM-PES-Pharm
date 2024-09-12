<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
include("../../../fn/config.php");
include("../../../fn/session.php");

if (!isset($_SESSION['code'])) {
    header("Location: ../../../login.php");
    exit();
}

$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/../../../font/',
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

function convertToThaiNumber($number) {
    $thai_numbers = ['๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'];
    $number = strval($number);
    $thai_number = '';

    for ($i = 0; $i < strlen($number); $i++) {
        $thai_number .= $thai_numbers[intval($number[$i])];
    }

    return $thai_number;
}

$year = date("Y") + 543;
$nextyear = $year + 1;

$thai_year = convertToThaiNumber($year);
$thai_nextyear = convertToThaiNumber($nextyear);


$sql = "SELECT c.staff_code, 
               c.staff_name as staff_name,
               GROUP_CONCAT(CONCAT(com.fname, ' ', com.lname) SEPARATOR '<br>&nbsp;') as com_names
            FROM committee c 
            JOIN staff com ON c.com_code = com.code 
            WHERE c.status = 'committee'
            GROUP BY c.staff_code, c.staff_name";
$result = $conn->query($sql);

$html = '<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบรายชื่อกรรมการ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body { font-family: "thsarabun"; }
        .txt { font-size: 20px; text-align: justify; }
        .border {
            border-right: 1px solid black;
            border-left: 1px solid black;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }
        thead th {
            text-align: center;
        }
        p.txt2 {
            font-weight: bold;
            margin-top: 5px;
            font-size: 20px;
            text-align: center;
        }
    </style>
</head>
<body>';

if ($result) {
    $html .= '
    <table class="text-center" style="width:100%">
        <tbody>
            <tr>
                <td>
                    <div>
                        <img src="krut.png" style="width:50px">
                        <p class="txt2">คำสั่งคณะเภสัชศาสตร์ <br>
                        ที่ ๒๐๙/'.$thai_year.'<br>
                        เรื่อง แต่งตั้งคณะกรรมการประเมินผลการปฏิบัติงานของลูกจ้างประจำ <br>
                        เพื่อประกอบการพิจารณาเลื่อนขั้นค่าจ้างลูกค้าประจำ ปีงบประมาณ '.$thai_nextyear.' ณ ๑ ตุลาคม '.$thai_year.' <br>
                        </p>
                        <p class="txt">-------------------------------------</p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    <table class="" style="width:100%">
        <tbody>
            <tr>
                <td style="width:15%"></td>
                <td>
                    <div>
                        <p class="txt">เพื่อให้การประเมินประสิทธิภาพและประสิทธิผลการปฏิบัติงานของลูกจ้างประจำ สังกัดคณะเภสัชศาสตร์</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div>
                        <p class="txt">เพื่อประกอบการเลื่อนขั้นค่าจ้างลูกจ้างประจำ ปีงบประมาณ '.$nextthai_nextyearyear.' ณ ๑ ตุลาคม '.$thai_year.' (ผลการปฏิบัติงานระหว่างวันที่ <br>
                            ๑ เมษายน '.$thai_year.' – 30 กันยายน '.$thai_nextyear.') เป็นไปด้วยความเรียบร้อย ฉะนั้น อาศัยอำนาจตามความในมาตรา ๔๐ มาตรา ๗๔ <br>
                            วรรคหก และมาตรา ๘๗ แห่งพระราชบัญญัติมหาวิทยาลัยเชียงใหม่ พ.ศ. ๒๕๕๑ จึงแต่งตั้งคณะกรรมการฯ ประกอบด้วย.-</p>
                    </div>
                </td>
            </tr>
        </tbody>
        
    </table>

    <div class="container">
        <table class="table table-dark text-dark txt border">
            <thead class="border">
                <tr class="border">
                    <th class="border" style="width:35%">รายชื่อ</th>
                    <th class="border">คณะกรรมการประเมิน</th>
                </tr>
            </thead>
            <tbody class="border">';
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $html .= "<tr class='border'>
                        <td class='border'>&nbsp;" . $row['staff_name'] . "</td>
                        <td class='border'>&nbsp;" . $row['com_names'] . "</td>
                        </tr>";
                    }
                } else {
                    $html .= "<tr><td colspan='2'>No data available</td></tr>";
                }
    $html .= '
            </tbody>
        </table>
        <p class="txt">ให้คณะกรรมการฯ มีหน้าที่ประเมินผลการปฏิบัติงานตามแบบประเมินในระบบออนไลน์ CMU MIS</p>
    </div>';
} else {
    $html .= '<div class="container mt-5 mb-5"><p>No data available</p></div>';
}

$html .= '</body></html>';

$mpdf->WriteHTML($html);
$mpdf->Output('list_committee.pdf', \Mpdf\Output\Destination::INLINE);
?>
