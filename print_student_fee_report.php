<?php
require 'vendor/autoload.php'; // this loads Dompdf

use Dompdf\Dompdf;
include('database_connection.php');

if (!isset($_GET['id'])) {
    die('Student ID required.');
}

$student_id = $_GET['id'];
$stmt = $connect->prepare("SELECT * FROM sfms_student WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    die('Student not found.');
}

$total_paid = 0;
$total_due = 0;

$html = '
<style>
body { font-family: sans-serif; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
th { background: #eee; }
</style>
<h2>Fee Report</h2>
<p><b>Name:</b> '.$student['student_name'].'<br>
<b>Student No:</b> '.$student['student_number'].'</p>
<table>
<thead>
<tr>
<th>Month</th>
<th>Status</th>
<th>Amount</th>
</tr>
</thead>
<tbody>';

$stmt2 = $connect->prepare("
    SELECT f.fees_id, f.fees_month, f.fees_data 
    FROM sfms_fees f
    INNER JOIN sfms_student_standard ss 
    ON ss.acedemic_standard_id = f.acedemic_standard_id 
    AND ss.acedemic_year_id = f.acedemic_year_id
    WHERE ss.student_id = ?
    ORDER BY f.fees_id ASC
");
$stmt2->execute([$student_id]);

foreach ($stmt2->fetchAll() as $fee) {
    $amount = 0;
    $data = json_decode($fee['fees_data'], true);
    foreach ($data as $f) {
        $amount += $f['fees_value'];
    }

    $check = $connect->prepare("SELECT fees_paid_id FROM sfms_fees_paid WHERE student_id = ? AND fees_id = ?");
    $check->execute([$student_id, $fee['fees_id']]);
    $paid = $check->rowCount() > 0;

    $html .= '<tr>
        <td>'.$fee['fees_month'].'</td>
        <td>'.($paid ? 'Paid' : 'Not Paid').'</td>
        <td>₹ '.$amount.'</td>
    </tr>';

    if ($paid) $total_paid += $amount;
    else $total_due += $amount;
}

$html .= '</tbody>
<tfoot>
<tr><th colspan="2">Total Paid</th><th>₹ '.$total_paid.'</th></tr>
<tr><th colspan="2">Total Pending</th><th>₹ '.$total_due.'</th></tr>
</tfoot>
</table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("student_fee_report.pdf", ["Attachment" => false]);
exit;
