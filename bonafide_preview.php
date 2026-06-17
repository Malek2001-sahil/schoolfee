<?php
include('database_connection.php');

if (!is_login()) {
    header('location:login.php');
}

if (!isset($_POST['student_id']) || !isset($_POST['purpose'])) {
    die("Invalid request");
}

$student_id = $_POST['student_id'];
$purpose = htmlspecialchars($_POST['purpose']);

// Get student info
$query = "
SELECT s.student_name, s.student_number, s.student_dob, 
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
    die("Student not found");
}

$today = date("d-m-Y");
$dob = date("d-m-Y", strtotime($student["student_dob"]));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bonafide Certificate - Preview</title>
    <style>
        body { font-family: sans-serif; margin: 50px; }
        .certificate-box {
            border: 2px solid #000;
            padding: 30px;
            max-width: 800px;
            margin: auto;
        }
        h2, h4 { text-align: center; margin-bottom: 30px; }
        p { font-size: 16px; line-height: 1.6; }
        .footer { margin-top: 50px; text-align: right; padding-right: 50px; }
        .print-btn {
            text-align: center;
            margin-top: 30px;
        }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

<div class="certificate-box">
    <h2>Bonafide Certificate</h2>

    <p>This is to certify that <strong><?php echo $student["student_name"]; ?></strong>, 
    bearing Student No. <strong><?php echo $student["student_number"]; ?></strong>, 
    is a bonafide student of our institution.</p>

    <p>He/She is currently studying in <strong><?php echo $student["acedemic_standard_name"]; ?> - <?php echo $student["acedemic_standard_division"]; ?></strong>.</p>

    <p>Date of Birth: <strong><?php echo $dob; ?></strong></p>

    <p>This certificate is issued upon his/her request for the purpose of 
    <strong><?php echo $purpose; ?></strong>.</p>

    <div class="footer">
        <p>Date: <?php echo $today; ?></p>
        <p><strong>Principal</strong></p>
    </div>
</div>

<div class="print-btn">
    <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px;">🖨 Print Certificate</button>
</div>

</body>
</html>
