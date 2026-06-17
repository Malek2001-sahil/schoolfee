<?php
// fees_paid.php
include('database_connection.php');

if(!is_login()) {
    header('location:login.php');
}

$message = '';
$error = '';
$student_data = '';

if(isset($_POST["search_student"])) {
    $formdata = array();

    // Academic Year check
    if(empty($_POST["acedemic_year_id"])) {
        $error .= '<li>Please Select Academic Year</li>';
    } else {
        $formdata['acedemic_year_id'] = trim($_POST["acedemic_year_id"]);
    }

    // Student keyword check
    if(empty($_POST["student_keyword"])) {
        $error .= '<li>Please Enter Student Number / Name / Mother Name</li>';
    } else {
        $formdata['student_keyword'] = trim($_POST["student_keyword"]);
    }

    if($error == '') {
        $student_data .= '<div class="card mb-3"><div class="card-header">Basic Details</div><div class="card-body">';

        $data = array(':keyword' => '%' . $formdata['student_keyword'] . '%');

        // Search by number, name, or mother name
        $query = "
        SELECT * FROM sfms_student 
        WHERE student_number LIKE :keyword 
           OR student_name LIKE :keyword 
           OR student_mother_name LIKE :keyword
        ";
        $statement = $connect->prepare($query);
        $statement->execute($data);

        if($statement->rowCount() > 0) {
            $result = $statement->fetchAll();

            foreach($result as $row) {
                $student_data .= '
                <div class="row">
                    <div class="col-md-9">
                        <table class="table table-bordered">
                            <tr><td width="30%"><b>Student Number</b></td><td>'.$row["student_number"].'</td></tr>
                            <tr><td><b>Student Name</b></td><td>'.$row["student_name"].'</td></tr>
                            <tr><td><b>Father Name</b></td><td>'.$row["student_father_name"].'</td></tr>
                ';

                // Fetch class details
                $query_1 = "
                SELECT sfms_acedemic_standard.acedemic_standard_name, 
                       sfms_acedemic_standard.acedemic_standard_division, 
                       sfms_student_standard.acedemic_standard_id  
                FROM sfms_acedemic_standard 
                INNER JOIN sfms_student_standard 
                ON sfms_student_standard.acedemic_standard_id = sfms_acedemic_standard.acedemic_standard_id 
                WHERE sfms_student_standard.student_id = '".$row['student_id']."' 
                  AND sfms_student_standard.acedemic_year_id = '".trim($formdata['acedemic_year_id'])."'
                ";

                $result_1 = $connect->query($query_1, PDO::FETCH_ASSOC)->fetchAll();

                if(!empty($result_1)) {
                    foreach($result_1 as $row_1) {
                        $student_data .= '<tr><td><b>Standard</b></td><td>'.$row_1["acedemic_standard_name"].' - '.$row_1["acedemic_standard_division"].'</td></tr>';
                    }
                } else {
                    $student_data .= '<tr><td colspan="2" class="text-danger">No class assigned for this student</td></tr>';
                }

                $student_data .= '</table></div>
                    <div class="col-md-3"><img src="upload/'.$row["student_image"].'" class="img-thumbnail img-fluid" /></div>
                </div></div></div>';

                // Fees details section
                if(!empty($result_1)) {
                    $student_data .= '<div class="card mb-3"><div class="card-header"><b>Fees Details</b></div><div class="card-body"><table class="table table-bordered">
                    <tr><th width="20%">Fees Month</th><th width="30%">Fees Breakup</th><th width="20%">Amount</th><th width="20%">Paid Status</th><th width="10%">Action</th></tr>';

                    $count_pending_fees = 0;

                    foreach($result_1 as $row_1) {
                        $query_2 = "
                        SELECT fees_month, fees_data, fees_id FROM sfms_fees 
                        WHERE acedemic_year_id = '".trim($formdata['acedemic_year_id'])."' 
                        AND acedemic_standard_id = '".$row_1['acedemic_standard_id']."' 
                        AND fees_status = 'Enable' 
                        ORDER BY fees_id ASC
                        ";
                        $result_2 = $connect->query($query_2, PDO::FETCH_ASSOC);

                        foreach($result_2 as $row_2) {
                            $total_fees = 0;
                            $fees_breakup = '<ul class="list-unstyled">';
                            $fees_data = json_decode($row_2["fees_data"], true);

                            foreach($fees_data as $fee_data) {
                                $total_fees += $fee_data["fees_value"];
                                $fees_breakup .= '<li><b>'.$fee_data["fees_name"].' - </b>&#8377; '.$fee_data["fees_value"].'</li>';
                            }

                            $query_3 = "
                            SELECT fees_paid_id FROM sfms_fees_paid 
                            WHERE student_id = '".$row['student_id']."' 
                            AND fees_id = '".$row_2["fees_id"]."' 
                            AND acedemic_year_id = '".trim($formdata['acedemic_year_id'])."' 
                            AND acedemic_standard_id = '".$row_1['acedemic_standard_id']."'
                            ";
                            $statement_3 = $connect->prepare($query_3);
                            $statement_3->execute();

                            if($statement_3->rowCount() > 0) {
                                $fees_paid_status = '<span class="badge bg-success">Paid</span>';
                                $fees_action_button = '-';
                            } else {
                                $fees_paid_status = '<span class="badge bg-danger">Not Paid</span>';
                                if($count_pending_fees == 0) {
                                    $fees_action_button = '
                                    <form method="post">
                                        <input type="hidden" name="fees_id" value="'.$row_2["fees_id"].'" />
                                        <input type="hidden" name="student_id" value="'.$row["student_id"].'" />
                                        <input type="hidden" name="acedemic_year_id" value="'.$formdata['acedemic_year_id'].'" />
                                        <input type="hidden" name="acedemic_standard_id" value="'.$row_1['acedemic_standard_id'].'" />
                                        <input type="submit" name="receive_fee" class="btn btn-warning" value="Receive Fee" />
                                    </form>';
                                } else {
                                    $fees_action_button = 'NA';
                                }
                                $count_pending_fees++;
                            }

                            $fees_breakup .= '</ul>';
                            $student_data .= '<tr><td><b>'.$row_2["fees_month"].'</b></td><td>'.$fees_breakup.'</td><td>&#8377; '.$total_fees.'</td><td>'.$fees_paid_status.'</td><td>'.$fees_action_button.'</td></tr>';
                        }
                    }
                    $student_data .= '</table></div></div>';
                }
            }
        } else {
            $student_data .= '<p><b>No Student Data Found</b></p>';
        }
    }
}

