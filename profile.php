<?php

//profile.php

include('database_connection.php');

if(!is_login())
{
	header('location:login.php');
}

$message = '';

$error = '';

if(isset($_POST["edit_user"]))
{
	$formdata = array();

	if(empty($_POST["admin_name"]))
	{
		$error .= '<li>User Name is required</li>';
	}
	else
	{
		$formdata['admin_name'] = trim($_POST['admin_name']);
	}

	if(empty($_POST['admin_email']))
	{
		$error .= '<li>Email Address is required</li>';
	}
	else
	{
		if(!filter_var($_POST['admin_email'], FILTER_VALIDATE_EMAIL))
		{
			$error .= '<li>Invalid Email Address</li>';
		}
		else
		{
			$formdata['admin_email'] = trim($_POST['admin_email']);
		}
	}

	if(empty($_POST['admin_password']))
	{
		$error .= '<li>Password is required</li>';
	}	
	else
	{
		$formdata['admin_password'] = trim($_POST['admin_password']);
	}

	if($error == '')
	{
		$user_id = $_SESSION['admin_id'];

		$data = array(
			':admin_name'		=>	$formdata['admin_name'],
			':admin_email'		=>	$formdata['admin_email'],
			':admin_password'	=>	$formdata['admin_password'],
			':admin_id'			=>	$user_id
		);

		$query = "
		UPDATE sfms_admin 
            SET admin_name = :admin_name, 
            admin_email = :admin_email,
            admin_password = :admin_password 
            WHERE admin_id = :admin_id
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		$message = 'User Data Edited';
	}
}

$query = "
	SELECT * FROM sfms_admin 
    WHERE admin_id = '".$_SESSION["admin_id"]."'
";

$result = $connect->query($query, PDO::FETCH_ASSOC);

include('header.php');

?>
						<div class="container-fluid px-4">
                            <h1 class="mt-4">Profile</h1>

                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Profile</a></li>
                            </ol>
                            <div class="row">
                                <div class="col-md-6">
                                	<?php
                                	if($error != '')
                                	{
                                		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                	}

                                	if($message != '')
                                	{
                                		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">User Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                	}

                                	?>
                                	<div class="card mb-4">
                                        <div class="card-header">
                                            <i class="fas fa-user-edit"></i> Edit Profile Details
                                        </div>
                                        <div class="card-body">
		                                <?php
		                                foreach($result as $row)
		                                {
		                                ?>
		                                	<form method="POST">
		                                		<div class="mb-3">
                                                    <label>User Name</label>
                                                    <input type="text" name="admin_name" class="form-control" value="<?php echo $row['admin_name']; ?>" />
                                                </div>
                                                <div class="mb-3">
                                                    <label>Email Address</label>
                                                    <input type="text" name="admin_email" class="form-control" value="<?php echo $row['admin_email']; ?>" />
                                                </div>
                                                <div class="mb-3">
                                                    <label>Password</label>
                                                    <input type="password" name="admin_password" class="form-control" value="<?php echo $row['admin_password']; ?>" />
                                                </div>
                                                <div class="mt-4 mb-0">
                                                	<input type="submit" name="edit_user" class="btn btn-primary" value="Edit" />
                                                </div>
		                                	</form>
		                                <?php
		                                }
		                                ?>
		                            	</div>
		                            </div>
                            	</div>
                            </div>
                        </div>

<?php

include('footer.php');

?>