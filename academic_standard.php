<?php

//academic_standard.php

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

if(isset($_POST['add_academic_standard']))
{	
	$formdata = array();

	if(empty($_POST['acedemic_standard_name']))
	{
		$error .= '<li>Standard Name is required</li>';
	}
	else
	{
		$formdata['acedemic_standard_name'] = trim($_POST['acedemic_standard_name']);
	}

	if(empty($_POST['acedemic_standard_division']))
	{
		$error .= '<li>Standard Division is required</li>';
	}
	else
	{
		$formdata['acedemic_standard_division'] = trim($_POST['acedemic_standard_division']);
	}

	if($error == '')
	{
		$query = "
		SELECT * FROM sfms_acedemic_standard 
		WHERE acedemic_standard_name = '".$formdata['acedemic_standard_name']."' 
		AND acedemic_standard_division = '".$formdata['acedemic_standard_division']."' 
		AND acedemic_standard_status = 'Enable'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Academic Standard Data Already Exists</li>';
		}
		else
		{
			$data = array(
				':acedemic_standard_name'		=>	$formdata['acedemic_standard_name'],
				':acedemic_standard_division'	=>	$formdata['acedemic_standard_division'],
				':acedemic_standard_status'		=>	'Enable',
				':acedemic_standard_added_on'	=>	time()
			);

			$query = "
			INSERT INTO sfms_acedemic_standard 
			(acedemic_standard_name, acedemic_standard_division, acedemic_standard_status, acedemic_standard_added_on) VALUES (:acedemic_standard_name, :acedemic_standard_division, :acedemic_standard_status, :acedemic_standard_added_on)
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:academic_standard.php?msg=add');
		}
	}
}

if(isset($_POST['edit_academic_standard']))
{
	$formdata = array();

	if(empty($_POST['acedemic_standard_name']))
	{
		$error .= '<li>Standard Name is required</li>';
	}
	else
	{
		$formdata['acedemic_standard_name'] = trim($_POST['acedemic_standard_name']);
	}

	if(empty($_POST['acedemic_standard_division']))
	{
		$error .= '<li>Standard Division is required</li>';
	}
	else
	{
		$formdata['acedemic_standard_division'] = trim($_POST['acedemic_standard_division']);
	}

	if($error == '')
	{
		$query = "
		SELECT * FROM sfms_acedemic_standard 
		WHERE acedemic_standard_name = '".$formdata['acedemic_standard_name']."' 
		AND acedemic_standard_division = '".$formdata['acedemic_standard_division']."' 
		AND acedemic_standard_status = 'Enable' 
        AND acedemic_standard_id != '".$_POST['acedemic_standard_id']."'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			$error = '<li>Academic Standard Data Already Exists</li>';
		}
		else
		{
			$data = array(
				':acedemic_standard_name'		=>	$formdata['acedemic_standard_name'],
				':acedemic_standard_division'	=>	$formdata['acedemic_standard_division'],
				':acedemic_standard_updated_on'	=>	time(),
				':acedemic_standard_id'			=>	$_POST['acedemic_standard_id']
			);

			$query = "
			UPDATE sfms_acedemic_standard 
			SET acedemic_standard_name = :acedemic_standard_name, 
			acedemic_standard_division = :acedemic_standard_division,
			acedemic_standard_updated_on = :acedemic_standard_updated_on 
			WHERE acedemic_standard_id = :acedemic_standard_id
			";

			$statement = $connect->prepare($query);

			$statement->execute($data);

			header('location:academic_standard.php?msg=edit');
		}
	}
}

if(isset($_GET['action'], $_GET['id'], $_GET['status']) && $_GET['action'] == 'delete')
{
	$acedemic_standard_id = $_GET['id'];

	$status = trim($_GET["status"]);

	$data = array(
		':acedemic_standard_status'		=>	$status,
		':acedemic_standard_id'			=>	$acedemic_standard_id
	);

	$query = "
	UPDATE sfms_acedemic_standard 
	SET acedemic_standard_status = :acedemic_standard_status 
	WHERE acedemic_standard_id = :acedemic_standard_id
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	header('location:academic_standard.php?msg=' . $status);
}

