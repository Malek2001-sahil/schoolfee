<?php

//action.php

include('database_connection.php');

if(isset($_POST['action']))
{
	if($_POST["action"] == 'fetch_user')
	{
		$query = "
		SELECT * FROM sfms_admin 
		WHERE admin_type = 'User' AND 
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '(admin_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR admin_email LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR admin_status LIKE "%'.$_POST["search"]["value"].'%") ';
		}

		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY admin_id DESC ';
		}

		$query1 = '';

		if($_POST['length'] != -1)
		{
			$query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$filtered_rows = $statement->rowCount();

		$result = $connect->query($query . $query1);

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$status = '';

			$delete_button = '';

			if($row['admin_status'] == 'Enable')
			{
				$status = '<div class="badge bg-success">Enable</div>';

				$delete_button = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["admin_id"].'`, `'.$row["admin_status"].'`)"><i class="fa fa-toggle-off" aria-hidden="true"></i> Disable</button>';
			}
			else
			{
				$status = '<div class="badge bg-danger">Disable</div>';

				$delete_button = '<button type="button" class="btn btn-success btn-sm" onclick="delete_data(`'.$row["admin_id"].'`, `'.$row["admin_status"].'`)"><i class="fa fa-toggle-on" aria-hidden="true"></i> Enable</button>';
			}

			$sub_array[] = $row['admin_name'];
			$sub_array[] = $row['admin_email'];
			$sub_array[] = $row['admin_password'];
			$sub_array[] = $row['admin_type'];
			$sub_array[] = $status;
			$sub_array[] = '<a href="user.php?action=edit&id='.$row["admin_id"].'" class="btn btn-sm btn-primary">Edit</a>&nbsp;' . $delete_button;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"		=>	intval($_POST["draw"]),
			"recordsTotal"	=>	get_total_user_all_records($connect),
			"recordsFiltered"	=>	$filtered_rows,
			"data"		=>	$data
		);

		echo json_encode($output);
	}

	if($_POST['action'] == 'fetch_academic_year')
	{
		$query = "
		SELECT * FROM sfms_acedemic_year 
		";

		if(isset($_POST['search']['value']))
		{
			$query .= 'WHERE (acedemic_start_year LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR acedemic_start_month LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR acedemic_end_year LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR acedemic_end_month LIKE "%'.$_POST["search"]["value"].'%") ';			
		}

		if(isset($_POST['order']))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY acedemic_year_id DESC ';
		}

		$query1 = '';

		if($_POST['length'] != -1)
		{
			$query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$filtered_rows = $statement->rowCount();

		$result = $connect->query($query . $query1, PDO::FETCH_ASSOC);

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$status = '';

			$delete_button = '';

			if($row['acedemic_year_status'] == 'Enable')
			{
				$status = '<div class="badge bg-success">Enable</div>';

				$delete_button = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["acedemic_year_id"].'`, `'.$row["acedemic_year_status"].'`)"><i class="fa fa-toggle-off" aria-hidden="true"></i> Disable</button>';
			}
			else
			{
				$status = '<div class="badge bg-danger">Disable</div>';

				$delete_button = '<button type="button" class="btn btn-success btn-sm" onclick="delete_data(`'.$row["acedemic_year_id"].'`, `'.$row["acedemic_year_status"].'`)"><i class="fa fa-toggle-on" aria-hidden="true"></i> Enable</button>';
			}

			$sub_array[] = $row['acedemic_start_year'];
			$sub_array[] = $row['acedemic_start_month'];
			$sub_array[] = $row['acedemic_end_year'];
			$sub_array[] = $row['acedemic_end_month'];
			$sub_array[] = $status;
			$sub_array[] = '<a href="academic_year.php?action=edit&id='.$row["acedemic_year_id"].'" class="btn btn-sm btn-primary">Edit</a>&nbsp;' . $delete_button;

			$data[] = $sub_array;
		}

		$output = array(
			'draw'		=>	intval($_POST['draw']),
			'recordsTotal'	=>	get_total_academic_year_records($connect),
			'recordsFiltered'	=>	$filtered_rows,
			'data'	=>	$data
		);

		echo json_encode($output);
	}

	if($_POST['action'] == 'fetch_academic_standard')
	{
		$query = "
		SELECT * FROM sfms_acedemic_standard 
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= 'WHERE acedemic_standard_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR acedemic_standard_division LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY acedemic_standard_id DESC ';
		}

		$query1 = '';

		if($_POST["length"] != -1)
		{
			$query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$filtered_rows = $statement->rowCount();

		$result = $connect->query($query . $query1);

		//echo $query . $query1;

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$status = '';

			$delete_button = '';

			if($row['acedemic_standard_status'] == 'Enable')
			{
				$status = '<div class="badge bg-success">Enable</div>';

				$delete_button = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["acedemic_standard_id"].'`, `'.$row["acedemic_standard_status"].'`); "><i class="fa fa-toggle-off" aria-hidden="true"></i> Disable</button>';
			}
			else
			{	
				$status = '<div class="badge bg-danger">Disable</div>';
				$delete_button = '<button type="button" class="btn btn-success btn-sm" onclick="delete_data(`'.$row["acedemic_standard_id"].'`, `'.$row["acedemic_standard_status"].'`); "><i class="fa fa-toggle-on" aria-hidden="true"></i> Enable</button>';
			}

			$sub_array[] = $row['acedemic_standard_name'];

			$sub_array[] = $row['acedemic_standard_division'];

			$sub_array[] = $status;

			$sub_array[] = '<a href="academic_standard.php?action=edit&id='.$row["acedemic_standard_id"].'" class="btn btn-sm btn-primary">Edit</a>&nbsp;' . $delete_button;

			$data[] = $sub_array;
		}

		$output = array(
			'draw'		=>	intval($_POST['draw']),
			'recordsTotal'	=>	get_total_academic_standard_records($connect),
			'recordsFiltered'	=>	$filtered_rows,
			'data'			=>	$data
		);

		echo json_encode($output);

	}

	if($_POST['action'] == 'fetch_student')
	{
		$query = "
		SELECT * FROM sfms_student 
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= ' WHERE student_number LIKE "%'.$_POST["search"]["value"].'%" ';

			$query .= 'OR student_name LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY student_id DESC ';
		}

		$query1 = '';

		if($_POST['length'] != -1)
		{
			$query1 .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$filtered_rows = $statement->rowCount();

		$result = $connect->query($query . $query1, PDO::FETCH_ASSOC);

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$sub_array[] = '<img src="upload/'.$row["student_image"].'" width="50" />';

			$sub_array[] = $row['student_number'];

			$sub_array[] = $row['student_name'];

			$sub_array[] = '<a href="student.php?action=edit&id='.$row["student_id"].'" class="btn btn-sm btn-primary">Edit</a>';

			$data[] = $sub_array;
		}

		$output = array(
			'draw'			=>	intval($_POST['draw']),
			'recordsTotal'	=>	get_total_student_records($connect),
			'recordsFiltered'	=>	$filtered_rows,
			'data'			=>	$data
		);

		echo json_encode($output);
	}

	if($_POST['action'] == 'fetch_student_standard')
	{
		$query = "
		SELECT * FROM sfms_student_standard 
		INNER JOIN sfms_student ON sfms_student.student_id = sfms_student_standard.student_id 
		INNER JOIN sfms_acedemic_year ON sfms_acedemic_year.acedemic_year_id = sfms_student_standard.acedemic_year_id 
		INNER JOIN sfms_acedemic_standard ON sfms_acedemic_standard.acedemic_standard_id = sfms_student_standard.acedemic_standard_id 
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= 'WHERE (sfms_student.student_number LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_student.student_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_year.acedemic_start_year LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_year.acedemic_start_month LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_year.acedemic_end_year LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_year.acedemic_end_month LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_standard.acedemic_standard_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_standard.acedemic_standard_division LIKE "%'.$_POST["search"]["value"].'%") ';
		}

		if(isset($_POST["order"]))
		{	
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY sfms_student_standard.student_standard_id DESC ';
		}

		$query1 = '';

		if($_POST['length'] != -1)
		{
			$query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$filtered_rows = $statement->rowCount();

		$result = $connect->query($query . $query1);

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$status = '';

			$delete_button = '';

			if($row['student_standard_status'] == 'Enable')
			{
				$status = '<div class="badge bg-success">Enable</div>';

				$delete_button = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["student_standard_id"].'`, `'.$row["student_standard_status"].'`)"><i class="fa fa-toggle-off" aria-hidden="true"></i> Disable</button>';
			}
			else
			{
				$status = '<div class="badge bg-danger">Disable</div>';

				$delete_button = '<button type="button" class="btn btn-success btn-sm" onclick="delete_data(`'.$row["student_standard_id"].'`, `'.$row["student_standard_status"].'`)"><i class="fa fa-toggle-on" aria-hidden="true"></i> Enable</button>';
			}

			$sub_array[] = $row['student_number'];

			$sub_array[] = $row['student_name'];

			$sub_array[] = $row['acedemic_start_year'] . ' ' . $row['acedemic_start_month'] . ' - ' . $row['acedemic_end_year'] . ' ' . $row['acedemic_end_month'];

			$sub_array[] = $row['acedemic_standard_name'] . ' ' . $row['acedemic_standard_division'];

			$sub_array[] = $status;

			$sub_array[] = '<a href="student_standard.php?action=edit&id='.$row["student_standard_id"].'" class="btn btn-sm btn-primary">Edit</a>&nbsp;' . $delete_button;

			$data[] = $sub_array;
		}

		$output = array(
			"draw" 		=>	intval($_POST['draw']),
			"recordsTotal"	=>	get_total_student_standard_records($connect),
			"recordsFiltered"	=>	$filtered_rows,
			"data"	=>	$data
		);

		echo json_encode($output);
	}

	if($_POST['action'] == 'fetch_fees')
	{
		$query = "
		SELECT * FROM sfms_fees 
		INNER JOIN sfms_acedemic_year ON sfms_acedemic_year.acedemic_year_id = sfms_fees.acedemic_year_id 
		INNER JOIN sfms_acedemic_standard ON sfms_acedemic_standard.acedemic_standard_id = sfms_fees.acedemic_standard_id 
		";

		if(isset($_POST['search']['value']))
		{
			$query .= '
			WHERE (sfms_acedemic_year.acedemic_start_year LIKE "%'.$_POST["search"]["value"].'%" 
			OR sfms_acedemic_year.acedemic_start_month LIKE "%'.$_POST["search"]["value"].'%" 
			OR sfms_acedemic_year.acedemic_end_year LIKE "%'.$_POST["search"]["value"].'%" 
			OR sfms_acedemic_year.acedemic_end_month LIKE "%'.$_POST["search"]["value"].'%" 
			OR sfms_acedemic_standard.acedemic_standard_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR sfms_acedemic_standard.acedemic_standard_division LIKE "%'.$_POST["search"]["value"].'%" 
			OR fees_month LIKE "%'.$_POST["search"]["value"].'%" 
			OR fees_status LIKE "%'.$_POST["search"]["value"].'%" 
			OR fees_data LIKE "%'.$_POST["search"]["value"].'%") 
			';
		}

		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY sfms_fees.fees_id DESC ';
		}

		$query1 = '';

		if($_POST['length'] != -1)
		{
			$query1 .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$filtered_rows = $statement->rowCount();

		$result = $connect->query($query . $query1);

		$data = array();

		foreach($result as $row)		
		{
			$sub_array = array();

			$status = '';

			if($row['fees_status'] == 'Enable')
			{
				$status = '<div class="badge bg-success">Enable</div>';

				$delete_button = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["fees_id"].'`, `'.$row["fees_status"].'`); "><i class="fa fa-toggle-off" aria-hidden="true"></i> Disable</button>';
			}
			else
			{
				$status = '<div class="badge bg-danger">Disable</div>';

				$delete_button = '<button type="button" class="btn btn-success btn-sm" onclick="delete_data(`'.$row["fees_id"].'`, `'.$row["fees_status"].'`); "><i class="fa fa-toggle-on" aria-hidden="true"></i> Enable</button>';
			}

			$sub_array[] = $row['acedemic_start_month'] . ' ' . $row['acedemic_start_year'] . ' - ' . $row['acedemic_end_month'] .  ' ' . $row['acedemic_end_year'];

			$sub_array[] = $row['acedemic_standard_name'] . ' - ' . $row['acedemic_standard_division'];

			$sub_array[] = $row['fees_month'];

			$fees_data = json_decode($row['fees_data'], true);

			$fees_data_output = '<ul class="list-unstyled">';

			foreach($fees_data as $fees_data_row)
			{
				$fees_data_output .= '<li><b>'.$fees_data_row["fees_name"].' - </b>&#8377;'.$fees_data_row["fees_value"].'</li>';
			}

			$fees_data_output .= '</ul>';

			$sub_array[] = $fees_data_output;

			$sub_array[] = $status;

			$sub_array[] = '<a href="fees_master.php?action=edit&id='.$row["fees_id"].'" class="btn btn-sm btn-primary">Edit</a>&nbsp;' . $delete_button;

			$data[] = $sub_array;
		}

		$output = array(
			'draw'				=>	intval($_POST['draw']),
			'recordsTotal'		=>	get_total_fees_records($connect),
			'recordsFiltered'	=>	$filtered_rows,
			'data'				=>	$data
		);

		echo json_encode($output);
		
	}

	if($_POST['action'] == 'fetch_fees_paid_data')
	{
		$query = "
		SELECT sfms_student.student_number, sfms_student.student_name, sfms_acedemic_year.acedemic_start_year, sfms_acedemic_year.acedemic_start_month, sfms_acedemic_year.acedemic_end_year, sfms_acedemic_year.acedemic_end_month, sfms_fees.fees_month, sfms_fees.fees_data, sfms_acedemic_standard.acedemic_standard_name, sfms_acedemic_standard.acedemic_standard_division, sfms_admin.admin_name, sfms_fees_paid.fees_paid_on, sfms_fees_paid.fees_paid_id  
		FROM sfms_fees_paid 
		INNER JOIN sfms_student 
		ON sfms_student.student_id = sfms_fees_paid.student_id 
		INNER JOIN sfms_acedemic_year 
		ON sfms_acedemic_year.acedemic_year_id = sfms_fees_paid.acedemic_year_id 
		INNER JOIN sfms_fees 
		ON sfms_fees.fees_id = sfms_fees_paid.fees_id 
		INNER JOIN sfms_acedemic_standard 
		ON sfms_acedemic_standard.acedemic_standard_id = sfms_fees_paid.acedemic_standard_id 
		INNER JOIN sfms_admin 
		ON sfms_admin.admin_id = sfms_fees_paid.fees_received_by 
		";

		if($_SESSION['user_type'] == 'User')
		{
			$query .= 'WHERE sfms_fees_paid.fees_received_by = "'.$_SESSION["admin_id"].'" AND ';
		}
		else
		{
			$query .= 'WHERE ';
		}

		if(isset($_POST["search"]["value"]))
		{
			$query .= 'sfms_student.student_number LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_student.student_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_year.acedemic_start_year LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_year.acedemic_start_month LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_year.acedemic_end_year LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_year.acedemic_end_month LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_fees.fees_month LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_standard.acedemic_standard_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_acedemic_standard.acedemic_standard_division LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR sfms_admin.admin_name LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY sfms_fees_paid.fees_paid_id DESC ';
		}

		$query1 = '';

		if($_POST['length'] != -1)
		{
			$query1 .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$filtered_rows = $statement->rowCount();

		$result = $connect->query($query . $query1);

		$data = array();

		foreach($result as $row)
		{
			$status = '';

			$delete_button = '';
			
			$delete_button = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["fees_paid_id"].'`); "><i class="fa fa-times" aria-hidden="true"></i></button>';

			$sub_array = array();
			$sub_array[] = $row['student_number'];
			$sub_array[] = $row['student_name'];
			$sub_array[] = $row['acedemic_start_month'] . ' ' . $row["acedemic_start_year"] . ' - ' . $row["acedemic_end_month"] . ' ' . $row["acedemic_end_year"];
			$sub_array[] = $row['fees_month'];
			$sub_array[] = $row['acedemic_standard_name'] . ' - ' . $row['acedemic_standard_division'];
			$fees_data = json_decode($row['fees_data'], true);

			$fees_data_output = '<ul class="list-unstyled">';

			foreach($fees_data as $fees_data_row)
			{
				$fees_data_output .= '<li><b>'.$fees_data_row["fees_name"].' - </b>&#8377;'.$fees_data_row["fees_value"].'</li>';
			}

			$fees_data_output .= '</ul>';

			$sub_array[] = $fees_data_output;
			$sub_array[] = $row['admin_name'];
			$sub_array[] = date('d/m/Y H:i:s', $row['fees_paid_on']);
			$sub_array[] = '<a href="print_fees_slip.php?action=pdf&code='.$row["fees_paid_id"].'" target="_blank" class="btn btn-warning btn-sm"><i class="fas fa-file-pdf"></i></a>';
			$sub_array[] = $delete_button;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  get_total_fees_paid_all_records($connect),
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
		echo json_encode($output);
	}

	if($_POST['action'] == 'fetch_fees_month_data')
	{
		$output = '<option value="">Select Fees Month</option>';
		$query = "
		SELECT fees_id, fees_month FROM sfms_fees 
		WHERE acedemic_year_id = '".$_POST["acedemic_year_id"]."' 
		AND acedemic_standard_id = '".$_POST["acedemic_standard_id"]."' 
		AND fees_status = 'Enable' 
		ORDER BY fees_id ASC
		";

		$result = $connect->query($query, PDO::FETCH_ASSOC);

		foreach($result as $row)
		{
			$output .= '<option value="'.$row["fees_id"].'">'.$row["fees_month"].'</option>';
		}

		echo $output;
	}
}

?>