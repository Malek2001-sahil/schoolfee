<?php
require_once('class/pdf.php');
include('database_connection.php');

if (isset($_POST["student_id"], $_POST["purpose"], $_POST["dob"], $_POST["caste"])) {
    $student_id = $_POST["student_id"];
    $purpose = htmlspecialchars($_POST["purpose"]);
    $dob = date("d-m-Y", strtotime($_POST["dob"]));
    $caste = htmlspecialchars($_POST["caste"]);

    // Get student info
    $query = "
    SELECT s.student_name, s.student_number, 
           std.acedemic_standard_name, std.acedemic_standard_division
    FROM sfms_student s
    JOIN sfms_student_standard ss ON ss.student_id = s.student_id
    JOIN sfms_acedemic_standard std ON std.acedemic_standard_id = ss.acedemic_standard_id
    WHERE s.student_id = '$student_id' 
    AND ss.student_standard_status = 'Enable'
    LIMIT 1
    ";

    $result = $connect->query($query, PDO::FETCH_ASSOC);
    $student = $result->fetch();

    if (!$student) {
        die("Student not found.");
    }

    $today = date("d-m-Y");

    // Certificate HTML
    $html = '
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        .cert-box { border: 2px solid #000; padding: 30px; width: 80%; margin: auto; }
        h2 { text-align: center; }
        p { font-size: 14px; }
        .footer { margin-top: 50px; text-align: right; padding-right: 50px; }
    </style>

    <div class="cert-box">
        <h2>Bonafide Certificate</h2>
        <p>This is to certify that <strong>' . $student["student_name"] . '</strong>, 
        bearing Student No. <strong>' . $student["student_number"] . '</strong>, 
        is a bonafide student of our institution.</p>

        <p>He/She is studying in <strong>' . $student["acedemic_standard_name"] . ' - ' . $student["acedemic_standard_division"] . '</strong>.</p>

        <p>His/Her Date of Birth is <strong>' . $dob . '</strong> and Caste is <strong>' . $caste . '</strong>.</p>

        <p>This certificate is issued upon his/her request for the purpose of <strong>' . $purpose . '</strong>.</p>

        <div class="footer">
            <p>Date: ' . $today . '</p>
            <p><strong>Principal</strong></p>
        </div>
    </div>
    ';

    $pdf = new Pdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf->stream("bonafide_certificate.pdf", array("Attachment" => false));
} else {
    echo "Missing required fields.";
}
?>