// Receive fee
if(isset($_POST["receive_fee"])) {
    $data = array(
        ':student_id'           =>  $_POST["student_id"],
        ':fees_id'              =>  $_POST["fees_id"],
        ':acedemic_year_id'     =>  $_POST["acedemic_year_id"],
        ':acedemic_standard_id' =>  $_POST["acedemic_standard_id"],
        ':fees_received_by'     =>  $_SESSION['admin_id'],
        ':fees_paid_on'         =>  time()
    );
    $query = "
    INSERT INTO sfms_fees_paid 
    (student_id, fees_id, acedemic_year_id, acedemic_standard_id, fees_received_by, fees_paid_on) 
    VALUES (:student_id, :fees_id, :acedemic_year_id, :acedemic_standard_id, :fees_received_by, :fees_paid_on)
    ";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    header('location:fees_paid.php?msg=add');
}

// Delete fee
if(isset($_GET["action"], $_GET["id"]) && $_GET["action"] == 'delete') {
    $data = array(':fees_paid_id' => $_GET["id"]);
    $query = "DELETE FROM sfms_fees_paid WHERE fees_paid_id = :fees_paid_id";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    header('location:fees_paid.php?msg=delete');
}

include('header.php');
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Fees Received Management</h1>

    <?php if(isset($_GET["action"]) && $_GET["action"] == 'add') { ?>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="fees_paid.php">Fees Received Management</a></li>
            <li class="breadcrumb-item active">Add Fee Receive</li>
        </ol>
        <?php if($error != '') { echo '<div class="alert alert-danger"><ul class="list-unstyled">'.$error.'</ul></div>'; } ?>
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-user-plus"></i> Add Fee Receive</div>
            <div class="card-body">
                <form method="post">
                    <div class="row mb-5">
                        <div class="col-md-5">
                            <label>Select Academic Year <span class="text-danger">*</span></label>
                            <select name="acedemic_year_id" id="acedemic_year_id" class="form-control">
                                <option value="">Select Academic Year</option>
                                <?php echo Academic_list_data($connect); ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label>Enter Student Name / Number / GR NO. <span class="text-danger">*</span></label>
                            <input type="text" name="student_keyword" class="form-control" value="<?php echo isset($_POST["student_keyword"]) ? $_POST["student_keyword"] : ''; ?>" />
                        </div>
                        <div class="col-md-2">
                            <br />
                            <input type="submit" name="search_student" class="btn btn-primary" value="Search" />
                        </div>
                    </div>
                </form>
                <?php echo $student_data; ?>
            </div>
        </div>
    <?php } else { ?>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Fees Master</li>
        </ol>
        <?php
        if(isset($_GET["msg"])) {
            if($_GET["msg"] == 'add') { echo '<div class="alert alert-success">New Fees Data Added</div>'; }
            if($_GET["msg"] == 'edit') { echo '<div class="alert alert-success">Fees Data Edited</div>'; }
            if($_GET["msg"] == 'delete') { echo '<div class="alert alert-success">Fees Received Data Removed</div>'; }
        }
        ?>
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6"><i class="fas fa-table me-1"></i> Fees Master</div>
                    <div class="col-md-6" align="right"><a href="fees_paid.php?action=add" class="btn btn-success btn-sm">Add</a></div>
                </div>
            </div>
            <div class="card-body">
                <table id="fees_paid_data" class="table table-bordered table-striped">
                   <thead>
    <tr>
        <th>Student Number</th>
        <th>Student Name</th>
        <th>Academic Year</th>
        <th>Month</th>
        <th>Standard</th>
        <th>Fees Details</th>
        <th>Enter By</th>
        <th>Added on</th>
        <th>PDF</th>
        <th>WhatsApp</th>
        <th>Action</th>
    </tr>
</thead>

                </table>
            </div>
        </div>
        <script>
        var userdataTable = $('#fees_paid_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": { url:"action.php", type:"POST", data:{action:"fetch_fees_paid_data"} },
            "columnDefs":[{ "targets":[5], "orderable":false }],
            "pageLength": 25
        });
        function delete_data(id) {
            if(confirm("Are you sure you want to delete this Fees Received Data?")) {
                window.location.href="fees_paid.php?action=delete&id="+id;
            }
        }
        </script>
    <?php } ?>
</div>
<?php include('footer.php'); ?>
