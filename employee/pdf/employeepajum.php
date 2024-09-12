<?php
require_once __DIR__ . '../../../vendor/autoload.php';
include("../../fn/config.php");
include("../../fn/session.php");

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/../font/',
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

$estimate_score_id = $_REQUEST['id'];

$year = date("Y") + 543;
$pyear = date("Y") + 543-1;
$sql = "SELECT * FROM estimate_score WHERE id = $estimate_score_id ";
$query = mysqli_query($conn, $sql);

$current_date = date("d-m-") . (date("Y") + 543);

while ($row = mysqli_fetch_array($query)) {
    $status = $row["status"];
    $estimate_score_year = $row["ests_year"];
    $oldyear = $estimate_score_year - 1;

    $date_c = $row["date_complete"];
    $date = new DateTime($date_c);
    $date_complete = $date->format('d/m/') . ($date->format('Y') + 543);

    $dean_status = $row['dean_status'];

    $dean_d = $row['dean_date'];
    $dated = new DateTime($dean_d);
    $dean_date = $dated->format('d/m/') . ($dated->format('Y') + 543);

    $performance = $row['performance'];
    $behavior = $row['behavior'];
    $score = $row['score'];
    $percentage_performance = ($performance / 70) * 100;
    $percentage_behavior = ($behavior / 30) * 100;

    $staff_code = $row['staff_code'];
    $sql_staff = "SELECT * FROM staff WHERE code = '$staff_code'";
    $query_staff = mysqli_query($conn, $sql_staff);

    if (!$query_staff) {
        die('Error: ' . mysqli_error($conn));
    }

    $row_staff = mysqli_fetch_array($query_staff);
    $staff_name = $row_staff ? $row_staff['fname'] . ' ' . $row_staff['lname'] : 'N/A';

    $position_code = $row_staff['position_code'];
    $sql_position = "SELECT * FROM position WHERE code = '$position_code'";
    $query_position = mysqli_query($conn, $sql_position);
    $row_position = mysqli_fetch_array($query_position);
    $position_name = $row_position ? $row_position['name'] : 'N/A';

    $dep_code = $row_staff['dep_code'];
    $sql_dep = "SELECT * FROM department WHERE code = '$dep_code'";
    $query_dep = mysqli_query($conn, $sql_dep);
    $row_dep = mysqli_fetch_array($query_dep);
    $dep_name = $row_dep ? $row_dep['name'] : 'N/A';

    $job_code = $row_staff['job_code'];
    $sql_job = "SELECT * FROM job WHERE code = '$job_code'";
    $query_job = mysqli_query($conn, $sql_job);
    $row_job = mysqli_fetch_array($query_job);
    $job_name = $row_job ? $row_job['name'] : 'N/A';

    $unit_code = $row_staff['unit_code'];
    $sql_unit = "SELECT * FROM unit WHERE code = '$unit_code'";
    $query_unit = mysqli_query($conn, $sql_unit);
    $row_unit = mysqli_fetch_array($query_unit);
    $unit_name = $row_unit ? $row_unit['name'] : 'N/A';

    $round = $row['round'];
    $html = '
    <!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <title>คะแนนปี' . $year . 'ของพนักงาน</title>
        <style>
                .table2 {
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }
            body {
                font-family: "thsarabun";
            }
            .txt {
                font-size: 20px;
            }
            tbody {
                margin: 0px;
            }
        .content {
                    text-align: center;
                    margin: 0 auto;
                }
        }
        </style>
    </head>
    <body>';

    if ($round == '1') {
        $html .= '<h4 align="right">ครั้งที่ 1 (1 ตุลาคม ' . $year . ' - 30 พฤศจิกายน ' . $year . ')</h4>';
    } else if ($round == '2') {
        $html .= '<h4 align="right">ครั้งที่ 2 (1 ธันวาคม ' . $pyear . ' - 30 กันยายน ' . $year . ')</h4>';
    }

    $html .= '
        <table class="" style="width:100%">
         <tr>
            <td style="margin-top:0px;">
                <img src="bd55ccc4416012910a723da8f810658b-removebg-preview.png" alt="รูปภาพ" style="width:50px;height:auto;">
            </td>
            <td>
                <div class="content table2">
                    <h2 style="text-align: center;">บันทึกข้อความ</h2>
                </div>
            </td>
        </tr>
        </table>
        
        <div>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align: bottom; padding-bottom: 1;"><h2>ส่วนงาน</h2></td>
                    <td colspan="2" class="txt">&nbsp;&nbsp;' . $unit_name . '&nbsp;&nbsp;' . $job_name . ' โทร ๔๔๓๑๙</td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align: bottom; padding-bottom: 1;"><h2>ที่</h2></td>
                    <td class="txt">&nbsp;&nbsp;อว ๘๓๙๓(๙).๑.๑.๒/-</td>
                    <td style="vertical-align: bottom; padding-bottom: 1;"><h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่</h2></td>
                    <td class="txt">&nbsp;&nbsp;' . $current_date . '</td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align: bottom; padding-bottom: 1;"><h2>เรื่อง</h2></td>
                    <td class="txt">&nbsp;&nbsp;แจ้งผลการประเมินการปฏิบัติงานของลูกจ้างประจำ</td>
                </tr>
            </table>
        </div><br>
        <div>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align: bottom; padding-bottom: 1;"><h2>เรียน</h2></td>
                    <td class="txt">&nbsp;&nbsp;' . $staff_name . '</td>
                </tr>
            </table>
        </div><br>
        <div>
            <table cellpadding="0" cellspacing="0" style="width:100%">
                <tr>
                    <td style="width:15%"></td>
                    <td class="txt">ตามที่คณะกรรมการฯ ได้ประเมินผลการปฺฏิบัติงานของท่าน เรียบร้อยแล้วนั้น ข้าพเจ้าในฐานะ</td>
                </tr>
                <tr>
                    <td class="txt" colspan="2">ประธานคณะกรรมการฯ ขอแจ้งประเมินผลการปฏิบัติงานของท่าน ดังนี้.-</td>
                </tr>
            </table>
        </div><br>
        <div>
            <table cellpadding="0" cellspacing="0" style="width:100%">
                <tr>
                    <td style="width:15%"></td>
                    <td class="txt" style="width:25%">ผลการประเมินอยู่ในระดับ</td>
                    <td class="txt">&nbsp;&nbsp;<input type="checkbox">  ดีเด่น</td>
                </tr>
                <tr>
                    <td class="txt"></td>
                    <td class="txt"></td>
                    <td class="txt">&nbsp;&nbsp;<input type="checkbox">  เป็นที่ยอมรับได้</td>
                </tr>
                <tr>
                    <td class="txt"></td>
                    <td class="txt"></td>
                    <td class="txt">&nbsp;&nbsp;<input type="checkbox">  ต้องปรับปรุ่ง</td>
                </tr>
            </table>
        </div>
        <div class="txt">
            ข้อเสนอแนะของคณะกรรมการ<br>
            .....................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................
        </div><br>
        <table cellpadding="0" cellspacing="0" style="width:100%">
                <tr>
                    <td style="width:15%"></td>
                    <td class="txt">จึงเรียนมาเพื่อทราบ</td>
                </tr>
                <tr>
                    <td class="txt" colspan="2"></td>
                </tr>
            </table><br><br>
        <table cellpadding="0" cellspacing="0" style="width:100%">
                <tr>
                    <td style="width:40%"></td>
                    <td class="txt" style="text-align:center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                </tr>
                <tr>
                    <td style="width:40%"></td>
                    <td class="txt" style="text-align:center;">ประธานคณะกรรมการประเมินฯ</td>
                </tr>
        </table>
    </body>
    </html>';

    $mpdf->AddPage();
    $mpdf->WriteHTML($html);
}

$filename = 'evaluation_reports.pdf';
$mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
?>
