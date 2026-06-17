<?php
include('database_connection.php');

if (!is_login()) {
    header("location:login.php");
}

include('header.php');

$report_data = '';
$total = 0;
$date = '';

if (isset($_POST['report_date'])) {
    $date = $_POST['report_date'];
    
    $start = strtotime($date . " 00:00:00");
    $end = strtotime($date . " 23:59:59");

    $query = "
    SELECT p.fees_paid_on, s.student_name, s.student_number, 
           std.acedemic_standard_name, std.acedemic_standard_division, 
           f.fees_month, f.fees_data,
           a.admin_name
    FROM sfms_fees_paid p
    INNER JOIN sfms_student s ON s.student_id = p.student_id
    INNER JOIN sfms_fees f ON f.fees_id = p.fees_id
    INNER JOIN sfms_acedemic_standard std ON std.acedemic_standard_id = p.acedemic_standard_id
    INNER JOIN sfms_admin a ON a.admin_id = p.fees_received_by
    WHERE p.fees_paid_on BETWEEN '$start' AND '$end'
    ORDER BY p.fees_paid_on ASC
    ";

    $result = $connect->query($query, PDO::FETCH_ASSOC);

    if ($result->rowCount() > 0) {
        $report_data = '<table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Standard</th>
                    <th>Fees Month</th>
                    <th>Total Amount (₹)</th>
                    <th>Received By</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($result as $row) {
            $fees_data = json_decode($row["fees_data"], true);
            $fee_amount = 0;
            foreach ($fees_data as $item) {
                $fee_amount += $item["fees_value"];
            }
            $total += $fee_amount;

            $report_data .= '
            <tr>
                <td>' . date('d-m-Y H:i', $row["fees_paid_on"]) . '</td>
                <td>' . $row["student_name"] . ' (' . $row["student_number"] . ')</td>
                <td>' . $row["acedemic_standard_name"] . ' - ' . $row["acedemic_standard_division"] . '</td>
                <td>' . $row["fees_month"] . '</td>
                <td>₹' . number_format($fee_amount, 2) . '</td>
                <td>' . $row["admin_name"] . '</td>
            </tr>';
        }

        $report_data .= '
            <tr>
                <td colspan="4" align="right"><strong>Grand Total</strong></td>
                <td colspan="2"><strong>₹' . number_format($total, 2) . '</strong></td>
            </tr>
            </tbody>
        </table>';
    } else {
        $report_data = '<div class="alert alert-warning">No records found for selected date.</div>';
    }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Daily Fees Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Fees Report</li>
    </ol>

    <form method="post" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>Select Date</label>
                <input type="date" name="report_date" class="form-control" value="<?php echo $date; ?>" required />
            </div>
            <div class="col-md-2 pt-4">
                <input type="submit" class="btn btn-primary mt-2" value="Get Report" />
            </div>
        </div>
    </form>

    <?php
if (!empty($report_data)) {
    echo $report_data;
    ?>
    <form method="post" action="export_daily_fees_report_pdf.php" target="_blank">
        <input type="hidden" name="report_html" value='<?php echo htmlspecialchars($report_data, ENT_QUOTES); ?>'>
        <button type="submit" class="btn btn-danger mt-3">
            <i class="fas fa-file-pdf"></i> Export to PDF
        </button>
    </form>
    <?php
} // ✅ This was missing
?>
</div>

<?php include('footer.php'); ?>
