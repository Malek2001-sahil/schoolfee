<?php include('header.php'); ?>
<div class="container mt-5">
    <h3>Import Student Excel File</h3>
    <form method="post" enctype="multipart/form-data" action="import_students.php">
        <div class="mb-3">
            <label>Select Excel File (.xlsx):</label>
            <input type="file" name="excel_file" class="form-control" required />
        </div>
        <input type="submit" name="import" value="Import" class="btn btn-success" />
    </form>
</div>
<?php include('footer.php'); ?>
