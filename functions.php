<?php

function get_today_fees($connect) {
    $start = strtotime(date('Y-m-d') . ' 00:00:00');
    $end = strtotime(date('Y-m-d') . ' 23:59:59');
    $query = "
        SELECT f.fees_data
        FROM sfms_fees_paid p
        JOIN sfms_fees f ON f.fees_id = p.fees_id
        WHERE p.fees_paid_on BETWEEN '$start' AND '$end'
    ";
    $result = $connect->query($query, PDO::FETCH_ASSOC);
    $total = 0;
    foreach ($result as $row) {
        $fees = json_decode($row['fees_data'], true);
        foreach ($fees as $fee) {
            $total += $fee['fees_value'];
        }
    }
    return $total;
}

function get_monthly_fees($connect) {
    $start = strtotime(date('Y-m-01') . ' 00:00:00');
    $end = strtotime(date('Y-m-t') . ' 23:59:59');
    $query = "
        SELECT f.fees_data
        FROM sfms_fees_paid p
        JOIN sfms_fees f ON f.fees_id = p.fees_id
        WHERE p.fees_paid_on BETWEEN '$start' AND '$end'
    ";
    $result = $connect->query($query, PDO::FETCH_ASSOC);
    $total = 0;
    foreach ($result as $row) {
        $fees = json_decode($row['fees_data'], true);
        foreach ($fees as $fee) {
            $total += $fee['fees_value'];
        }
    }
    return $total;
}

function get_class_wise_today_fees($connect) {
    $start = strtotime(date('Y-m-d') . ' 00:00:00');
    $end = strtotime(date('Y-m-d') . ' 23:59:59');
    $query = "
        SELECT std.acedemic_standard_name, std.acedemic_standard_division, f.fees_data
        FROM sfms_fees_paid p
        JOIN sfms_acedemic_standard std ON std.acedemic_standard_id = p.acedemic_standard_id
        JOIN sfms_fees f ON f.fees_id = p.fees_id
        WHERE p.fees_paid_on BETWEEN '$start' AND '$end'
    ";
    $result = $connect->query($query, PDO::FETCH_ASSOC);
    $data = [];

    foreach ($result as $row) {
        $class = $row['acedemic_standard_name'] . ' - ' . $row['acedemic_standard_division'];
        $fees = json_decode($row['fees_data'], true);
        foreach ($fees as $fee) {
            $data[$class] = ($data[$class] ?? 0) + $fee['fees_value'];
        }
    }

    return $data;
}
// ✅ Get paid fee months
function get_paid_months($connect, $student_id) {
    $stmt = $connect->prepare("
        SELECT f.fees_month 
        FROM sfms_fees_paid p 
        INNER JOIN sfms_fees f ON f.fees_id = p.fees_id 
        WHERE p.student_id = ?
    ");
    $stmt->execute([$student_id]);
    $months = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return implode(", ", $months);
}

// ✅ Get unpaid fee months
function get_unpaid_months($connect, $student_id) {
    $stmt = $connect->prepare("
        SELECT f.fees_month 
        FROM sfms_fees f 
        WHERE f.fees_id NOT IN (
            SELECT fees_id FROM sfms_fees_paid WHERE student_id = ?
        )
    ");
    $stmt->execute([$student_id]);
    $months = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return implode(", ", $months);
}

// ✅ Get total paid amount
function get_total_paid($connect, $student_id) {
    $stmt = $connect->prepare("
        SELECT f.fees_data 
        FROM sfms_fees_paid p 
        INNER JOIN sfms_fees f ON f.fees_id = p.fees_id 
        WHERE p.student_id = ?
    ");
    $stmt->execute([$student_id]);
    $total = 0;
    foreach ($stmt as $row) {
        $items = json_decode($row['fees_data'], true);
        foreach ($items as $item) {
            $total += $item['fees_value'];
        }
    }
    return $total;
}

// ✅ Get total pending amount
function get_total_pending($connect, $student_id) {
    $stmt = $connect->prepare("
        SELECT f.fees_data 
        FROM sfms_fees f 
        WHERE f.fees_id NOT IN (
            SELECT fees_id FROM sfms_fees_paid WHERE student_id = ?
        )
    ");
    $stmt->execute([$student_id]);
    $total = 0;
    foreach ($stmt as $row) {
        $items = json_decode($row['fees_data'], true);
        foreach ($items as $item) {
            $total += $item['fees_value'];
        }
    }
    return $total;
}

// ✅ Get last paid date
function get_last_payment_date($connect, $student_id) {
    $stmt = $connect->prepare("
        SELECT MAX(fees_paid_on) AS last_date 
        FROM sfms_fees_paid 
        WHERE student_id = ?
    ");
    $stmt->execute([$student_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['last_date'] ? date('d/m/Y', $result['last_date']) : 'N/A';
}
