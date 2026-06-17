<?php

//student.php

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

if(isset($_POST['add_student']))
{
	$formdata = array();

	$query = "SELECT MAX(student_id) AS ID FROM sfms_student";

	$result = $connect->query($query, PDO::FETCH_ASSOC);

	foreach($result as $row)
	{
		if($row['ID'] > 0)
		{
			$max_student_id = $row['ID'];
		}
		else
		{
			$max_student_id = 0;
		}
	}

	$formdata['student_number'] = Generate_student_number($max_student_id);

	if(empty($_POST['student_name']))
	{
		$error .= '<li>Student Name is required</li>';
	}
	else
	{
		$formdata['student_name'] = trim($_POST['student_name']);
	}

	if(empty($_POST['student_father_name']))
	{
		$error .= '<li>Father Name is required</li>';
	}
	else
	{
		$formdata['student_father_name'] = trim($_POST['student_father_name']);
	}

	if(empty($_POST['student_mother_name']))
	{
		$error .= '<li>Mother Name is required</li>';
	}
	else
	{
		$formdata['student_mother_name'] = trim($_POST['student_mother_name']);
	}

	if(empty($_POST['student_date_of_birth']))
	{
		$error .= '<li>Date of Birth is required</li>';
	}
	else
	{
		$formdata['student_date_of_birth'] = trim($_POST['student_date_of_birth']);
	}

	if(empty($_POST['student_address']))
	{
		$error .= '<li>Address is required</li>';
	}
	else
	{
		$formdata['student_address'] = trim($_POST['student_address']);
	}

	if(empty($_POST['student_date_of_addmission']))
	{
		$error .= '<li>Addmission Date is required</li>';
	}
	else
	{
		$formdata['student_date_of_addmission'] = trim($_POST['student_date_of_addmission']);
	}

	if(empty($_POST['student_contact_number1']))
	{
		$error .= '<li>Contact Number is required</li>';
	}
	else
	{
		$formdata['student_contact_number1'] = trim($_POST['student_contact_number1']);
	}

	$formdata['student_contact_number2'] = trim($_POST['student_contact_number2']);

	if(!empty($_FILES['student_image']['name']))
	{
		$image_name = $_FILES['student_image']['name'];

		$image_type = $_FILES['student_image']['type'];

		$temporary_name = $_FILES['student_image']['tmp_name'];

		$image_size = $_FILES['student_image']['size'];

		$image_explode = explode(".", $image_name);

		$image_extension = strtolower(end($image_explode));

		$extensions = array("jpeg", "png", "jpg");

		if(in_array($image_extension, $extensions))
		{
			if($image_size <= 2000000)
			{
				$new_image_name = time() . '-'. rand() . '.' . $image_extension;

				move_uploaded_file($temporary_name, "upload/" . $new_image_name);

				$formdata['student_image'] = $new_image_name;
			}
			else
			{
				$error .= '<li>Image size exceeds 2MB</li>';
			}
		}
		else
		{
			$error .= '<li>Invalid Image File</li>';
		}
	}

	if($error == '')
	{
		$data = array(
			':student_number'		=>	$formdata['student_number'],
			':student_name'			=>	$formdata['student_name'],
			':student_father_name'	=>	$formdata['student_father_name'],
			':student_mother_name'	=>	$formdata['student_mother_name'],
			':student_date_of_birth'	=>	$formdata['student_date_of_birth'],
			':student_address'		=>	$formdata['student_address'],
			':student_date_of_addmission'	=>	$formdata['student_date_of_addmission'],
			':student_contact_number1'	=>	$formdata['student_contact_number1'],
			':student_contact_number2'	=>	$formdata['student_contact_number2'],
			':student_image'		=>	$formdata['student_image'],
			':student_added_on'		=>	time()
		);

		$query = '
		INSERT INTO sfms_student 
		(student_number, student_name, student_father_name, student_mother_name, student_date_of_birth, student_address, student_date_of_addmission, student_contact_number1, student_contact_number2, student_image, student_added_on ) VALUES (:student_number, :student_name, :student_father_name, :student_mother_name, :student_date_of_birth, :student_address, :student_date_of_addmission, :student_contact_number1, :student_contact_number2, :student_image, :student_added_on)
		';

		$statement = $connect->prepare($query);

		$statement->execute($data);

		header('location:student.php?msg=add');
	}
}

