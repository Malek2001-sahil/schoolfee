<?php

//setting.php

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
$setting_id = '';
$school_name = '';
$school_address = '';
$school_email_address = '';
$school_website = '';
$school_contact_number = '';

if(isset($_POST["submit"]))
{
	$data = array(
		':school_name'		=>	$_POST['school_name'],
		':school_address'	=>	$_POST['school_address'],
		':school_contact_number'	=>	$_POST['school_contact_number'],
		':school_email_address'		=>	$_POST['school_email_address'],
		':school_website'			=>	$_POST['school_website']
	);

	if($_POST['setting_id'] == '')
	{
		$query = "
		INSERT INTO sfms_setting 
		(school_name, school_address, school_email_address, school_website, school_contact_number) VALUES (:school_name, :school_address, :school_email_address, :school_website, :school_contact_number)
		";
	}
	else
	{
		$query = "
		UPDATE sfms_setting 
            SET school_name = :school_name, 
            school_address = :school_address, 
            school_email_address = :school_email_address, 
            school_website = :school_website, 
            school_contact_number = :school_contact_number 
            WHERE setting_id = '".$_POST["setting_id"]."'
		";
	}

	$statement = $connect->prepare($query);

	$statement->execute($data);

	$message = '<div class="alert alert-success">Data Successfully Change</div>';

}

$query = "
	SELECT * FROM sfms_setting 
    LIMIT 1
";

$result = $connect->query($query, PDO::FETCH_ASSOC);

foreach($result as $row)
{
	$school_name = $row['school_name'];

	$school_address = $row['school_address'];

	$school_email_address = $row['school_email_address'];

	$school_website = $row['school_website'];

	$school_contact_number = $row['school_contact_number'];

	$setting_id = $row['setting_id'];
}


include('header.php');

?>

						<div class="container-fluid px-4">
                            <h1 class="mt-4">Setting</h1>
                            
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Setting</li>
                            </ol>
                            <div class="row">
                                <div class="col-md-6">
                                	<?php 
                                	echo $message;
                                	?>
                                	<div class="card mb-4">
                                        <div class="card-header">
                                            <i class="fas fa-user-edit"></i> Setting
                                        </div>
                                        <div class="card-body">
                                        	<form method="post">
                                        		<div class="mb-3">
                                                    <label>School Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="school_name" class="form-control" value="<?php echo $school_name; ?>" />
                                                </div>
                                                <div class="mb-3">
                                                    <label>Address</label>
                                                    <textarea name="school_address" class="form-control"><?php echo $school_address; ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Contact No.</label>
                                                    <input type="text" name="school_contact_number" class="form-control" value="<?php echo $school_contact_number; ?>" />
                                                </div>
                                                <div class="mb-3">
                                                    <label>Email Address</label>
                                                    <input type="text" name="school_email_address" class="form-control" value="<?php echo $school_email_address; ?>" />
                                                </div>
                                                <div class="mb-3">
                                                    <label>Website</label>
                                                    <input type="text" name="school_website" class="form-control" value="<?php echo $school_website; ?>" />
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                	<input type="hidden" name="setting_id" value="<?php echo $setting_id; ?>" />
                                                	<input type="submit" name="submit" class="btn btn-primary" value="Save" />
                                                </div>
                                        	</form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

<?php

include('footer.php');

?>