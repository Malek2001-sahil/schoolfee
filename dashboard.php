<?php
include('database_connection.php');
include('functions.php'); // contains helper functions
include('header.php');

if (!is_login()) {
    header('location:login.php');
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Overview</li>
    </ol>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4>Today’s Fees</h4>
                    <h2>₹<?= number_format(get_today_fees($connect), 2) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4>This Month’s Fees</h4>
                    <h2>₹<?= number_format(get_monthly_fees($connect), 2) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <h4>📊 Monthly Collection (Last 6 Months)</h4>
    <canvas id="monthlyChart" height="100"></canvas>

    <h4 class="mt-5">🏫 Today’s Class-wise Fees</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Class</th>
                <th>Total Collected (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (get_class_wise_today_fees($connect) as $class => $amount): ?>
                <tr>
                    <td><?= $class ?></td>
                    <td>₹<?= number_format($amount, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch("fees_chart_data.php")
    .then(res => res.json())
    .then(data => {
        new Chart(document.getElementById("monthlyChart").getContext("2d"), {
            type: "bar",
            data: {
                labels: data.months,
                datasets: [{
                    label: "Fees Collected",
                    data: data.amounts,
                    backgroundColor: "rgba(75,192,192,0.7)"
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>

<?php include('footer.php'); ?>
