<?php
include('database_connection.php');
include('functions.php');

if (!is_login()) {
    header("location:login.php");
}

$search_term = '';
$students = [];

if (isset($_POST['search']) && !empty(trim($_POST['keyword']))) {
    $search_term = trim($_POST['keyword']);
    
    $query = "
        SELECT s.*, ss.acedemic_year_id, ss.acedemic_standard_id, a.acedemic_standard_name, a.acedemic_standard_division
        FROM sfms_student s
        LEFT JOIN sfms_student_standard ss ON ss.student_id = s.student_id
        LEFT JOIN sfms_acedemic_standard a ON a.acedemic_standard_id = ss.acedemic_standard_id
        WHERE s.student_name LIKE :keyword 
        OR s.student_contact_number1 LIKE :keyword 
        OR a.acedemic_standard_name LIKE :keyword 
        ORDER BY s.student_name ASC
    ";

    $stmt = $connect->prepare($query);
    $stmt->execute([':keyword' => "%$search_term%"]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include('header.php'); ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">📲 Send WhatsApp Fee Reminders</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Send WhatsApp Message</li>
    </ol>

    <!-- Search Form -->
    <form method="post" class="mb-4">
        <div class="row">
            <div class="col-md-10">
                <input type="text" name="keyword" class="form-control" placeholder="Search by student name, contact, or class..." value="<?= htmlentities($search_term) ?>">
            </div>
            <div class="col-md-2">
                <input type="submit" name="search" value="Search" class="btn btn-primary w-100" />
            </div>
        </div>
    </form>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-users"></i> Students</div>
        <div class="card-body">
            <?php if (count($students) > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>WhatsApp</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $student): 
                        $student_id = $student['student_id'];
                        $student_name = $student['student_name'];
                        $number = $student['student_contact_number1'];
                        $image = $student['student_image'];
                        $std_name = $student['acedemic_standard_name'] . " - " . $student['acedemic_standard_division'];

                        $paid_months = get_paid_months($connect, $student_id);
                        $unpaid_months = get_unpaid_months($connect, $student_id);
                        $paid_amount = get_total_paid($connect, $student_id);
                        $pending_amount = get_total_pending($connect, $student_id);
                        $last_payment = get_last_payment_date($connect, $student_id);

                        $message = "📚 *Fee Reminder - ABC School*\n\n👦 *Student*: $student_name\n📘 *Class*: $std_name\n\n✅ *Paid*: $paid_months\n❌ *Unpaid*: $unpaid_months\n💰 *Paid*: ₹$paid_amount\n💸 *Pending*: ₹$pending_amount\n📅 *Last Paid*: $last_payment\n\nThank you!";
                        $encoded_msg = urlencode($message);
                        $whatsapp_link = "https://wa.me/91$number?text=$encoded_msg";
                    ?>
                        <tr>
                            <td><img src="upload/<?= $image ?>" width="50"></td>
                            <td><?= $student_name ?></td>
                            <td><?= $std_name ?></td>
                            <td>+91 <?= $number ?></td>
                            <td><a href="<?= $whatsapp_link ?>" class="btn btn-success btn-sm" target="_blank">📤 Send Message</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($search_term != ''): ?>
                <div class="alert alert-warning">No students found for "<strong><?= htmlentities($search_term) ?></strong>"</div>
            <?php else: ?>
                <p class="text-muted">Please enter a search term and click Search.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
