<?php

//login.php

include('database_connection.php');

if(is_login())
{
    header('location:index.php');
}

$message = '';

if(isset($_POST['login_button']))
{
    $formdata = array();

    if(empty($_POST['admin_email']))
    {
        $message .= '<li>Email Address is required</li>';
    }
    else
    {
        if(!filter_var($_POST["admin_email"], FILTER_VALIDATE_EMAIL))
        {
            $message .= '<li>Invalid Email Address</li>';
        }
        else
        {
            $formdata['admin_email'] = trim($_POST['admin_email']);
        }
    }

    if(empty($_POST["admin_password"]))
    {
        $message .= '<li>Password is required</li>';
    }
    else
    {
        $formdata['admin_password'] = trim($_POST['admin_password']);
    }

    if($message == '')
    {
        $data = array(
            ':admin_email'      =>  $formdata['admin_email']
        );

        $query = "
        SELECT * FROM sfms_admin 
        WHERE admin_email = :admin_email 
        ";

        $statement = $connect->prepare($query);

        $statement->execute($data);

        if($statement->rowCount() > 0)
        {
            foreach($statement->fetchAll() as $row)
            {
                if($row["admin_status"] == 'Enable')
                {
                    if($row['admin_password'] == $formdata['admin_password'])
                    {
                        $_SESSION['user_type'] = $row['admin_type'];
                        $_SESSION['admin_id'] = $row['admin_id'];
                        header('location:dashboard.php');
                    }
                    else
                    {
                        $message = '<li>Wrong Password</li>';
                    }
                }
                else
                {
                    $message = '<li>Your Account has been disabled</li>';
                }
            }
        }
        else
        {
            $message = '<li>Wrong Email Address</li>';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login | School Fees Management System</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-light">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <br />
                                <br />
                                <h3 class="text-center text-danger mt-5"><b>Welcome Kool Kids!</b></h3>
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Admin Login</h3></div>
                                    <div class="card-body">
                                        <?php 

                                        if($message != '')
                                        {
                                            echo '<div class="alert alert-danger"><ul class="list-unstyled">'.$message.'</ul></div>';
                                        }

                                        ?>
                                        <form method="post">
                                            <div class="mb-3">
                                                <label>Username</label>
                                                <input type="text" name="admin_email" class="form-control" />
                                            </div>
                                            <div class="mb-3">
                                                <label>Password</label>
                                                <input type="password" name="admin_password" class="form-control" />
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <input type="submit" name="login_button" class="btn btn-primary" value="Login" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; IT OF INDI MODASA <?php echo date('Y'); ?></div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo $base_url; ?>js/scripts.js"></script>
    </body>
</html>