include('header.php');

?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Academic Standard Management</h1>
	<?php
	if(isset($_GET["action"]))
	{
		if($_GET["action"] == 'add')
		{
	?>
	<ol class="breadcrumb mb-4">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item"><a href="academic_standard.php">Academic Standard Management</a></li>
		<li class="breadcrumb-item active">Add Academic Standard</li>
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
					<i class="fas fa-user-plus"></i> Add Academic Standard
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="mb-3">
							<label>Enter Standard Name <span class="text-danger">*</span></label>
							<input type="text" name="acedemic_standard_name" class="form-control" />
						</div>
						<div class="mb-3">
							<label>Select Division <span class="text-danger">*</span></label>
							<select name="acedemic_standard_division" class="form-control">
								<option value="">Select Division</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
							</select>
						</div>
						<div class="mt-4 mb-0">
							<input type="submit" name="add_academic_standard" class="btn btn-success" value="Add" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
		}
		else if($_GET['action'] == 'edit')
		{
			if(isset($_GET['id']))
			{
				$query = "
				SELECT * FROM sfms_acedemic_standard 
				WHERE acedemic_standard_id = '".$_GET["id"]."'
				";

				$academic_standard_result = $connect->query($query, PDO::FETCH_ASSOC);

				foreach($academic_standard_result as $academic_standard_row)
				{
	?>
	<ol class="breadcrumb mb-4">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item"><a href="academic_standard.php">Academic Standard Management</a></li>
		<li class="breadcrumb-item active">Edit Academic Standard</li>
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
					<i class="fas fa-user-edit"></i> Edit Academic Standard Details
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="mb-3">
							<label>Enter Standard Name <span class="text-danger">*</span></label>
							<input type="text" name="acedemic_standard_name" class="form-control" value="<?php echo $academic_standard_row["acedemic_standard_name"]; ?>" />
						</div>
						<div class="mb-3">
							<label>Select Division <span class="text-danger">*</span></label>
							<select name="acedemic_standard_division" id="acedemic_standard_division" class="form-control">
								<option value="">Select Division</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
							</select>
						</div>
						<div class="mt-4 mb-0">
							<input type="hidden" name="acedemic_standard_id" value="<?php echo $academic_standard_row['acedemic_standard_id'];?>" />
							<input type="submit" name="edit_academic_standard" class="btn btn-success" value="Edit" />
						</div>
					</form>
					<script>
						$('#acedemic_standard_division').val("<?php echo $academic_standard_row["acedemic_standard_division"]; ?>");
					</script>
				</div>
			</div>
		</div>
	</div>
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
		<li class="breadcrumb-item active">Academic Standard Management</li>
	</ol>
	<?php 

	if(isset($_GET['msg']))
	{
		if($_GET['msg'] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New Academic Standard Data Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}

		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Academic Standard Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}

		if($_GET['msg'] == 'Enable' || $_GET['msg'] == 'Disable')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Academic Standard Data Status Change to '.$_GET["msg"].' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}

	?>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Academic Standard Management
				</div>
				<div class="col col-md-6" align="right">
					<a href="academic_standard.php?action=add" class="btn btn-success btn-sm">Add</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<table id="academic_standard_data" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Standard Name</th>
						<th>Division</th>
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

var dataTable = $('#academic_standard_data').DataTable({
	"processing" : true,
	"serverSide" : true,
	"order" : [],
	"ajax" : {
		url : "action.php",
		type : "POST",
		data : {action : "fetch_academic_standard"}
	}
});

function delete_data(id, status)
{
	var new_status = 'Enable';

	if(status == 'Enable')
	{
		new_status = 'Disable';
	}

	if(confirm("Are you sure you want to "+new_status+" this Academic Standard Data?"))
	{
		window.location.href = 'academic_standard.php?action=delete&id='+id+'&status='+new_status;
	}
}

</script>

<?php

include('footer.php');

?>