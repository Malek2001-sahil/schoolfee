
<?php
include('database_connection.php');

if (!is_login()) {
    header('location:login.php');
}

$message = '';
$error = '';
$output = '';

$summary = [];

if (isset($_POST['acedemic_year_id'])) {
    $year_id = $_POST['acedemic_year_id'];
    $class_id = $_POST['acedemic_standard_id'];

    // Build base query
    $query = "
    SELECT f.fees_month, f.fees_data, 
           std.acedemic_standard_name, std.acedemic_standard_division
    FROM sfms_fees f
    INNER JOIN sfms_acedemic_standard std 
        ON std.acedemic_standard_id = f.acedemic_standard_id
    WHERE f.acedemic_year_id = '$year_id' 
    AND f.fees_status = 'Enable'
    ";

    if (!empty($class_id)) {
        $query .= " AND f.acedemic_standard_id = '$class_id'";
    }

    $query .= " ORDER BY f.fees_month ASC";

    $result = $connect->query($query, PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        $month = $row["fees_month"];
        $standard = $row["acedemic_standard_name"] . ' - ' . $row["acedemic_standard_division"];
        $fees_data = json_decode($row["fees_data"], true);

        $total = 0;
        foreach ($fees_data as $fee_row) {
            $total += $fee_row["fees_value"];
        }

        $summary[] = [
            'month' => $month,
            'standard' => $standard,
            'amount' => $total
        ];
    }

    if (count($summary) > 0) {
        $output .= '
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Fees Month</th>
                    <th>Class (Standard)</th>
                    <th>Total Fees (₹)</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($summary as $row) {
            $output .= '<tr>
                <td>' . $row["month"] . '</td>
                <td>' . $row["standard"] . '</td>
                <td>₹' . number_format($row["amount"], 2) . '</td>
            </tr>';
        }

        $output .= '</tbody></table>';
    } else {
        $output = '<div class="alert alert-info">No fees data found.</div>';
    }
}
?>

<?php include('header.php'); ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Fees Summary by Academic Year and Class</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Fees Summary</li>
    </ol>

    <form method="post" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>Select Academic Year <span class="text-danger">*</span></label>
                <select name="acedemic_year_id" class="form-control" required>
                    <option value="">Select Academic Year</option>
                    <?php echo Academic_list_data($connect); ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Select Standard (Optional)</label>
                <select name="acedemic_standard_id" class="form-control">
                    <option value="">All Classes</option>
                    <?php echo Academic_standard_list_data($connect); ?>
                </select>
            </div>
            <div class="col-md-2 pt-4">
                <input type="submit" name="view_fees_summary" class="btn btn-primary mt-2" value="Get Data" />
            </div>
        </div>
    </form>

    <?php
    if (isset($_POST["view_fees_summary"])) {
        echo $output;
    }
    ?>
</div>

<?php include('footer.php'); ?>