if(isset($_POST['edit_student']))
{
	$formdata = array();

	if(empty($_POST['student_name']))
	{
		$error .= '<li>Student Name is required</li>';
	}
	else
	{
		$formdata['student_name'] = trim($_POST['student_name']);
	}

	if(empty($_POST['student_father_name']))
	{
		$error .= '<li>Father Name is required</li>';
	}
	else
	{
		$formdata['student_father_name'] = trim($_POST['student_father_name']);
	}

	if(empty($_POST['student_mother_name']))
	{
		$error .= '<li>Mother Name is required</li>';
	}
	else
	{
		$formdata['student_mother_name'] = trim($_POST['student_mother_name']);
	}

	if(empty($_POST['student_date_of_birth']))
	{
		$error .= '<li>Date of Birth is required</li>';
	}
	else
	{
		$formdata['student_date_of_birth'] = trim($_POST['student_date_of_birth']);
	}

	if(empty($_POST['student_address']))
	{
		$error .= '<li>Address is required</li>';
	}
	else
	{
		$formdata['student_address'] = trim($_POST['student_address']);
	}

	if(empty($_POST['student_date_of_addmission']))
	{
		$error .= '<li>Addmission Date is required</li>';
	}
	else
	{
		$formdata['student_date_of_addmission'] = trim($_POST['student_date_of_addmission']);
	}

	if(empty($_POST['student_contact_number1']))
	{
		$error .= '<li>Contact Number is required</li>';
	}
	else
	{
		$formdata['student_contact_number1'] = trim($_POST['student_contact_number1']);
	}

	$formdata['student_contact_number2'] = trim($_POST['student_contact_number2']);

	$formdata['student_image'] = $_POST['hidden_student_image'];

	if(!empty($_FILES['student_image']['name']))
	{
		$image_name = $_FILES['student_image']['name'];
		$image_type = $_FILES['student_image']['type'];
		$temporary_name = $_FILES['student_image']['tmp_name'];
		$image_size = $_FILES['student_image']['size'];
		$image_explode = explode('.', $image_name);
		$image_extension = end($image_explode);
		$extensions = array("jpeg", "png", "jpg");

		if(in_array($image_extension, $extensions))
		{
			if($image_size < 2000000)
			{
				$new_image_name = time() . '-' . rand() . '.' . $image_extension;

				move_uploaded_file($temporary_name, "upload/" . $new_image_name);

				$formdata['student_image'] = $new_image_name;
			}
			else
			{
				$error .= '<li>Image size exceeds 2MB</li>';
			}
		}
		else
		{
			$error .= '<li>Invalid Image File</li>';
		}
	}

	if($error == '')
	{
		$data = array(
			':student_name'			=>	$formdata['student_name'],
			':student_father_name'	=>	$formdata['student_father_name'],
			':student_mother_name'	=>	$formdata['student_mother_name'],
			':student_date_of_birth'	=>	$formdata['student_date_of_birth'],
			':student_address'		=>	$formdata['student_address'],
			':student_date_of_addmission'	=>	$formdata['student_date_of_addmission'],
			':student_contact_number1'	=>	$formdata['student_contact_number1'],
			':student_contact_number2'	=>	$formdata['student_contact_number2'],
			':student_image'		=>	$formdata['student_image'],
			':student_updated_on'	=>	time(),
			':student_id'			=>	$_POST['student_id']
		);

		$query = "
		UPDATE sfms_student 
		SET student_name = :student_name, 
		student_father_name = :student_father_name, 
		student_mother_name = :student_mother_name, 
		student_date_of_birth = :student_date_of_birth, 
		student_address = :student_address, 
		student_date_of_addmission = :student_date_of_addmission, 
		student_contact_number1 = :student_contact_number1, 
		student_contact_number2 = :student_contact_number2, 
		student_image = :student_image, 
		student_updated_on = :student_updated_on 
		WHERE student_id = :student_id
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		header('location:student.php?msg=edit');
	}
}

include('header.php');

