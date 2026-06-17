<?php

//academic_year.php

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

if(isset($_POST['add_academic_year']))
{
	$formdata = array();

	if(empty($_POST['acedemic_start_year']))
	{
		$error .= '<li>Start Year is required</li>';
	}
	else
	{
		$formdata['acedemic_start_year'] = $_POST['acedemic_start_year'];
	}

	if(empty($_POST['acedemic_start_month']))
	{
		$error .= '<li>Start Month is required</li>';
	}
	else
	{
		$formdata['acedemic_start_month'] = $_POST['acedemic_start_month'];
	}

	if(empty($_POST['acedemic_end_year']))
	{
		$error .= '<li>End Year is required</li>';
	}
	else
	{
		$formdata['acedemic_end_year'] = $_POST['acedemic_end_year'];
	}

	if(empty($_POST['acedemic_end_month']))
	{
		$error .= '<li>End Month is required</li>';
	}
	else
	{
		$formdata['acedemic_end_month'] = $_POST['acedemic_end_month'];
	}

	if($error == '')
	{
		$query = "
		SELECT * FROM sfms_acedemic_year 
		WHERE acedemic_start_year = '".$formdata['acedemic_start_year']."' 
		AND acedemic_start_month = '".$formdata['acedemic_start_month']."' 
		AND acedemic_end_year = '".$formdata['acedemic_end_year']."' 
		AND acedemic_end_month = '".$formdata['acedemic_end_month']."' 
		AND acedemic_year_status = 'Enable'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Academic Year Data Already Exists</li>';
		}
		else
		{
			$data = array(
				':acedemic_start_year'		=>	$formdata['acedemic_start_year'],
				':acedemic_start_month'		=>	$formdata['acedemic_start_month'],
				':acedemic_end_year'		=>	$formdata['acedemic_end_year'],
				':acedemic_end_month'		=>	$formdata['acedemic_end_month'],
				':acedemic_year_status'		=>	'Enable',
				':acedemic_added_on'		=>	time()
			);

			$query = "
			INSERT INTO sfms_acedemic_year 
			(acedemic_start_year, acedemic_start_month, acedemic_end_year, acedemic_end_month, acedemic_year_status, acedemic_added_on) VALUES (:acedemic_start_year, :acedemic_start_month, :acedemic_end_year, :acedemic_end_month, :acedemic_year_status, :acedemic_added_on)
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:academic_year.php?msg=add');
		}
	}
}

if(isset($_POST["edit_academic_year"]))
{
	$formdata = array();

	if(empty($_POST['acedemic_start_year']))
	{
		$error .= '<li>Start Year is required</li>';
	}
	else
	{
		$formdata['acedemic_start_year'] = $_POST['acedemic_start_year'];
	}

	if(empty($_POST['acedemic_start_month']))
	{
		$error .= '<li>Start Month is required</li>';
	}
	else
	{
		$formdata['acedemic_start_month'] = $_POST['acedemic_start_month'];
	}

	if(empty($_POST['acedemic_end_year']))
	{
		$error .= '<li>End Year is required</li>';
	}
	else
	{
		$formdata['acedemic_end_year'] = $_POST['acedemic_end_year'];
	}

	if(empty($_POST['acedemic_end_month']))
	{
		$error .= '<li>End Month is required</li>';
	}
	else
	{
		$formdata['acedemic_end_month'] = $_POST['acedemic_end_month'];
	}

	if($error == '')
	{
		$query = "
		SELECT * FROM sfms_acedemic_year 
		WHERE acedemic_start_year = '".$formdata['acedemic_start_year']."' 
		AND acedemic_start_month = '".$formdata['acedemic_start_month']."' 
		AND acedemic_end_year = '".$formdata['acedemic_end_year']."' 
		AND acedemic_end_month = '".$formdata['acedemic_end_month']."' 
		AND acedemic_year_status = 'Enable' 
		AND acedemic_year_id != '".$_POST['acedemic_year_id']."'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)		
		{
			$error = '<li>Academic Year Data Already Exists</li>';
		}
		else
		{
			$data = array(
				':acedemic_start_year'			=> $formdata['acedemic_start_year'],
				':acedemic_start_month'			=>	$formdata['acedemic_start_month'],
				':acedemic_end_year'			=>	$formdata['acedemic_end_year'],
				':acedemic_end_month'			=> $formdata['acedemic_end_month'],
				':acedemic_updated_on'			=>	time(),
				':acedemic_year_id'				=>	$_POST['acedemic_year_id']
			);

			$query = "
			UPDATE sfms_acedemic_year 
			SET acedemic_start_year = :acedemic_start_year, 
			acedemic_start_month = :acedemic_start_month,
            acedemic_end_year = :acedemic_end_year, 
            acedemic_end_month = :acedemic_end_month, 
            acedemic_updated_on = :acedemic_updated_on  
            WHERE acedemic_year_id = :acedemic_year_id
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:academic_year.php?msg=edit');

		}
	}
}

