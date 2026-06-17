<?php

//student_standard.php

include('database_connection.php');

if(!is_login())
{
	header('location:login.php');
}

if(!is_master_user())
{
	header('location:index.php');
}

$error = '';

if(isset($_POST['add_student_standard']))
{
	$formdata = array();

	if(empty($_POST['student_id']))
	{
		$error .= '<li>Please Select Student</li>';
	}
	else
	{
		$formdata['student_id'] = trim($_POST['student_id']);
	}

	if(empty($_POST['acedemic_year_id']))
	{
		$error .= '<li>Please Select Academic Year</li>';
	}
	else
	{
		$formdata['acedemic_year_id'] = trim($_POST['acedemic_year_id']);
	}

	if(empty($_POST['acedemic_standard_id']))
	{
		$error .= '<li>Please Select Standard</li>';
	}
	else
	{
		$formdata['acedemic_standard_id'] = trim($_POST['acedemic_standard_id']);
	}

	if($error == '')
	{
		//already exits
		$query = "
		SELECT * FROM sfms_student_standard 
		WHERE student_id = '".$formdata['student_id']."' 
		AND acedemic_year_id = '".$formdata['acedemic_year_id']."' 
		AND acedemic_standard_id = '".$formdata['acedemic_standard_id']."' 
		AND student_standard_status = 'Enable'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Student Standard Data Already Exists</li>';
		}
		else
		{
			$data = array(
				':student_id'			=>	$formdata['student_id'],
				':acedemic_year_id'		=>	$formdata['acedemic_year_id'],
				':acedemic_standard_id'	=>	$formdata['acedemic_standard_id'],
				':student_standard_status'	=>	'Enable',
				':student_standard_added_on'	=>	time()
			);

			$query = "
			INSERT INTO sfms_student_standard 
			(student_id, acedemic_year_id, acedemic_standard_id, student_standard_status, student_standard_added_on) VALUES (:student_id, :acedemic_year_id, :acedemic_standard_id, :student_standard_status, :student_standard_added_on)
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:student_standard.php?msg=add');
		}
	}
}

if(isset($_POST['edit_student_standard']))
{
	$formdata = array();

	if(empty($_POST['student_id']))
	{
		$error .= '<li>Please Select Student</li>';
	}
	else
	{
		$formdata['student_id'] = trim($_POST['student_id']);
	}

	if(empty($_POST['acedemic_year_id']))
	{
		$error .= '<li>Please Select Academic Year</li>';
	}
	else
	{
		$formdata['acedemic_year_id'] = trim($_POST['acedemic_year_id']);
	}

	if(empty($_POST['acedemic_standard_id']))
	{
		$error .= '<li>Please Select Standard</li>';
	}
	else
	{
		$formdata['acedemic_standard_id'] = trim($_POST['acedemic_standard_id']);
	}

	if($error == '')
	{
		$query = "
		SELECT * FROM sfms_student_standard 
		WHERE student_id = '".$formdata['student_id']."' 
		AND acedemic_year_id = '".$formdata['acedemic_year_id']."' 
		AND acedemic_standard_id = '".$formdata['acedemic_standard_id']."' 
		AND student_standard_status = 'Enable' 
		AND student_standard_id != '".$_POST['student_standard_id']."'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Student Standard Data Already Exists</li>';
		}
		else
		{
			$data = array(
				':student_id'			=>	$formdata['student_id'],
				':acedemic_year_id'		=>	$formdata['acedemic_year_id'],
				':acedemic_standard_id'	=>	$formdata['acedemic_standard_id'],
				':student_standard_updated_on'	=>	time(),
				':student_standard_id'	=>	$_POST['student_standard_id']
			);

			$query = "
			UPDATE sfms_student_standard 
			SET student_id = :student_id, 
			acedemic_year_id = :acedemic_year_id, 
			acedemic_standard_id = :acedemic_standard_id, 
			student_standard_updated_on = :student_standard_updated_on 
			WHERE student_standard_id = :student_standard_id
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:student_standard.php?msg=edit');
		}
	}
}

if(isset($_GET["action"], $_GET["id"], $_GET["status"]) && $_GET["action"] == 'delete')
{
	$student_standard_id = $_GET["id"];

	$status = $_GET["status"];

	$data = array(
		':student_standard_status'		=>	$status,
		':student_standard_id'			=>	$student_standard_id
	);

	$query = "
	UPDATE sfms_student_standard 
	SET student_standard_status = :student_standard_status 
	WHERE student_standard_id = :student_standard_id
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	header('location:student_standard.php?msg='.strtolower($status));
}

include('header.php');