?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Student Management</h1>
	<?php

	if(isset($_GET['action']))
	{
		if($_GET['action'] == 'add')
		{
	?>
	
	<ol class="breadcrumb mb-4">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item"><a href="student.php">Student Management</a></li>
		<li class="breadcrumb-item active">Add Student</li>
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
					<i class="fas fa-user-plus"></i> Add New Student
				</div>
				<div class="card-body">
					<form method="post" enctype="multipart/form-data">
						<div class="mb-3">
							<label>Student Name <span class="text-danger">*</span></label>
							<input type="text" name="student_name" class="form-control" />
						</div>
						<div class="mb-3">
							<label>Father Name <span class="text-danger">*</span></label>
							<input type="text" name="student_father_name" class="form-control" />
						</div>
						<div class="mb-3">
							<label>GR NO <span class="text-danger">*</span></label>
							<input type="text" name="student_mother_name" class="form-control" />
						</div>
						<div class="mb-3">
							<label>Student Date of Birth <span class="text-danger">*</span></label>
							<input type="text" name="student_date_of_birth" class="form-control select_date" readonly />
						</div>
						<div class="mb-3">
							<label>Address <span class="text-danger">*</span></label>
							<textarea name="student_address" class="form-control"></textarea>
						</div>
						<div class="mb-3">
							<label>Date of Addmission <span class="text-danger">*</span></label>
							<input type="text" name="student_date_of_addmission" class="form-control select_date" readonly />
						</div>
						<div class="mb-3">
							<label>Contact Number 1 <span class="text-danger">*</span></label>
							<input type="text" name="student_contact_number1" class="form-control" />
						</div>
						<div class="mb-3">
							<label>Contact Number 2 <span class="text-muted">(optional)</span></label>
							<input type="text" name="student_contact_number2" class="form-control" />
						</div>
						<div class="mb-3">
							<label>Image</label><br />
							<input type="file" name="student_image" /> <br />
                            <span class="text-muted">Only .jpg & .png file allowed</span>
						</div>
						<div class="mt-4 mb-0">
							<input type="submit" name="add_student" value="Add" class="btn btn-success" />
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
			if(isset($_GET['id']))
			{
				$query = "
				SELECT * FROM sfms_student 
				WHERE student_id = '".$_GET["id"]."'
				";

				$student_result = $connect->query($query, PDO::FETCH_ASSOC);

				foreach($student_result as $student_row)
				{
	?>
	<ol class="breadcrumb mb-4">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="user.php">Student Management</a></li>
        <li class="breadcrumb-item active">Edit Student</li>
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
    				<i class="fas fa-user-edit"></i> Edit Student Details
                </div>
                <div class="card-body">
                	<form method="post" enctype="multipart/form-data">
						<div class="mb-3">
							<label>Student Name <span class="text-danger">*</span></label>
							<input type="text" name="student_name" class="form-control" value="<?php echo $student_row["student_name"]; ?>" />
						</div>
						<div class="mb-3">
							<label>Father Name <span class="text-danger">*</span></label>
							<input type="text" name="student_father_name" class="form-control" value="<?php echo $student_row["student_father_name"]; ?>" />
						</div>
						<div class="mb-3">
							<label>GR NO <span class="text-danger">*</span></label>
							<input type="text" name="student_mother_name" class="form-control" value="<?php echo $student_row["student_mother_name"]; ?>" />
						</div>
						<div class="mb-3">
							<label>Student Date of Birth <span class="text-danger">*</span></label>
							<input type="text" name="student_date_of_birth" class="form-control select_date" value="<?php echo $student_row["student_date_of_birth"]; ?>" readonly />
						</div>
						<div class="mb-3">
							<label>Address <span class="text-danger">*</span></label>
							<textarea name="student_address" class="form-control"><?php echo $student_row["student_address"]; ?></textarea>
						</div>
						<div class="mb-3">
							<label>Date of Addmission <span class="text-danger">*</span></label>
							<input type="text" name="student_date_of_addmission" class="form-control select_date" value="<?php echo $student_row["student_date_of_addmission"]; ?>" readonly />
						</div>
						<div class="mb-3">
							<label>Contact Number 1 <span class="text-danger">*</span></label>
							<input type="text" name="student_contact_number1" class="form-control" value="<?php echo $student_row['student_contact_number1'];  ?>" />
						</div>
						<div class="mb-3">
							<label>Contact Number 2 <span class="text-muted">(optional)</span></label>
							<input type="text" name="student_contact_number2" class="form-control" value="<?php echo $student_row['student_contact_number2'];  ?>" />
						</div>
						<div class="mb-3">
							<label>Image</label><br />
							<input type="file" name="student_image" /> <br />
                            <span class="text-muted">Only .jpg & .png file allowed</span><br />
                            <?php 
                            if($student_row['student_image'] != '')
                            {
                            	echo '<img src="upload/'.$student_row["student_image"].'" class="img-thumbnail" width="100" />';
                            	echo '<input type="hidden" name="hidden_student_image" value="'.$student_row["student_image"].'" />';
                            }
                            ?>
						</div>
						<div class="mt-4 mb-0">
							<input type="hidden" name="student_id" value="<?php echo $student_row['student_id']; ?>" />
							<input type="submit" name="edit_student" value="Edit" class="btn btn-success" />
						</div>
					</form>
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
		<li class="breadcrumb-item active">Student Management</li>
	</ol>
	<?php

	if(isset($_GET['msg']))
	{
		if($_GET['msg'] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New Student Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}

		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Student Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}

	?>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Student Management
				</div>
				<div class="col col-md-6" align="right">
					<a href="student.php?action=add" class="btn btn-success btn-sm">Add</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<table id="student_data" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Image</th>
						<th>Student Number</th>
						<th>Student Name</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">

	var datatable = $('#student_data').DataTable({
		'processing' : true,
		'serverSide' : true,
		'order' : [],
		'ajax' : {
			url : 'action.php',
			type : 'post',
			data : {action : 'fetch_student'}
		}
	});

</script>
<?php 
	}
?>

<?php

include('footer.php');

?>

<script>

$('.select_date').daterangepicker({
	singleDatePicker: true,
    showDropdowns: true,
    minYear: 1901,
    maxYear: parseInt(moment().format('YYYY'),10)
});

</script>