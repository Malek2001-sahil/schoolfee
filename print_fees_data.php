<?php

//print_fees_data.php

if(isset($_POST["pdf_fees_data"]))
{
	include('database_connection.php');

	$query = "
	SELECT * FROM sfms_setting 
	LIMIT 1
	";

	$school_result = $connect->query($query, PDO::FETCH_ASSOC);

	$school_name = '';
	$school_address = '';
	$school_email_address = '';
	$school_website = '';
	$school_contact_number = '';

	foreach($school_result as $school_row)
	{
		$school_name = $school_row['school_name'];
		$school_address = $school_row['school_address'];
		$school_email_address = $school_row['school_email_address'];
		$school_website = $school_row['school_website'];
		$school_contact_number = $school_row['school_contact_number'];
	}

	$html = '
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<h2 align="center" style="margin-bottom:15px;">'.$school_name.'</h2>
				<div align="center" style="margin-bottom:6px">'.$school_address.'</div>
				<div align="center"><b>Phone No. : </b>'.$school_contact_number.' &nbsp;&nbsp;&nbsp;<b>Email : </b>'.$school_email_address.'</div>
				<div align="center" style="margin-bottom:6px"><b>Website : </b>'.$school_website.'</div>
			</td>
		</tr>
		<tr>
			<td>
				<h4>Fees Status Data</h4>
				<div style="font-size:11px;">
				'.$_POST["filter_fees_data"].'
				</div>
			</td>
		</tr>
	</table>
	';

    require_once('class/pdf.php');

	$pdf = new Pdf();

	$pdf->set_paper('letter');

	$file_name = ''.date('Y-m-d-H-i-s', time()) .'.pdf';

	$pdf->loadHtml($html);
	$pdf->render();
	$pdf->stream($file_name, array("Attachment" => false));
	exit(0);
}

?>