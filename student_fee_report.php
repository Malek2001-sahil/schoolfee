<?php
include('database_connection.php');
include('header.php');

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Student ID missing.</div>";
    include('footer.php');
    exit;
}

$student_id = $_GET['id'];
$stmt = $connect->prepare("SELECT * FROM sfms_student WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    echo "<div class='alert alert-danger'>Student not found.</div>";
    include('footer.php');
    exit;
}

$total_paid = 0;
$total_due = 0;
?>

<div class="container mt-4">
    <h3>Fee Report for <?php echo $student['student_name']; ?> (<?php echo $student['student_number']; ?>)</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Month</th>
                <th>Status</th>
                <th>Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php
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

                echo '<tr>
                    <td>'.$fee['fees_month'].'</td>
                    <td>'.($paid ? "<span class='badge bg-success'>Paid</span>" : "<span class='badge bg-danger'>Not Paid</span>").'</td>
                    <td>₹ '.$amount.'</td>
                </tr>';

                if ($paid) $total_paid += $amount;
                else $total_due += $amount;
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total Paid</th>
                <th>₹ <?php echo $total_paid; ?></th>
            </tr>
            <tr>
                <th colspan="2">Total Pending</th>
                <th>₹ <?php echo $total_due; ?></th>
            </tr>
        </tfoot>
    </table>

    <a href="print_student_fee_report.php?id=<?php echo $student_id; ?>" class="btn btn-primary" target="_blank">Print PDF</a>
</div>

<?php include('footer.php'); ?>
