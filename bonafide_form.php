<?php
include('database_connection.php');
include('header.php');

if (!is_login()) {
    header("location:login.php");
}

$student_list = $connect->query("SELECT * FROM sfms_student ORDER BY student_name ASC", PDO::FETCH_ASSOC);
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Bonafide Certificate Generator</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Bonafide Certificate</li>
    </ol>

    <form method="post" action="bonafide_generate.php" target="_blank">
    <div class="mb-3">
        <label>Select Student <span class="text-danger">*</span></label>
        <select name="student_id" class="form-control" required>
            <option value="">Select Student</option>
            <?php
            $student_list = $connect->query("SELECT * FROM sfms_student ORDER BY student_name ASC", PDO::FETCH_ASSOC);
            foreach ($student_list as $student) {
                echo '<option value="' . $student['student_id'] . '">' . $student['student_name'] . ' (' . $student['student_number'] . ')</option>';
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Date of Birth <span class="text-danger">*</span></label>
        <input type="date" name="dob" class="form-control" required />
    </div>

    <div class="mb-3">
        <label>Caste <span class="text-danger">*</span></label>
        <input type="text" name="caste" class="form-control" placeholder="e.g. General / SC / OBC" required />
    </div>

    <div class="mb-3">
        <label>Purpose <span class="text-danger">*</span></label>
        <input type="text" name="purpose" class="form-control" placeholder="e.g. Passport, Scholarship" required />
    </div>

    <button type="submit" class="btn btn-success">Generate Certificate</button>
</form>
</div>

<?php include('footer.php'); ?>
