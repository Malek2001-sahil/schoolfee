<?php

//fees_master.php

include('database_connection.php');

if(!is_login())
{
	header('location:login.php');
}

if(!is_master_user())
{
	header('location:index.php');
}

$message = '';

$error = '';

if(isset($_POST["add_fees"]))
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

    if(empty($_POST["acedemic_standard_id"]))
    {
        $error .= '<li>Please Select Standard</li>';
    }
    else
    {
        $formdata['acedemic_standard_id'] = trim($_POST["acedemic_standard_id"]);
    }

    if(empty($_POST["fees_month"]))
    {
        $error .= '<li>Please Select Fees Month</li>';
    }
    else
    {
        $formdata['fees_month'] = trim($_POST["fees_month"]);
    }

    foreach($_POST["fees_name"] as $fees_name)
    {
        if(empty($fees_name))
        {
            $error .= '<li>Please Enter Fees Name Details</li>';
        }
        else
        {
            $formdata['fees_name'][] = trim($fees_name);
        }
    }

    foreach($_POST["fees_value"] as $fees_value)
    {
        if(empty($fees_value))
        {
            $error .= '<li>Please Enter Fees Value Data</li>';
        }
        else
        {
            $formdata['fees_value'][] = trim($fees_value);
        }
    }

    if($error == '')
    {
        $fees_data = array();

        for($count = 0; $count < count($formdata['fees_name']); $count++)
        {
            $fees_data[] = array(
                'fees_name'     =>  $formdata['fees_name'][$count],
                'fees_value'    =>  $formdata['fees_value'][$count]
            );
        }

        $query = "
        SELECT * FROM sfms_fees 
        WHERE acedemic_year_id = '".$formdata['acedemic_year_id']."' 
        AND acedemic_standard_id = '".$formdata['acedemic_standard_id']."' 
        AND fees_month = '".$formdata['fees_month']."' 
        AND fees_status = 'Enable'
        ";

        $statement = $connect->prepare($query);

        $statement->execute();

        if($statement->rowCount() > 0)
        {
            $error = '<li>Fees Data Already Exists</li>';
        }
        else
        {
            $data = array(
                ':acedemic_year_id'      		=>  $formdata['acedemic_year_id'],
                ':acedemic_standard_id'     	=>  $formdata['acedemic_standard_id'],
                ':fees_month'                   =>  $formdata['fees_month'],
                ':fees_data'                    =>  json_encode($fees_data),
                ':fees_status'                  =>  'Enable',
                ':fees_added_on'                =>  time()
            );

            $query = "
            INSERT INTO sfms_fees 
            (acedemic_year_id, acedemic_standard_id, fees_month, fees_data, fees_status, fees_added_on) 
            VALUES (:acedemic_year_id, :acedemic_standard_id, :fees_month, :fees_data, :fees_status, :fees_added_on)
            ";

            $statement = $connect->prepare($query);

            $statement->execute($data);

            header('location:fees_master.php?msg=add');
        }
    }
}

