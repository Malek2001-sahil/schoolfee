<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET["action"], $_GET["code"]) && $_GET["action"] == 'pdf') {

    include('database_connection.php');
    require_once('class/pdf.php');

    $code = $_GET['code'];

    // Get School Info
    $school = $connect->query("SELECT * FROM sfms_setting LIMIT 1")->fetch(PDO::FETCH_ASSOC);

    // Get Fee Data
    $stmt = $connect->prepare("
        SELECT s.student_id, s.student_number, s.student_name,
               y.acedemic_start_year, y.acedemic_start_month, y.acedemic_end_year, y.acedemic_end_month,
               f.fees_month, f.fees_data,
               std.acedemic_standard_name, std.acedemic_standard_division,
               a.admin_name, p.fees_paid_on,
               p.acedemic_year_id, p.acedemic_standard_id
        FROM sfms_fees_paid p
        INNER JOIN sfms_student s ON s.student_id = p.student_id
        INNER JOIN sfms_acedemic_year y ON y.acedemic_year_id = p.acedemic_year_id
        INNER JOIN sfms_fees f ON f.fees_id = p.fees_id
        INNER JOIN sfms_acedemic_standard std ON std.acedemic_standard_id = p.acedemic_standard_id
        INNER JOIN sfms_admin a ON a.admin_id = p.fees_received_by
        WHERE p.fees_paid_id = :code
    ");
    $stmt->execute(['code' => $code]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$data) exit("Data not found.");

    // Total Academic Fees
    $total_fees = 0;
    $fees_result = $connect->prepare("
        SELECT fees_data FROM sfms_fees 
        WHERE acedemic_year_id = :year_id AND acedemic_standard_id = :std_id AND fees_status = 'Enable'
    ");
    $fees_result->execute([
        'year_id' => $data['acedemic_year_id'],
        'std_id'  => $data['acedemic_standard_id']
    ]);
    foreach ($fees_result->fetchAll() as $row) {
        $items = json_decode($row['fees_data'], true);
        foreach ($items as $item) $total_fees += $item['fees_value'];
    }

    // Total Paid
    $total_paid = 0;
    $paid_result = $connect->prepare("
        SELECT f.fees_data FROM sfms_fees_paid p 
        INNER JOIN sfms_fees f ON f.fees_id = p.fees_id 
        WHERE p.student_id = :student_id AND p.acedemic_year_id = :year_id AND p.acedemic_standard_id = :std_id
    ");
    $paid_result->execute([
        'student_id' => $data['student_id'],
        'year_id' => $data['acedemic_year_id'],
        'std_id' => $data['acedemic_standard_id']
    ]);
    foreach ($paid_result->fetchAll() as $row) {
        $items = json_decode($row['fees_data'], true);
        foreach ($items as $item) $total_paid += $item['fees_value'];
    }

    $pending_amount = $total_fees - $total_paid;

    // Copy Template
    function makeCopy($title, $data, $school, $pending_amount) {
        $fees = json_decode($data['fees_data'], true);
        $rows = '';
        $total = 0;
        foreach ($fees as $f) {
            $rows .= "<tr><td>{$f['fees_name']}</td><td style='text-align:right;'>&#8377; ".number_format($f['fees_value'], 2)."</td></tr>";
            $total += $f['fees_value'];
        }

        return "
        <div class='receipt'>
            <h3 style='text-align:center; margin: 0 0 5px;'>{$school['school_name']}</h3>
            <p style='text-align:center; margin:0; font-size:10px;'>{$school['school_address']}</p>
            <p style='text-align:center; font-size:10px; margin:0 0 5px;'>
                Phone: {$school['school_contact_number']} | Email: {$school['school_email_address']}
            </p>
            <h4 style='text-align:center; margin:0 0 5px;'>$title</h4>
            <p><b>Date:</b> " . date('d/m/Y H:i:s', $data['fees_paid_on']) . "</p>
            <table class='info'>
                <tr><th>Student Number</th><td>{$data['student_number']}</td></tr>
                <tr><th>Student Name</th><td>{$data['student_name']}</td></tr>
                <tr><th>Standard</th><td>{$data['acedemic_standard_name']} - {$data['acedemic_standard_division']}</td></tr>
                <tr><th>Academic Year</th><td>{$data['acedemic_start_month']} {$data['acedemic_start_year']} - {$data['acedemic_end_month']} {$data['acedemic_end_year']}</td></tr>
                <tr><th>Fees Month</th><td>{$data['fees_month']}</td></tr>
            </table>
            <h4>Fee Details</h4>
            <table class='fees'>
                <tr><th>Fee</th><th style='text-align:right;'>Amount</th></tr>
                $rows
                <tr><th>Total</th><th style='text-align:right;'>&#8377; ".number_format($total, 2)."</th></tr>
                <tr><th>Pending</th><th style='text-align:right;'>&#8377; ".number_format($pending_amount, 2)."</th></tr>
            </table>
            <p style='text-align:right;'><b>Received By:</b> {$data['admin_name']}</p>
        </div>
        ";
    }

    // CSS + Combine
    $html = '
    <style>
        body { font-family: sans-serif; font-size: 10px; margin: 0; padding: 0; }
        .wrapper { display: flex; flex-wrap: wrap; justify-content: space-between; }
        .receipt {
            width: 48%;
            box-sizing: border-box;
            padding: 8px;
            border: 1px dashed #000;
            margin-bottom: 10px;
        }
        table { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 5px; }
        th, td { padding: 4px; border: 1px solid #000; }
        .fees th, .fees td { text-align: left; }
    </style>

    <div class="wrapper">
        ' . makeCopy("STUDENT COPY", $data, $school, $pending_amount) . '
        ' . makeCopy("OFFICE COPY", $data, $school, $pending_amount) . '
    </div>
    ';

    $pdf = new Pdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf->stream("fees_slip_{$data['student_number']}.pdf", array("Attachment" => false));
    exit;

} else {
    header("Location: fees_paid.php");
}
