<?php
include('database_connection.php');

$months = [];
$amounts = [];

for ($i = 5; $i >= 0; $i--) {
    $month_start = strtotime(date('Y-m-01', strtotime("-$i month")));
    $month_end = strtotime(date('Y-m-t', strtotime("-$i month")) . ' 23:59:59');

    $query = "
        SELECT f.fees_data
        FROM sfms_fees_paid p
        JOIN sfms_fees f ON f.fees_id = p.fees_id
        WHERE p.fees_paid_on BETWEEN '$month_start' AND '$month_end'
    ";

    $result = $connect->query($query, PDO::FETCH_ASSOC);
    $total = 0;
    foreach ($result as $row) {
        $fees = json_decode($row['fees_data'], true);
        foreach ($fees as $fee) {
            $total += $fee['fees_value'];
        }
    }

    $months[] = date('M Y', $month_start);
    $amounts[] = $total;
}

echo json_encode(['months' => $months, 'amounts' => $amounts]);
