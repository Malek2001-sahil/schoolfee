<?php
require 'vendor/autoload.php'; // PhpSpreadsheet autoload
include('database_connection.php');

use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_POST["import"]))
{
    $file = $_FILES['excel_file']['tmp_name'];

    if($file)
    {
        $spreadsheet = IOFactory::load($file);
        $data = $spreadsheet->getActiveSheet()->toArray();

        // Skip header row
        for($i = 1; $i < count($data); $i++)
        {
            $row = $data[$i];

            // Extract values (adjust column index as needed)
            $student_number = $row[0];
            $student_name = $row[1];
            $father_name = $row[2];
            $mother_name = $row[3];
            $dob = $row[4];
            $address = $row[5];
            $admission_date = $row[6];
            $contact1 = $row[7];
            $contact2 = $row[8];

            // Insert into DB
            $query = "INSERT INTO sfms_student 
                (student_number, student_name, student_father_name, student_mother_name, student_date_of_birth, student_address, student_date_of_addmission, student_contact_number1, student_contact_number2, student_added_on) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $statement = $connect->prepare($query);
            $statement->execute([
                $student_number,
                $student_name,
                $father_name,
                $mother_name,
                $dob,
                $address,
                $admission_date,
                $contact1,
                $contact2,
                time()
            ]);
        }

        header("Location: student.php?msg=import");
    }
}
?>
| Student Number | Name | Father Name | Mother Name | DOB        | Address  | Admission Date | Contact 1  | Contact 2 |
| -------------- | ---- | ----------- | ----------- | ---------- | -------- | -------------- | ---------- | --------- |
| S001           | John | Peter       | Mary        | 2009-03-15 | Street 1 | 2023-06-10     | 9876543210 |           |
