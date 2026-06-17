<?php

//database_connection.php

// Define the database connection settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'sfms');
define('DB_USER', 'root');
define('DB_PASS', '');

// Connect to the database
$connect = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);

$base_url = 'http://localhost/tutorial/school_fees_management_system/';

// Import the database schema if the table does not exist
$table_name = 'sfms_admin'; // change this to the name of your table
$table_check_sql = "SELECT 1 FROM $table_name LIMIT 1";
$table_exists = $connect->query($table_check_sql) !== false;

if (!$table_exists) 
{
    $sql = file_get_contents('database.sql');
    $connect->exec($sql);

    // Output a message indicating the setup was successful
	echo "Setup complete. Please log in using the credentials:\n";
	echo "Username: admin@sfms.com\n";
	echo "Password: password\n";
}

date_default_timezone_set("Asia/Calcutta");

$month_array = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

session_start();
// License Check (1-year validity)
$license_path = __DIR__ . '/license.json';

if (file_exists($license_path)) {
    $license = json_decode(file_get_contents($license_path), true);
    $today = date('Y-m-d');

    if ($today > $license['valid_till']) {

        die("<h2 style='color:red; text-align:center; margin-top:50px;'>❌ License Expired<br>Please renew your license.</h2>");
    }
} else {
    die("<h2 style='color:red; text-align:center; margin-top:50px;'>❌ License file not found.</h2>");
}

function is_login()
{
	if(isset($_SESSION['admin_id']))
	{
		return true;
	}
	return false;
}

function is_master_user()
{
	if(isset($_SESSION['user_type']))
	{
		if($_SESSION["user_type"] == 'Master')
		{
			return true;
		}
		return false;
	}
	return false;
}

function Generate_student_number($number)
{
	$output = '';

	$number = $number + 1;

	$number_length = strlen((string)$number);

	if($number_length == 1)
	{
		$output = 'S00000' . $number;
	}
	else if($number_length == 2)
	{
		$output = 'S0000' . $number;
	}
	else if($number_length == 3)
	{
		$output = 'S000' . $number;
	}
	else if($number_length == 4)
	{
		$output = 'S00' . $number;
	}
	else if($number_length == 5)
	{
		$output = 'S0' . $number;
	}
	else
	{
		$output = 'S' . $number;
	}

	return $output;
}

function Student_list_data($connect)
{
	$query = "
	SELECT student_id, student_number, student_name 
	FROM sfms_student 
	ORDER BY student_number
	";

	$result = $connect->query($query, PDO::FETCH_ASSOC);

	$output = '';

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["student_id"].'">'.$row["student_number"].' - '.$row["student_name"].'</option>';
	}

	return $output;
}

function Academic_list_data($connect)
{
	/*$query = "
	SELECT acedemic_year_id, acedemic_start_year, acedemic_start_month, acedemic_end_year, acedemic_end_month 
	FROM sfms_acedemic_year 
	WHERE acedemic_year_status = 'Enable' 
	AND acedemic_start_year >= '".date('Y')."' 
	ORDER BY acedemic_year_id DESC
	";*/

	$query = "
	SELECT acedemic_year_id, acedemic_start_year, acedemic_start_month, acedemic_end_year, acedemic_end_month 
	FROM sfms_acedemic_year 
	WHERE acedemic_year_status = 'Enable' 
	ORDER BY acedemic_year_id DESC
	";

	$result = $connect->query($query, PDO::FETCH_ASSOC);

	$output = '';

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["acedemic_year_id"].'">'.$row["acedemic_start_month"].' '.$row["acedemic_start_year"].' - '.$row["acedemic_end_month"].' '.$row["acedemic_end_year"].'</option>';
	}

	return $output;
}

function Academic_standard_list_data($connect)
{
	$query = "
	SELECT acedemic_standard_id, acedemic_standard_name, acedemic_standard_division 
	FROM sfms_acedemic_standard 
	WHERE acedemic_standard_status = 'Enable' 
	ORDER BY acedemic_standard_id DESC
	";

	$result = $connect->query($query, PDO::FETCH_ASSOC);

	$output = '';

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["acedemic_standard_id"].'">'.$row['acedemic_standard_name'].' - '.$row["acedemic_standard_division"].'</option>';
	}
	return $output;
}

function Fees_month_list_data($connect, $acedemic_year_id, $month_array)
{
	$query = "SELECT * FROM sfms_acedemic_year WHERE acedemic_year_id = '".$acedemic_year_id."'";

	$result = $connect->query($query, PDO::FETCH_ASSOC);

	$output = '<option value="">Select Month</option>';

	$start_month_index = 0;

	$end_month_index = 0;

	foreach($result as $row)
	{
		$start_month_index = array_search($row["acedemic_start_month"], $month_array);

		$end_month_index = array_search($row["acedemic_end_month"], $month_array);

		$start_year = $row["acedemic_start_year"];

		$end_year = $row["acedemic_end_year"];			
	}

	for($i = $start_month_index; $i < count($month_array); $i++)
	{
		$output .= '<option value="'.$month_array[$i].' - '.$start_year.'">'.$month_array[$i].' - '.$start_year.'</option>';
	}

	if($start_year != $end_year)
	{
		for($i = 0; $i <= $end_month_index; $i++)
		{
			$output .= '<option value="'.$month_array[$i].' - '.$end_year.'">'.$month_array[$i].' - '.$end_year.'</option>';
		}
	}

	return $output;
}

function get_total_user_all_records($connect)
{
	$query = "
	SELECT * FROM sfms_admin WHERE admin_type = 'User'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

function get_total_academic_year_records($connect)
{
	$query = 'SELECT * FROM sfms_acedemic_year';

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

function get_total_academic_standard_records($connect)
{
	$query = "SELECT * FROM sfms_acedemic_standard";

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

function get_total_student_records($connect)
{
	$query = "SELECT * FROM sfms_student";

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

function get_total_student_standard_records($connect)
{
	$query = "SELECT * FROM sfms_student_standard";

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

function get_total_fees_records($connect)
{
	$query = "SELECT * FROM sfms_fees";

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

function get_total_fees_paid_all_records($connect)
{
	$query = "SELECT * FROM sfms_fees_paid";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function get_total_fees_received_data($connect)
{
	$query = "SELECT sfms_fees.fees_data FROM sfms_fees_paid INNER JOIN sfms_fees ON sfms_fees.fees_id = sfms_fees_paid.fees_id";

	$statement = $connect->prepare($query);

	$statement->execute();

	$total = 0;

	if($statement->rowCount() > 0)
	{
		$result = $statement->fetchAll();

		foreach($result as $row)
		{
			$fees_data = json_decode($row['fees_data'], true);

			$fees_total = 0;
			foreach($fees_data as $fees_row)
			{
				$fees_total = $fees_total + $fees_row['fees_value'];
			}

			$total = $total + $fees_total;
		}
	}

	return $total;

}

?>