
<?php

//index.php

include('database_connection.php');

if(!is_login())
{
	header('location:login.php');
}

include('header.php');

?>

							<div class="container-fluid px-4">
		                        <h1 class="mt-4">Dashboard</h1>
		                        <ol class="breadcrumb mb-4">
				                    <li class="breadcrumb-item active">Dashboard</li>
				                </ol>
		                        
		                        <div class="row">
		                            <div class="col-xl-3 col-md-6">
		                                <div class="card bg-primary text-white mb-4">
		                                    <div class="card-body">
		                                    	<h2 class="text-center"><?php echo get_total_student_records($connect); ?></h2>
		                                    	<h5 class="text-center">Total Student</h5>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="col-xl-3 col-md-6">
		                                <div class="card bg-warning text-white mb-4">
		                                    <div class="card-body">
		                                    	<h2 class="text-center"><?php echo get_total_user_all_records($connect); ?></h2>
		                                    	<h5 class="text-center">Total User</h5>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="col-xl-3 col-md-6">
		                                <div class="card bg-danger text-white mb-4">
		                                    <div class="card-body">
		                                    	<h2 class="text-center"><?php echo get_total_student_standard_records($connect); ?></h2>
		                                    	<h5 class="text-center">Total Standard</h5>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="col-xl-3 col-md-6">
		                                <div class="card bg-success text-white mb-4">
		                                    <div class="card-body">
		                                    	<h2 class="text-center"><?php echo '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>' . number_format(get_total_fees_received_data($connect), 2, '.', ''); ?></h2>
		                                    	<h5 class="text-center">Total Fees Received</h5>
		                                    </div>
		                                </div>
		                            </div>
		                            
		                        </div>

<?php

include('footer.php');

?>