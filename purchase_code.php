<?php
include('database_connection.php');
include('header.php');

if (!is_login()) {
    header('location:login.php');
}

// Get purchase code from your database or file
$license_file = 'license.json';

$purchase_data = [
    'purchase_code' => 'Not set',
    'purchased_on' => 'N/A',
    'valid_till' => 'N/A'
];

if (file_exists($license_file)) {
    $json = file_get_contents($license_file);
    $purchase_data = json_decode($json, true);
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">🔐 Purchase Code</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Purchase Code</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-key"></i> License Details</div>
        <div class="card-body">
            <p><strong>Purchase Code:</strong> <?= htmlspecialchars($purchase_data['purchase_code']) ?></p>
            <p><strong>Purchased On:</strong> <?= htmlspecialchars($purchase_data['purchased_on']) ?></p>
            <p><strong>Valid Till:</strong> <?= htmlspecialchars($purchase_data['valid_till']) ?></p>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