if(isset($_GET['action'], $_GET['id'], $_GET['status']) && $_GET['action'] == 'delete')
{
	$academic_year_id = $_GET['id'];

	$status = $_GET['status'];

	$data = array(
		':acedemic_year_status'			=>	$status,
		':acedemic_year_id'				=>	$academic_year_id
	);

	$query = "
	UPDATE sfms_acedemic_year 
	SET acedemic_year_status = :acedemic_year_status 
	WHERE acedemic_year_id = :acedemic_year_id
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	header('location:academic_year.php?msg='.$status.'');

}


include('header.php');

?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Academic Year Management</h1>
	<?php 

	if(isset($_GET["action"]))
	{
		if($_GET["action"] == 'add')
		{
	?>

	<ol class="breadcrumb mb-4">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item"><a href="academic_year.php">Academic Management</a></li>
		<li class="breadcrumb-item active">Add Academic Year</li>
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
					<i class="fas fa-user-plus"></i> Add Academic Year
				</div>
				<div class="card-body">
					<form method="post">
						<div class="mb-3">
							<label>Select Start Year <span class="text-danger">*</span></label>
							<select name="acedemic_start_year" class="form-control">
								<option value="">Select Start Year</option>
								<?php
								for($i = date('Y'); $i <= date('Y') + 5; $i++)
								{
									echo '<option value="'.$i.'">'.$i.'</option>';
								}
								?>
							</select>
						</div>
						<div class="mb-3">
							<label>Select Start Month <span class="text-danger">*</span></label>
							<select name="acedemic_start_month" class="form-control">
								<option value="">Select Start Month</option>
								<?php 
								for($i = 0; $i < count($month_array); $i++)
								{
									echo '<option value="'.$month_array[$i].'">'.$month_array[$i].'</option>';
								}
								?>
							</select>
						</div>
						<div class="mb-3">
							<label>Select End Year <span class="text-danger">*</span></label>
							<select name="acedemic_end_year" class="form-control">
								<option value="">Select End Year</option>
								<?php 
								for($i = date('Y') + 1; $i <= date('Y') + 6; $i++)
								{
									echo '<option value="'.$i.'">'.$i.'</option>';
								}
								?>
							</select>
						</div>
						<div class="mb-3">
						 	<label>Select End Month <span class="text-danger">*</span></label>
						 	<select name="acedemic_end_month" class="form-control">
						 		<option value="">Select End Month</option>
						 		<?php
						 		for($i = 0; $i < count($month_array); $i++)
						 		{
						 			echo '<option value="'.$month_array[$i].'">'.$month_array[$i].'</option>';
						 		}
						 		?>
						 	</select>
						</div>
						<div class="mt-4 mb-0">
							<input type="submit" name="add_academic_year" class="btn btn-success" value="Add" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php
		}
		else if($_GET["action"] == 'edit')
		{
			if(isset($_GET["id"]))
			{
				$query = "
				SELECT * FROM sfms_acedemic_year 
				WHERE acedemic_year_id = '".$_GET["id"]."'
				";

				$academic_year_array = $connect->query($query, PDO::FETCH_ASSOC);

				foreach($academic_year_array as $academic_year_row)
				{


	?>

	<ol class="breadcrumb mb-4">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item"><a href="academic_year.php">Academic Year Management</a></li>
		<li class="breadcrumb-item active">Edit Academic Year</li>
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
					<i class="fas fa-user-edit"></i> Edit Academic Year Details
				</div>
				<div class="card-body">
					<form method="post">
						<div class="mb-3">
							<label>Select Start Year <span class="text-danger">*</span></label>
							<select name="acedemic_start_year" id="acedemic_start_year" class="form-control">
								<option value="">Select Start Year</option>
								<?php
								for($i = date('Y'); $i <= date('Y') + 5; $i++)
								{
									echo '<option value="'.$i.'">'.$i.'</option>';
								}
								?>
							</select>
						</div>
						<div class="mb-3">
							<label>Select Start Month <span class="text-danger">*</span></label>
							<select name="acedemic_start_month" id="acedemic_start_month" class="form-control">
								<option value="">Select Start Month</option>
								<?php 
								for($i = 0; $i < count($month_array); $i++)
								{
									echo '<option value="'.$month_array[$i].'">'.$month_array[$i].'</option>';
								}
								?>
							</select>
						</div>
						<div class="mb-3">
							<label>Select End Year <span class="text-danger">*</span></label>
							<select name="acedemic_end_year" id="acedemic_end_year" class="form-control">
								<option value="">Select End Year</option>
								<?php 
								for($i = date('Y') + 1; $i <= date('Y') + 6; $i++)
								{
									echo '<option value="'.$i.'">'.$i.'</option>';
								}
								?>
							</select>
						</div>
						<div class="mb-3">
						 	<label>Select End Month <span class="text-danger">*</span></label>
						 	<select name="acedemic_end_month" id="acedemic_end_month" class="form-control">
						 		<option value="">Select End Month</option>
						 		<?php
						 		for($i = 0; $i < count($month_array); $i++)
						 		{
						 			echo '<option value="'.$month_array[$i].'">'.$month_array[$i].'</option>';
						 		}
						 		?>
						 	</select>
						</div>
						<div class="mt-4 mb-0">
							<input type="hidden" name="acedemic_year_id" value="<?php echo $academic_year_row["acedemic_year_id"]; ?>" />
							<input type="submit" name="edit_academic_year" class="btn btn-success" value="Edit" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script>

		$('#acedemic_start_year').val('<?php echo $academic_year_row["acedemic_start_year"]; ?>');

		$('#acedemic_start_month').val('<?php echo $academic_year_row["acedemic_start_month"]; ?>');

		$('#acedemic_end_year').val('<?php echo $academic_year_row["acedemic_end_year"]; ?>');

		$('#acedemic_end_month').val('<?php echo $academic_year_row["acedemic_end_month"]; ?>');

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
        <li class="breadcrumb-item active">Academic Year Management</li>
    </ol>
    <?php

    if(isset($_GET['msg']))
    {
    	if($_GET['msg'] == 'add')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New Academic Year Data Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}

    	if($_GET['msg'] == 'edit')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Academic Year Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}

    	if($_GET['msg'] == 'Enable' || $_GET['msg'] == 'Disable')
    	{
    		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Academic Year Data Status Change to '.$_GET["msg"].' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    	}
    }

    ?>
    <div class="card mb-4">
    	<div class="card-header">
    		<div class="row">
    			<div class="col col-md-6">
    				<i class="fas fa-table me-1"></i> Academic Year Management
    			</div>
    			<div class="col col-md-6" align="right">
    				<a href="academic_year.php?action=add" class="btn btn-success btn-sm">Add</a>
    			</div>
    		</div>
    	</div>
    	<div class="card-body">
    		<table id="academic_year_data" class="table table-bordered table-striped">
    			<thead>
    				<tr>
    					<th>Start Year</th>
                        <th>Start Month</th>
                        <th>End Year</th>
                        <th>End Month</th>
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

var datatable = $('#academic_year_data').DataTable({
	'processing' : true,
	'serverSide' : true,
	'order' : [],
	'ajax' : {
		url : 'action.php',
		type : 'POST',
		data : {action : 'fetch_academic_year'}
	}
});

function delete_data(id, status)
{
	var new_status = 'Enable';

	if(status == 'Enable')
	{
		new_status = 'Disable';
	}

	if(confirm("Are you sure you want to "+new_status+" this Academic Year Data?"))
	{
		window.location.href = 'academic_year.php?action=delete&id='+id+'&status='+new_status;
	}
}

</script>