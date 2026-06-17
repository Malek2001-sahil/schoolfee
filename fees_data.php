<?php

//fees_data.php

include('database_connection.php');

if(!is_login())
{
    header('location:login.php');
}

$message = '';

$error = '';

$student_data = '';

$fees_month_list_box = '<option value="">Select Fees Month</option>';

if(isset($_POST["search_pending_fees_data"]))
{
    $formdata = array();

    if(empty($_POST["acedemic_year_id"]))
    {
        $error .= '<li>Please Select Academic Year</li>';
    }
    else
    {
        $formdata['acedemic_year_id'] = trim($_POST["acedemic_year_id"]);
    }

    if(empty($_POST["fees_id"]))
    {
        $error .= '<li>Please Select Fees Month</li>';
    }
    else
    {
        $formdata['fees_id'] = trim($_POST["fees_id"]);
    }

    if(empty($_POST["acedemic_standard_id"]))
    {
        $error .= '<li>Please Select Academic Standard</li>';
    }
    else
    {
        $formdata['acedemic_standard_id'] = trim($_POST["acedemic_standard_id"]);
    }

    if($error == '')
    {
        $student_data = '
        <div class="card mb-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        Fees Data
                    </div>
                    <div class="col-md-6">
                        <form method="post" target="_blank" action="print_fees_data.php">
                            <input type="hidden" name="filter_fees_data" id="filter_fees_data" />
                            <button type="submit" name="pdf_fees_data" id="pdf_fees_data" class="btn btn-danger btn-sm float-end"><i class="fas fa-file-pdf"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" id="fees_data_table">
                    <table class="table table-bordered" width="100%" border="1" cellpadding="5" cellspacing="0">
                        <tr>
                            <th>Academic Year</th>
                            <th>Fees Month</th>
                            <th>Student No.</th>
                            <th>Student Name</th>
                            <th>Contact No.</th>
                            <th>Standard</th>
                            <th>Fees Amount</th>
                            <th>Status</th>
                        </tr>
        ';

        $query_1 = "
        SELECT fees_month, fees_data FROM sfms_fees 
        WHERE fees_id = '".$formdata['fees_id']."'
        ";

        $fees_month = '';
        $fees_amount = '';
        

        $result_1 = $connect->query($query_1, PDO::FETCH_ASSOC);

        foreach($result_1 as $row_1)
        {
            $fees_month = $row_1["fees_month"];

            $fees_data = json_decode($row_1['fees_data'], true);

            foreach($fees_data as $fees_row)
            {
                $fees_month = '';
                $fees_amount = 0;
                $fees_amount = $fees_amount + $fees_row["fees_value"];
            }
        }

        $query = "
        SELECT sfms_student.student_id, sfms_student.student_number, sfms_student.student_name, sfms_student.student_contact_number1, sfms_acedemic_standard.acedemic_standard_name, sfms_acedemic_standard.acedemic_standard_division, sfms_acedemic_year.acedemic_start_year, sfms_acedemic_year.acedemic_start_month, sfms_acedemic_year.acedemic_end_year, sfms_acedemic_year.acedemic_end_month FROM sfms_student_standard 
        INNER JOIN sfms_student ON sfms_student.student_id = sfms_student_standard.student_id 
        INNER JOIN sfms_acedemic_standard ON sfms_acedemic_standard.acedemic_standard_id = sfms_student_standard.acedemic_standard_id 
        INNER JOIN sfms_acedemic_year ON sfms_acedemic_year.acedemic_year_id = sfms_student_standard.acedemic_year_id 
        WHERE sfms_student_standard.acedemic_year_id = '".$formdata['acedemic_year_id']."' 
        AND sfms_student_standard.acedemic_standard_id = '".$formdata['acedemic_standard_id']."' 
        AND sfms_student_standard.student_standard_status = 'Enable'
        ";

        $result = $connect->query($query, PDO::FETCH_ASSOC);

        foreach($result as $row)
        {
            $fees_status = '';

            $query_2 = "
            SELECT fees_paid_id FROM sfms_fees_paid 
            WHERE student_id = '".$row["student_id"]."' 
            AND acedemic_year_id = '".$formdata['acedemic_year_id']."' 
            AND fees_id = '".$formdata['fees_id']."' 
            AND acedemic_standard_id = '".$formdata['acedemic_standard_id']."'
            ";

            $statement = $connect->prepare($query_2);

            $statement->execute();

            if($statement->rowCount() > 0)
            {
                $fees_status = '<span class="badge bg-success">Paid</span>';
            }
            else
            {
                $fees_status = '<span class="badge bg-danger">Not Paid</span>';
            }

            $student_data .= '
                        <tr>
                            <td>'.$row["acedemic_start_month"].' '.$row["acedemic_start_year"].' - '.$row["acedemic_end_month"].' '.$row["acedemic_end_year"].'</td>
                            <td>'.$fees_month.'</td>
                            <td>'.$row["student_number"].'</td>
                            <td>'.$row["student_name"].'</td>
                            <td>'.$row["student_contact_number1"].'</td>
                            <td>'.$row["acedemic_standard_name"].' - '.$row["acedemic_standard_division"].'</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>'.$fees_amount.'</td>
                            <td>'.$fees_status.'</td>
                        </tr>
            ';
        }

        $student_data .= '
                    </table>
                </div>
            </div>
        </div>
        ';

    }

    
    $query_3 = "
    SELECT fees_id, fees_month FROM sfms_fees 
    WHERE acedemic_year_id = '".$_POST["acedemic_year_id"]."' 
    AND acedemic_standard_id = '".$_POST["acedemic_standard_id"]."' 
    AND fees_status = 'Enable' 
    ORDER BY fees_id ASC
    ";

    $result_3 = $connect->query($query_3, PDO::FETCH_ASSOC);

    foreach($result_3 as $row_3)
    {
        $fees_month_list_box .= '<option value="'.$row_3["fees_id"].'">'.$row_3["fees_month"].'</option>';
    }
}