if(isset($_POST["edit_fees"]))
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

    if(empty($_POST["acedemic_standard_id"]))
    {
        $error .= '<li>Please Select Standard</li>';
    }
    else
    {
        $formdata['acedemic_standard_id'] = trim($_POST["acedemic_standard_id"]);
    }

    if(empty($_POST["fees_month"]))
    {
        $error .= '<li>Please Select Fees Month</li>';
    }
    else
    {
        $formdata['fees_month'] = trim($_POST["fees_month"]);
    }

    foreach($_POST["fees_name"] as $fees_name)
    {
        if(empty($fees_name))
        {
            $error .= '<li>Please Enter Fees Name Details</li>';
        }
        else
        {
            $formdata['fees_name'][] = trim($fees_name);
        }
    }

    foreach($_POST["fees_value"] as $fees_value)
    {
        if(empty($fees_value))
        {
            $error .= '<li>Please Enter Fees Value Data</li>';
        }
        else
        {
            $formdata['fees_value'][] = trim($fees_value);
        }
    }

    if($error == '')
    {
        $fees_data = array();

        for($count = 0; $count < count($formdata['fees_name']); $count++)
        {
            $fees_data[] = array(
                'fees_name'     =>  $formdata['fees_name'][$count],
                'fees_value'    =>  $formdata['fees_value'][$count]
            );
        }

        $query = "
        SELECT * FROM sfms_fees 
        WHERE acedemic_year_id = '".$formdata['acedemic_year_id']."' 
        AND acedemic_standard_id = '".$formdata['acedemic_standard_id']."' 
        AND fees_month = '".$formdata['fees_month']."' 
        AND fees_status = 'Enable' 
        AND fees_id != '".$_POST['fees_id']."'
        ";

        $statement = $connect->prepare($query);

        $statement->execute();

        if($statement->rowCount() > 0)
        {
            $error = '<li>Fees Data Already Exists</li>';
        }
        else
        {
            $data = array(
                ':acedemic_year_id'             =>  $formdata['acedemic_year_id'],
                ':acedemic_standard_id'         =>  $formdata['acedemic_standard_id'],
                ':fees_month'                   =>  $formdata['fees_month'],
                ':fees_data'                    =>  json_encode($fees_data),
                ':fees_updated_on'              =>  time(),
                ':fees_id'                      =>  $_POST["fees_id"]
            );

            $query = "
            UPDATE sfms_fees 
            SET acedemic_year_id = :acedemic_year_id, 
            acedemic_standard_id = :acedemic_standard_id,
            fees_month = :fees_month, 
            fees_data = :fees_data, 
            fees_updated_on = :fees_updated_on 
            WHERE fees_id = :fees_id
            ";

            $statement = $connect->prepare($query);

            $statement->execute($data);

            header('location:fees_master.php?msg=edit');
        }
    }
}


if(isset($_GET["action"], $_GET["id"], $_GET["status"]) && $_GET["action"] == 'delete')
{
    $fees_id = $_GET["id"];

    $status = trim($_GET["status"]);

    $data = array(
        ':fees_status'      =>  $status,
        ':fees_id'          =>  $fees_id
    );

    $query = "
    UPDATE sfms_fees 
    SET fees_status = :fees_status 
    WHERE fees_id = :fees_id
    ";

    $statement = $connect->prepare($query);

    $statement->execute($data);

    header('location:fees_master.php?msg='.strtolower($status).'');

}

include('header.php');

