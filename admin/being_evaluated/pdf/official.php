<?php
require_once __DIR__ . '../../../../vendor/autoload.php';
include("../../../fn/config.php");
include("../../../fn/session.php");
$year = isset($_GET['year']) ? $_GET['year'] : date("Y") + 543;
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

$sql = "SELECT * FROM estimate_score WHERE ests_year=$year AND duty_code <= '3'";
$query = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_array($query)) {
    $status = $row["status"];
    $estimate_score_year = $row["ests_year"];
    $oldyear = $estimate_score_year - 1;

    $date_c = $row["date_complete"];
    $date = new DateTime($date_c);
    $date_complete = $date->format('d/m/') . ($date->format('Y') + 543);

    $dean_status = $row['dean_status'];

    $round = $row['round'];

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

    $html = '
    <!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <title>คะแนนปี'.$year.'ของพนักงานข้าราชการ</title>
        <style>
            body {
                font-family: "thsarabun";
            }
            .section { 
                margin-bottom: 1%;
                margin-top: 1%;
            }
            h2 { 
                text-align: center; 
            }
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            table {
                margin-top: 1%;
            }
            .tdnoline {
                border: none;
            }
            .tdnolinebuthaveright {
                border-right: 1px solid black;
            }
            .tdlast {
                border-bottom: 1px solid black;
            }
            * {
                font-weight: 300;
            }
            .tdnolinebuthaveright2 {
                border-right: 1px solid black;
                border-left: 1px solid black;
                border-bottom: 1px solid black;
            }
            .nonlinebu {
                border-bottom: none;
            }
            .nonlinebutop {
                border-bottom: none;
                border-top: none;
            }
            .tdlast2 {
                border-top: none;
            }
        </style>
        <script src="https://kit.fontawesome.com/8b3618d419.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <h2>แบบแจ้งผลการประเมินปฏิบัติงานข้าราชการพลเรือนในสถาบันอุดมศึกษา</h2>
        <div class="section">
            <strong>ส่วนที่ 1 : ข้อมูลของผู้รับการประเมิน<br></strong>
            ชื่อผู้รับการประเมิน ' . $staff_name . ' ตำแหน่ง ' . $position_name . '<br>
            ตำแหน่งเลขที่ ' . $staff_code . ' สังกัด ' . $dep_name . '
        </div>
        <div class="section">
            <strong>ส่วนที่ 2 : การประเมินผลการปฏิบัติงาน<br></strong>
            ประเมินครั้งที่ '.$round.' ปีงบประมาณ ';
            if ($round == 1) {
                $html .= ' '. $estimate_score_year .'  ผลงานตั้งแต่ 1 มิถุนายน '. $oldyear .' - 30 พฤษภาคม '. $estimate_score_year .' ';
            } else if ($round == 2) {
                $html .= ' '. $estimate_score_year .'  ผลงานตั้งแต่ 1 ธันวาคม '. $oldyear .' - 30 พฤษภาคม '. $estimate_score_year .' ';
            }
            
        $html .='</div>
        <div>
            <strong>ส่วนที่ 3 : การสรุปผลการประเมิน</strong>
            <table class="tdnoline tdnolinebuthaveright" style="width:100%">
                <tr>
                    <th>องค์ประกอบการประเมิน</th>
                    <th>น้ำหนัก</th>
                    <th>คะแนน<br>ที่ได้</th>
                    <th>ร้อยละผลการ<br>ประเมิน</th>
                    <th>ระดับผลการประเมิน</th>
                </tr>
                <tr>
                    <td>1. ด้านผลงาน/ผลการปฏิบัติงาน</td>
                    <td style="text-align:center">70</td>
                    <td style="text-align:center">' . $performance . '</td>
                    <td style="text-align:center">' . number_format($percentage_performance, 2) . '</td>
                    <td class="tdnoline">';
                        if ($score >= 90) {
                            $html .= '<input type="checkbox" checked="checked">';
                        } else {
                            $html .= '<input type="checkbox">';
                        }
                        $html .= '<label>ดีเยี่ยม ได้รับคะแนนร้อยละ 90-100</label><br>
                    </td>
                </tr>
                <tr>
                    <td>2. พฤติกรรมการปฏิบัติงาน</td>
                    <td style="text-align:center">30</td>
                    <td style="text-align:center">' . $behavior . '</td>
                    <td style="text-align:center">' . number_format($percentage_behavior, 2) . '</td>
                    <td class="tdnoline">';
                        if ($score >= 80 && $score < 90) {
                            $html .= '<input type="checkbox" checked="checked">';
                        } else {
                            $html .= '<input type="checkbox">';
                        }
                        $html .= '<label>ดีมาก ได้รับคะแนนร้อยละ 80 แต่ไม่ถึง 90</label><br>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center"><strong>รวมคะแนน</strong></td>
                    <td style="text-align:center">100</td>
                    <td style="text-align:center">' . $score . '</td>
                    <td></td>
                    <td class="tdnoline">';
                        if ($score >= 70 && $score < 80) {
                            $html .= '<input type="checkbox" checked="checked">';
                        } else {
                            $html .= '<input type="checkbox">';
                        }
                        $html .= '<label>ดี ได้รับคะแนนร้อยละ 70 แต่ไม่ถึง 80</label><br>
                    </td>
                </tr>
                <tr class="tdnoline">
                    <td class="tdnoline"></td>
                    <td class="tdnoline"></td>
                    <td class="tdnoline"></td>
                    <td class="tdnoline"></td>
                    <td class="">';
                        if ($score >= 60 && $score < 70) {
                            $html .= '<input type="checkbox" checked="checked">';
                        } else {
                            $html .= '<input type="checkbox">';
                        }
                        $html .= '<label>พอใช้ ได้รับคะแนนร้อยละ 60 แต่ไม่ถึง 70</label><br>
                    </td>
                </tr>
                <tr class="tdlast">
                    <td class="tdnoline"></td>
                    <td class="tdnoline"></td>
                    <td class="tdnoline"></td>
                    <td class="tdnoline"></td>
                    <td class="tdlast2">';
                        if ($score <  60) {
                            $html .= '<input type="checkbox" checked="checked">';
                        } else {
                            $html .= '<input type="checkbox">';
                        }
                        $html .= '<label>ปรับปรุง ได้รับคะแนนต่ำกว่าร้อยละ 60</label><br>
                    </td>
                </tr>
            </table>
        </div>
        <div class="section">
            กรณีข้าราชกาลพลเรือนในสถาบันอุดมศึกษา ไม่ผ่านเกณฑ์การประเมิน โดยถูกยุติการปฏิบัติงานหรือโดยถูกให้เลิกจ้างให้มีสิทธิร้องทุกข์ ต่อ ก.อ.ร. ได้ภายใน 30 วันนับตั้งแต่วันถัดจากวันที่ได้รับทราบ หรือถือว่าทราบเหตุแห่งการร้องทุกข์
        </div>
        <div class="section">
            <strong>ส่วนที่ 4 : แผนพัฒนาการปฏิบัติงาน</strong>
            <table class="tdnoline tdnolinebuthaveright" style="width:100%">
                <tr>
                    <th style="width:40%">ความรู้/ทักษะ ที่ต้องได้รับการพัฒนา</th>
                    <th style="width:40%">วิธีการพัฒนา</th>
                    <th style="width:20%">ช่วงเวลาที่ต้องการพัฒนา</th>
                </tr>
                <tr>
                    <td><br></td>
                    <td><br></td>
                    <td><br></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <strong>ส่วนที่ 5 : การรับทราบผลการประเมิน</strong>
            <table class="tdnolinebuthaveright2" style="width:100%">
                <tr> 
                    <td style="width:50%" class="nonlinebu">
                        <strong>ผู้รับการประเมิน</strong><br>';
                        if ($status == 'complete') {
                            $html .= '<input type="checkbox" checked="checked">';
                        } else {
                            $html .= '<input type="checkbox">';
                        }
                        $html .= ' ได้รับทราบผลการประเมิน<br>
                    </td>
                    <td style="vertical-align: top;" class="nonlinebu">
                        <strong>ผู้แจ้งผลการประเมิน</strong><br>';
                        if ($dean_status == 'complete' && $status == 'complete') {
                            $html .= '<input type="checkbox" checked="checked">';
                        } else {
                            $html .= '<input type="checkbox">';
                        }
                        $html .= ' ได้แจ้งผลการประเมินและผู้รับการประเมินได้รับทราบแล้ว<br>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%" style="text-align:center" class="nonlinebutop">
                        ลงชื่อ..................................................... <br>
                        ( ' . $staff_name . ' )<br>
                        ตำแหน่ง ' . $position_name . '<br>
                        วันที่ ' . $date_complete . '
                    </td>
                    <td style="text-align:center" class="nonlinebutop">
                        ลงชื่อ.....................................................<br>
                        (ศ.ปฏิบัติ ดร.ภก.สุพัฒน์ จิรานุสรณ์กุล)<br>
                        ตำแหน่ง คณบดีคณะเภสัชศาสตร์<br>
                        วันที่ ' . $dean_date . '<br>
                    </td>
                </tr>
                <tr>
                    <td class="nonlinebutop"></td>
                    <td class="nonlinebutop">
                        <input type="checkbox">
                        ได้แจ้งผลการประเมิน เมื่อวันที่ .................................<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แต่ผู้รับการประเมินไม่ลงนามรับทราบผลประเมิน<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;โดยมี.......................................................เป็นพยาน<br><br>
                    </td>
                </tr>
                <tr>
                    <td class="nonlinebutop"></td>
                    <td style="text-align:center" class="nonlinebutop">
                        ลงชื่อ......................................................................พยาน<br>
                        (...............................................................)<br>
                        ตำแหน่ง.................................................................................<br>
                        วันที่..................................................................................<br>
                    </td>
                </tr>
            </table>
        </div>
    </body>
    </html>
    ';

    $mpdf->AddPage();
    $mpdf->WriteHTML($html);
}

$filename = 'evaluation_reports.pdf';
$mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
?>