include('header.php');

?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Get Fees Data</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Get Fees Data</li>
    </ol>
    <?php
    if(isset($error) && $error != '')
    {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    ?>
    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <i class="fas fa-user-plus"></i> Get Fees Data
                </div>
                <div class="col-md-6">
                    <?php
                    if($student_data != '')
                    {
                    ?>                   

                    <script>
					$(document).ready(function(){

					    $('#pdf_fees_data').click(function(){
					        $('#filter_fees_data').val($('#fees_data_table').html());
					    });

					});
                    </script>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="row mb-5">
                    <div class="col-md-3">
						<label>Select Academic Year <span class="text-danger">*</span></label>
						<select name="acedemic_year_id" id="acedemic_year_id" class="form-control filter_fees_month">
						    <option value="">Select Academic Year</option>
						    <?php
						    echo Academic_list_data($connect);
						    ?>
						</select>
                    </div>
                    <div class="col-md-3">
						<label>Select Standard <span class="text-danger">*</span></label>
						<select name="acedemic_standard_id" id="acedemic_standard_id" class="form-control filter_fees_month">
						    <option value="">Select Standard</option>
						    <?php echo Academic_standard_list_data($connect); ?>
						</select>
                    </div>
                    <div class="col-md-3">
						<label>Select Fees Month <span class="text-danger">*</span></label>
						<select name="fees_id" id="fees_id" class="form-control">
						    <?php echo $fees_month_list_box; ?>
						</select>
                    </div>
                    
                    <div class="col-md-2">
						<br />
						<input type="submit" name="search_pending_fees_data" class="btn btn-primary" value="Get" />
                    </div>
                </div>
            </form>

        </div>
    </div>
        
    <script>
    $(document).ready(function(){

        $('#acedemic_year_id').val("<?php echo isset($_POST['acedemic_year_id']) ? $_POST['acedemic_year_id'] : ''; ?>");

        $('#fees_id').val("<?php echo isset($_POST['fees_id']) ? $_POST['fees_id'] : ''; ?>");

        $('#acedemic_standard_id').val("<?php echo isset($_POST['acedemic_standard_id']) ? $_POST['acedemic_standard_id'] : ''; ?>");

        $('.filter_fees_month').change(function(){

            var acedemic_year_id = $('#acedemic_year_id').val();

            var acedemic_standard_id = $('#acedemic_standard_id').val();

            if(acedemic_year_id != '')
            {
                if(acedemic_standard_id != '')
                {
                    $.ajax({
						url:"action.php",
						method:"POST",
						data:{acedemic_year_id:acedemic_year_id, acedemic_standard_id:acedemic_standard_id, action:"fetch_fees_month_data"},
						success:function(data)
						{
						    $('#fees_id').html(data);
						}
                    });
                }
                else
                {
                    $('#fees_id').html('<option value="">Select Fees Month</option>');
                }
            }
            else
            {
                $('#fees_id').html('<option value="">Select Fees Month</option>');
            }

        });

    });
    </script>

    <?php echo $student_data; ?>

</div>

<?php

include('footer.php');

?>