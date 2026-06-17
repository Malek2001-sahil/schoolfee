<?php
include('database_connection.php');
include('functions.php');
include('header.php');

if (!is_login()) {
    header('location:login.php');
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">📊 Dashboard Summary</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Overview</li>
    </ol>

    <div class="row">
        <div class="col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4>Today’s Fees</h4>
                    <h2>₹<?= number_format(get_today_fees($connect), 2) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4>This Month’s Fees</h4>
                    <h2>₹<?= number_format(get_monthly_fees($connect), 2) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <h4>🏫 Class-wise Fees Collection (Total)</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Class</th>
                <th>Total Fees Collected (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $fees_by_class = get_total_fees_per_class($connect);
            foreach ($fees_by_class as $class => $amount): ?>
                <tr>
                    <td><?= $class ?></td>
                    <td>₹<?= number_format($amount, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h4 class="mt-5">👨‍🎓 Students Per Class</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Class</th>
                <th>Number of Students</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $students_per_class = get_students_per_class($connect);
            foreach ($students_per_class as $row): ?>
                <tr>
                    <td><?= $row['acedemic_standard_name'] . ' - ' . $row['acedemic_standard_division'] ?></td>
                    <td><?= $row['student_count'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
