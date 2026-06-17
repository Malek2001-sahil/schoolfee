<?php
// fees_paid_edit.php

include('database_connection.php');
include('header.php');

if (!is_login()) {
    header('location:login.php');
}

if (!isset($_GET["id"])) {
    die("Invalid Request");
}

$fees_paid_id = $_GET["id"];
$error = '';
$success = '';

$query = "
SELECT * FROM sfms_fees_paid 
WHERE fees_paid_id = '$fees_paid_id'
LIMIT 1
";
$result = $connect->query($query, PDO::FETCH_ASSOC);
$row = $result->fetch();

if (!$row) {
    die("Receipt not found.");
}

if (isset($_POST['update_fee'])) {
    $data = [
        ':fees_paid_id' => $fees_paid_id,
        ':student_id' => $_POST['student_id'],
        ':fees_id' => $_POST['fees_id'],
        ':acedemic_year_id' => $_POST['acedemic_year_id'],
        ':acedemic_standard_id' => $_POST['acedemic_standard_id']
    ];

    $update = "
        UPDATE sfms_fees_paid SET 
        student_id = :student_id, 
        fees_id = :fees_id, 
        acedemic_year_id = :acedemic_year_id, 
        acedemic_standard_id = :acedemic_standard_id 
        WHERE fees_paid_id = :fees_paid_id
    ";

    $stmt = $connect->prepare($update);
    $stmt->execute($data);

    $success = "Fees receipt updated successfully.";
    // Refresh data
    $result = $connect->query($query, PDO::FETCH_ASSOC);
    $row = $result->fetch();
}

?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Fee Receipt</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="fees_paid.php">Fees Received</a></li>
        <li class="breadcrumb-item active">Edit Receipt</li>
    </ol>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Academic Year</label>
            <select name="acedemic_year_id" class="form-control" required>
                <?php
                echo Academic_list_data($connect, $row["acedemic_year_id"]);
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Standard</label>
            <select name="acedemic_standard_id" class="form-control" required>
                <?php
                echo Academic_standard_list_data($connect, $row["acedemic_standard_id"]);
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Student</label>
            <select name="student_id" class="form-control" required>
                <?php
                $students = $connect->query("SELECT * FROM sfms_student ORDER BY student_name ASC", PDO::FETCH_ASSOC);
                foreach ($students as $stu) {
                    $sel = ($stu['student_id'] == $row['student_id']) ? 'selected' : '';
                    echo "<option value='{$stu['student_id']}' $sel>{$stu['student_name']} ({$stu['student_number']})</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Fees Month</label>
            <select name="fees_id" class="form-control" required>
                <?php
                $fees = $connect->query("SELECT * FROM sfms_fees WHERE fees_status = 'Enable' ORDER BY fees_month ASC", PDO::FETCH_ASSOC);
                foreach ($fees as $f) {
                    $sel = ($f['fees_id'] == $row['fees_id']) ? 'selected' : '';
                    echo "<option value='{$f['fees_id']}' $sel>{$f['fees_month']}</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" name="update_fee" class="btn btn-primary">Update Receipt</button>
        <a href="fees_paid.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<?php include('footer.php'); ?>
