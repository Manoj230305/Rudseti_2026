<?php
session_start();


if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    header("Location: logout.php");
    exit();
}

// Check if batch number is received via GET
if (!isset($_GET['batch'])) {
    header("Location: index.php"); // Redirect to index or previous page if batch number is not provided
    exit();
}


session_unset();

// Destroy the session
session_destroy();

// Validate and sanitize batch number
$batchNumber = $_GET['batch']; // Assuming batch number is sanitized elsewhere

// Path to batch folder (adjust as per your folder structure)
$batchFolder = 'certificates/' . $batchNumber . '/';

// Check if batch folder exists
if (!file_exists($batchFolder)) {
    die('Batch folder does not exist.');
}

// File name of the generated PDF
$pdfFileName = $batchNumber . '_pdf.pdf';
$pdfFilePath = $batchFolder . $pdfFileName;

// Check if the PDF file exists
if (file_exists($pdfFilePath)) {
    // Set headers for PDF download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');
    header('Content-Length: ' . filesize($pdfFilePath));

    // Output the PDF file
    readfile($pdfFilePath);
    exit();
} else {
    die('PDF file not found.');
}

?>