?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Fees Master</h1>
	<?php
	if(isset($_GET["action"], $_GET["id"]))
    {
        if($_GET["action"] == 'add')
        {
    ?>
    <ol class="breadcrumb mb-4">
    	<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="fees_master.php">Fees Master</a></li>
        <li class="breadcrumb-item active">Add New Fees Data</li>
    </ol>
    <div class="row">
        <div class="col-md-6">
        <?php
        if(isset($error) && $error != '')
        {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
        ?>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-plus"></i> Add New Fees Data
                </div>
                <div class="card-body">
					<form method="post">
					    <div class="mb-3">
					        <label>Select Academic Year <span class="text-danger">*</span></label>
					        <select name="acedemic_year_id" id="acedemic_year_id" class="form-control">
					            <option value="">Select Academic Year</option>
					            <?php
					            echo Academic_list_data($connect);
					            ?>
					        </select>
					    </div>
					    <div class="mb-3">
					        <label>Select Standard <span class="text-danger">*</span></label>
					        <select name="acedemic_standard_id" class="form-control">
					            <option value="">Select Standard</option>
					            <?php
					            echo Academic_standard_list_data($connect);
					            ?>
					        </select>
					    </div>
					    <div class="mb-3">
					        <label>Select Month <span class="text-danger">*</span></label>
					        <input name="fees_month" id="fees_month" class="form-control">
					            <option value="">Select Month</option>
					        </select>
					    </div>
					    <div class="mb-3">
					        <label>Enter Fees Details <span class="text-danger">*</span></label>
					        <div id="dynamic_fees_area"></div>
					    </div>
					    <div class="mt-4 mb-0">
					        <input type="submit" name="add_fees" class="btn btn-success" value="Add" />
					    </div>
					</form>
	            </div>
	        </div>
	    </div>
	</div>
	    
	<script>
	$(document).ready(function(){

	    var index = 0;

	    Make_fees_field(0);

	    function Make_fees_field(index)
	    {
	        var output = '';

	        var dynamic_button = '';

	        if(index == 0)
	        {
	            dynamic_button = '<button type="button" class="btn btn-success" id="add_fees_data"><b>+</b></button>';
	        }
	        else
	        {
	            dynamic_button = '<button type="button" class="btn btn-danger remove_fees_data" data-id="'+index+'"><b>-</b></button>';
	        }

	        output = `
	        <div class="mb-3 row" id="row_`+index+`">
	            <div class="col-md-6">
	                <input type="text" name="fees_name[]" class="form-control" placeholder="Fees Name" />
	            </div>
	            <div class="col-md-4">
	                <input type="number" name="fees_value[]" class="form-control" placeholder="Fees Amount" />
	            </div>
	            <div class="col-md-2">
	                `+dynamic_button+`
	            </div>
	        </div>
	        `;

	        $('#dynamic_fees_area').append(output);
	    }

	    $(document).on('click', '#add_fees_data', function(){

	        index++;

	        Make_fees_field(index);

	    });

	    $(document).on('click', '.remove_fees_data', function(){

	        $('#row_'+$(this).data("id")+'').remove();

	    });

	    $('#acedemic_year_id').change(function(){

	        var acedemic_year_id = $('#acedemic_year_id').val();

	        $.ajax({

	            url:"action.php",
	            method:"POST",
	            data:{acedemic_year_id:acedemic_year_id, action:"fetch_acedemic_month_data"},
	            success:function(data)
	            {
	                $('#fees_month').html(data);
	            }
	        });

	    });

	});
	</script>
    <?php
        }
        else if($_GET["action"] == 'edit')
		{
		    
		    if(isset($_GET["id"]))
		    {
		        $query = "
		        SELECT * FROM sfms_fees 
		        WHERE fees_id = '".$_GET["id"]."'
		        ";

		        $fees_result = $connect->query($query, PDO::FETCH_ASSOC);

		        foreach($fees_result as $fees_row)
		        {
		    ?>
		    <ol class="breadcrumb mb-4">
		        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		        <li class="breadcrumb-item"><a href="fees_master.php">Fees Master</a></li>
		        <li class="breadcrumb-item active">Edit Fees Data</li>
		    </ol>
		    <div class="row">
		        <div class="col-md-6">
		            <?php
		            if(isset($error) && $error != '')
		            {
		                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		            }
		            ?>
		            <div class="card mb-4">
		                <div class="card-header">
		                    <i class="fas fa-user-edit"></i> Edit Fees Data
		                </div>
		                <div class="card-body">
		                    <form method="post">
		                        <div class="mb-3">
                            		<label>Select Academic Year <span class="text-danger">*</span></label>
                                    <select name="acedemic_year_id" id="acedemic_year_id" class="form-control">
                                        <option value="">Select Academic Year</option>
                                        <?php
                                        echo Academic_list_data($connect);
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                	<label>Select Standard <span class="text-danger">*</span></label>
                                    <select name="acedemic_standard_id" id="acedemic_standard_id" class="form-control">
                                        <option value="">Select Standard</option>
                                        <?php
                                        echo Academic_standard_list_data($connect);
                                        ?>
                                    </select>
                               	</div>
                               	<div class="mb-3">
                               		<label>Select Month <span class="text-danger">*</span></label>
                                    <select name="fees_month" id="fees_month" class="form-control">
                                    <?php
                                        echo Fees_month_list_data($connect, $fees_row["acedemic_year_id"], $month_array);
                                    ?>
                                	</select>
                                </div>
                                <div class="mb-3">
                                	<label>Enter Fees Details <span class="text-danger">*</span></label>
									<div id="dynamic_fees_area">
									<?php

									$fees_data = json_decode($fees_row['fees_data'], true);
									$count_fees_row = 0;
									foreach($fees_data as $fees_data_row)
									{
									    $dynamic_button = '';
									    if($count_fees_row == 0)
									    {
									        $dynamic_button = '<button type="button" class="btn btn-success" id="add_fees_data"><b>+</b></button>';
									    }
									    else
									    {
									        $dynamic_button = '<button type="button" class="btn btn-danger remove_fees_data" data-id="'.$count_fees_row.'"><b>-</b></button>';
									    }
									    echo '
									    <div class="mb-3 row" id="row_'.$count_fees_row.'">
									        <div class="col-md-6">
									            <input type="text" name="fees_name[]" class="form-control" placeholder="Fees Name" value="'.$fees_data_row["fees_name"].'" />
									        </div>
									        <div class="col-md-4">
									            <input type="number" name="fees_value[]" class="form-control" placeholder="Fees Amount" value="'.$fees_data_row["fees_value"].'" />
									        </div>
									        <div class="col-md-2">
									            '.$dynamic_button.'
									        </div>
									    </div>
									    ';
									    $count_fees_row++;
									}

									?>
									</div>
								</div>
								<div class="mt-4 mb-0">
									<input type="hidden" name="fees_id" value="<?php echo trim($_GET["id"]); ?>" />
									<input type="submit" name="edit_fees" class="btn btn-primary" value="Edit" />
								</div>
								<script>
								$(document).ready(function(){
									$('#acedemic_year_id').val("<?php echo $fees_row['acedemic_year_id']; ?>");
									$('#acedemic_standard_id').val("<?php echo $fees_row['acedemic_standard_id']; ?>");
									$('#fees_month').val("<?php echo $fees_row['fees_month']; ?>");

									var index = <?php echo $count_fees_row; ?>;

									function Make_fees_field(index)
									{
									    var output = '';

									    var dynamic_button = '';

									    if(index == 0)
									    {
									        dynamic_button = '<button type="button" class="btn btn-success" id="add_fees_data"><b>+</b></button>';
									    }
									    else
									    {
									        dynamic_button = '<button type="button" class="btn btn-danger remove_fees_data" data-id="'+index+'"><b>-</b></button>';
									    }

									    output = `
									    <div class="mb-3 row" id="row_`+index+`">
									        <div class="col-md-6">
									            <input type="text" name="fees_name[]" class="form-control" placeholder="Fees Name" />
									        </div>
									        <div class="col-md-4">
									            <input type="number" name="fees_value[]" class="form-control" placeholder="Fees Amount" />
									        </div>
									        <div class="col-md-2">
									            `+dynamic_button+`
									        </div>
									    </div>
									    `;

									    $('#dynamic_fees_area').append(output);
									}

									$(document).on('click', '#add_fees_data', function(){

									    index++;

									    Make_fees_field(index);

									});

									$(document).on('click', '.remove_fees_data', function(){

									    $('#row_'+$(this).data("id")+'').remove();

									});

									$('#acedemic_year_id').change(function(){

									    var acedemic_year_id = $('#acedemic_year_id').val();

									    $.ajax({

									        url:"action.php",
									        method:"POST",
									        data:{acedemic_year_id:acedemic_year_id, action:"fetch_acedemic_month_data"},
									        success:function(data)
									        {
									            $('#fees_month').html(data);
									        }
									    });

									});
								});
                                </script>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    			}
    		}
    		else
    		{
    			echo '<div class="alert alert-info">Something Went Wrong</div>';
    		}
    	}
    	else
    	{
    		echo '<div class="alert alert-info">Something Went Wrong</div>';
    	}
    }
    else
    {
   	?>

   	<ol class="breadcrumb mb-4">
   		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Fees Master</li>
    </ol>

    <?php

    if(isset($_GET["msg"]))
    {
    	if($_GET["msg"] == 'add')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New Fees Data Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}
    	if($_GET["msg"] == 'edit')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Fees Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}
    	if($_GET["msg"] == 'disable')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Fees Data Status Change to Disable <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}
    	if($_GET["msg"] == 'enable')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Fees Data Status Change to Enable <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}
    }
    ?>

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Fees Master
                </div>
                <div class="col col-md-6" align="right">
                	<a href="fees_master.php?action=add&id=" class="btn btn-success btn-sm">Add</a>
                </div>
            </div>
        </div>
        <div class="card-body">
        	<table id="fees_data" class="table table-bordered table-striped">
        		<thead>
        			<tr>
        				<th>Academic Year</th>
                        <th>Standard</th>
                        <th>Fees Month</th>
                        <th>Fees Details</th>
                        <th>Status</th>
                        <th>Action</th>
        			</tr>
        		</thead>
        	</table>
        </div>
    </div>
    <?php
	}
    ?>
</div>

<script>

var dataTable = $('#fees_data').DataTable({
	'processing' : true,
	'serverSide' : true,
	'order' : [],
	'ajax' : {
		url : 'action.php',
		type : "POST",
		data : { action : 'fetch_fees'}
	}
});

function delete_data(id, status)
{
    var new_status = 'Enable';

    if(status == 'Enable')
    {
        new_status = 'Disable';
    }
    if(confirm("Are you sure you want to "+new_status+" this Fees Data?"))
    {
        window.location.href="fees_master.php?action=delete&id="+id+"&status="+new_status+"";
    }
}

</script>

<?php

include('footer.php');

?>