?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Student Standard Management</h1>
	<?php
	if(isset($_GET["action"]))
	{
		if($_GET['action'] == 'add')
		{
	?>
	<ol class="breadcrumb mb-4">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="student_standard.php">Student Standard Management</a></li>
        <li class="breadcrumb-item active">Add Student Standard</li>
    </ol>
    <div class="row">
    	<div class="col-md-6">
    		<?php
    		if($error != '')
    		{
    			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    		}
    		?>
    		<div class="card mb-4">
    			<div class="card-header">
    				<i class="fas fa-user-plus"></i> Add Student into new Standard
                </div>
                <div class="card-body">
                	<form method="post">
                		<div class="mb-3">
                			<label>Select Student <span class="text-danger">*</span></label>
                			<select name="student_id" class="form-control">
                				<option value="">Select Student</option>
                				<?php echo Student_list_data($connect); ?>
                			</select>
                		</div>
                		<div class="mb-3">
                			<label>Select Academic Year <span class="text-danger">*</span></label>
                			<select name="acedemic_year_id" class="form-control">
                				<option value="">Select Academic Year</option>
                				<?php echo Academic_list_data($connect); ?>
                			</select>
                		</div>
                		<div class="mb-3">
                			<label>Select Standard <span class="text-danger">*</span></label>
                			<select name="acedemic_standard_id" class="form-control">
                				<option value="">Select Standard</option>
                				<?php echo Academic_standard_list_data($connect); ?>
                			</select>
                		</div>
                		<div class="mt-4 mb-0">
                			<input type="submit" name="add_student_standard" class="btn btn-success" value="Add" />
                		</div>
                	</form>
                </div>
            </div>
    	</div>
    </div>
	<?php
		}
		else if($_GET["action"] == "edit")
		{
			if(isset($_GET["id"]))
			{
				$query = "
				SELECT * FROM sfms_student_standard 
				WHERE student_standard_id = '".$_GET["id"]."'
				";

				$student_standard_result = $connect->query($query, PDO::FETCH_ASSOC);

				foreach($student_standard_result as $student_standard_result_row)
				{
				?>
	<ol class="breadcrumb mb-4">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="student_standard.php">Student Standard Management</a></li>
        <li class="breadcrumb-item active">Edit Student Standard Data</li>
    </ol>
    <div class="row">
        <div class="col-md-6">
        	<?php

        	if($error != '')
        	{
        		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        	}

        	?>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-edit"></i> Edit Student Standard Data
                </div>
                <div class="card-body">
                	<form method="post">
                		<div class="mb-3">
                			<label>Select Student <span class="text-danger">*</span></label>
                			<select name="student_id" id="student_id" class="form-control">
                				<option value="">Select Student</option>
                				<?php echo Student_list_data($connect); ?>
                			</select>
                		</div>
                		<div class="mb-3">
                			<label>Select Academic Year <span class="text-danger">*</span></label>
                			<select name="acedemic_year_id" id="acedemic_year_id" class="form-control">
                				<option value="">Select Academic Year</option>
                				<?php echo Academic_list_data($connect); ?>
                			</select>
                		</div>
                		<div class="mb-3">
                			<label>Select Standard <span class="text-danger">*</span></label>
                			<select name="acedemic_standard_id" id="acedemic_standard_id" class="form-control">
                				<option value="">Select Standard</option>
                				<?php echo Academic_standard_list_data($connect); ?>
                			</select>
                		</div>
                		<div class="mt-4 mb-0">
                			<input type="hidden" name="student_standard_id" value="<?php echo $student_standard_result_row["student_standard_id"]; ?>" />
                			<input type="submit" name="edit_student_standard" class="btn btn-success" value="Edit" />
                		</div>
                	</form>
                </div>
            </div>
        </div>
    </div>
    <script>
    	$('#student_id').val("<?php echo $student_standard_result_row['student_id']; ?>");

    	$('#acedemic_year_id').val("<?php echo $student_standard_result_row['acedemic_year_id']; ?>");

    	$('#acedemic_standard_id').val("<?php echo $student_standard_result_row['acedemic_standard_id']; ?>");
    </script>
				<?php
				}
			}
		}
	}
	else
	{
	?>
	<ol class="breadcrumb mb-4">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Student Standard Management</li>
    </ol>
    <?php

    if(isset($_GET['msg']))
    {
    	if($_GET['msg'] == 'add')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New Student Standard Data Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}

    	if($_GET["msg"] == 'edit')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Student Standard Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}
    	if($_GET["msg"] == 'enable' || $_GET["msg"] == 'disable')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Academic Standard Data Status Change to '.$_GET["msg"].' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}
    }

    ?>
    <div class="card mb-4">
    	<div class="card-header">
    		<div class="row">
    			<div class="col col-md-6">
    				<i class="fas fa-table me-1"></i> Student Standard Management
    			</div>
    			<div class="col col-md-6" align="right">
    				<a href="student_standard.php?action=add" class="btn btn-success btn-sm">Add</a>
    			</div>
    		</div>
    	</div>
    	<div class="card-body">
    		<table id="student_standard_data" class="table table-bordered table-striped">
    			<thead>
    				<tr>
    					<th>Student Number</th>
                        <th>Student Name</th>
                        <th>Academic Year</th>
                        <th>Standard</th>
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

<?php

include('footer.php');

?>

<script>

var datatable = $('#student_standard_data').DataTable({

	"processing" : true,
	"serverSide" : true,
	"order" : [],
	"ajax" : {
		url : "action.php",
		type : "POST",
		data : {action : 'fetch_student_standard'}
	}

});

function delete_data(id, status)
{
	var new_status = 'Enable';

	if(status == 'Enable')
	{
		new_status = 'Disable';
	}

	if(confirm("Are you sure you want to "+new_status+" this Student Standard?"))
	{
		window.location.href="student_standard.php?action=delete&id="+id+"&status="+new_status;
	}
}

</script>

