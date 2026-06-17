<?php

//user.php

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

if(isset($_POST["add_user"]))
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
        $query = "
        SELECT * FROM sfms_admin 
        WHERE admin_email = '".$formdata['admin_email']."'
        ";

        $statement = $connect->prepare($query);

        $statement->execute();

        if($statement->rowCount() > 0)
        {
            $error = '<li>Email Address Already Exists</li>';
        }
        else
        {
            $data = array(
                ':admin_name'           =>  $formdata['admin_name'],
                ':admin_email'          =>  $formdata['admin_email'],
                ':admin_password'       =>  $formdata['admin_password'],
                ':admin_type'           =>  'User',
                ':admin_status'         =>  'Enable',
                ':admin_added_on'       =>  time()
            );

            $query = "
            INSERT INTO sfms_admin 
            (admin_name, admin_email, admin_password, admin_type, admin_status, admin_added_on) VALUES (:admin_name, :admin_email, :admin_password, :admin_type, :admin_status, :admin_added_on)
            ";

            $statement = $connect->prepare($query);

            $statement->execute($data);

            header('location:user.php?msg=add');
        }
    }
}

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
        $query = "
        SELECT * FROM sfms_admin 
        WHERE admin_email = '".$formdata['admin_email']."' 
        AND admin_id != '".$_POST['admin_id']."'
        ";

        $statement = $connect->prepare($query);

        $statement->execute();

        if($statement->rowCount() > 0)
        {
            $error = '<li>Email Address Already Exists</li>';
        }
        else
        {
            $data = array(
                ':admin_name'       =>  $formdata['admin_name'],
                ':admin_email'      =>  $formdata['admin_email'],
                ':admin_password'   =>  $formdata['admin_password'],
                ':admin_id'         =>  $_POST['admin_id']
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

            header('location:user.php?msg=edit');
        }
    }
}


if(isset($_GET["action"], $_GET["id"], $_GET["status"]) && $_GET['action'] == 'delete')
{
    $admin_id = $_GET['id'];

    $status = $_GET['status'];

    $data = array(
        ':admin_status'     =>  $status,
        ':admin_id'         =>  $admin_id
    );

    $query = "
    UPDATE sfms_admin 
    SET admin_status = :admin_status 
    WHERE admin_id = :admin_id
    ";

    $statement = $connect->prepare($query);

    $statement->execute($data);

    header('location:user.php?msg='.$status.'');
}

include('header.php');


?>
						<div class="container-fluid px-4">
                            <h1 class="mt-4">User Management</h1>
                            <?php
                            if(isset($_GET["action"]))
                            {
                                if($_GET["action"] == 'add')
                                {
                            ?>
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="user.php">User Management</a></li>
                                <li class="breadcrumb-item active">Add User</li>
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
                                            <i class="fas fa-user-plus"></i> Add New User
                                        </div>
                                        <div class="card-body">
                                            <form method="post">
                                                <div class="mb-3">
                                                    <label>User Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="admin_name" class="form-control" />
                                                </div>
                                                <div class="mb-3">
                                                    <label>Email Address <span class="text-danger">*</span></label>
                                                    <input type="text" name="admin_email" class="form-control" />
                                                </div>
                                                <div class="mb-3">
                                                    <label>Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="admin_password" class="form-control" />
                                                </div>
                                                <div class="mt-4 mb-0">
                                                    <input type="submit" name="add_user" class="btn btn-success" value="Add" />
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
                                        SELECT * FROM sfms_admin 
                                        WHERE admin_id = '".$_GET["id"]."'
                                        ";

                                        $user_result = $connect->query($query, PDO::FETCH_ASSOC);

                                        foreach($user_result as $user_row)
                                        {
                                        ?>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="user.php">User Management</a></li>
                                    <li class="breadcrumb-item active">Edit User</li>
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
                                                <i class="fas fa-user-edit"></i> Edit User Details
                                            </div>
                                            <div class="card-body">
                                                <form method="post">
                                                    <div class="mb-3">
                                                        <label>User Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="admin_name" class="form-control" value="<?php echo $user_row['admin_name']; ?>" />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Email Address <span class="text-danger">*</span></label>
                                                        <input type="text" name="admin_email" class="form-control" value="<?php echo $user_row['admin_email']; ?>" />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Password <span class="text-danger">*</span></label>
                                                        <input type="password" name="admin_password" class="form-control" value="<?php echo $user_row['admin_password']; ?>" />
                                                    </div>
                                                    <div class="mt-4 mb-0">
                                                        <input type="hidden" name="admin_id" value="<?php echo $user_row['admin_id']; ?>" />
                                                        <input type="submit" name="edit_user" class="btn btn-success" value="Edit" />
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
                                <li class="breadcrumb-item active">User Management</li>
                            </ol>
                            <?php

                            if(isset($_GET['msg']))
                            {
                                if($_GET['msg'] == 'add')
                                {
                                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New User Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                }

                                if($_GET["msg"] == 'edit')
                                {
                                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">User Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                }

                                if($_GET['msg'] == 'Enable' || $_GET['msg'] == 'Disable')
                                {
                                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">User Status Change to '.$_GET["msg"].' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                }
                            }

                            ?>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col col-md-6">
                                            <i class="fas fa-table me-1"></i> User Management
                                        </div>
                                        <div class="col col-md-6" align="right">
                                            <a href="user.php?action=add" class="btn btn-success btn-sm">Add</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                	<table id="user_data" class="table table-bordered table-striped">
                                		<thead>
                                			<tr>
                                				<th>User Name</th>
                                                <th>Email Address</th>
                                                <th>Password</th>
                                                <th>User Type</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                			</tr>
                                		</thead>
                                	</table>
                                </div>
                            </div>

                            <script>

                                var userdatatable = $('#user_data').DataTable({
                                    "processing" : true,
                                    "serverSide" : true,
                                    "order" : [],
                                    "ajax" : {
                                        url : 'action.php',
                                        type : 'post',
                                        data : {action : 'fetch_user'}
                                    }
                                });

                                function delete_data(id, status)
                                {
                                    var new_status = 'Enable';

                                    if(status == 'Enable')
                                    {
                                        new_status = 'Disable';
                                    }

                                    if(confirm("Are you sure you want to "+new_status+" this User?"))
                                    {
                                        window.location.href = 'user.php?action=delete&id='+id+'&status='+new_status;
                                    }
                                }

                            </script>
                            <?php
                            }
                            ?>
                        </div>
<?php

include('footer.php');

?>

