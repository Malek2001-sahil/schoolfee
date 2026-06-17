<?php
require_once('class/pdf.php');

if (isset($_POST["report_html"])) {
    $html = '
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 6px; }
        th { background-color: #f2f2f2; }
        h3 { text-align: center; margin-bottom: 20px; }
    </style>
    <h3>Daily Fees Collection Report</h3>' . $_POST["report_html"];

    $pdf = new Pdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'landscape');
    $pdf->render();
    $pdf->stream("daily_fees_report.pdf", array("Attachment" => false));
} else {
    echo "No data to export.";
}
?>
