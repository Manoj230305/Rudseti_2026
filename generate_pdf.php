<?php
    session_start();

    if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
        header("Location: logout.php");
        exit();
    }

    if (!isset($_SESSION['batch'])) {
        header("Location: home.php"); 
        exit();
    }

    $batchNumber = $_SESSION['batch'];
    $batchFolder = 'C:/xampp/server/rseti/certificates/' . $batchNumber . '/';

    if (!file_exists($batchFolder)) {
        die('Batch folder does not exist.');
    }

    require_once('tcpdf/tcpdf.php');

    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false, 0);

    // **Fetch & Sort Files**
    $imageFiles = array_filter(scandir($batchFolder), function($file) {
        return preg_match('/\.(png|jpg|jpeg)$/i', $file); // Only images
    });
    natsort($imageFiles); // Natural sorting (1.png before 10.png)

    // **Add images to PDF in correct order**
    foreach ($imageFiles as $fileName) {
        $imageFile = $batchFolder . $fileName;
        $pdf->AddPage();
        $pdf->Image($imageFile, 0, 0, 297, 210, '', '', '', true, 300, '', false, false, 0, false, false, false);
    }

    // Save PDF
    $pdfFileName = $batchNumber . '_pdf.pdf';
    $pdfFilePath = $batchFolder . $pdfFileName;
    $pdf->Output($pdfFilePath, 'F');
    $pdf->close();

    // Redirect after completion
    header("Location: download.php?batch=$batchNumber");
    exit();
?>
