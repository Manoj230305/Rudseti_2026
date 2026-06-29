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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloading Certificate PDF - RUDSETI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333333;
        }
        .status-card {
            background: #ffffff;
            border: 1px solid #e3e8ee;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .icon-container {
            width: 72px;
            height: 72px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 2rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(3, 105, 161, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(3, 105, 161, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(3, 105, 161, 0); }
        }
        .status-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.25rem;
            margin-bottom: 8px;
        }
        .status-subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 24px;
        }
        .progress-bar-container {
            background-color: #f1f5f9;
            border-radius: 4px;
            height: 6px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .progress-bar-fill {
            background-color: #284266;
            height: 100%;
            width: 0%;
            transition: width 3.5s linear;
        }
    </style>
</head>
<body>
    <div class="status-card">
        <div class="icon-container">
            <i class="bi bi-file-earmark-pdf-fill"></i>
        </div>
        <h3 class="status-title">PDF Generated Successfully</h3>
        <p class="status-subtitle" id="statusMessage">Your download will start shortly...</p>
        
        <div class="progress-bar-container">
            <div class="progress-bar-fill" id="progressBar"></div>
        </div>
        <span class="text-muted small">Logging out securely...</span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.getElementById('progressBar').style.width = '100%';
            }, 100);
            
            // Trigger the download in an iframe
            let iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = 'download.php?batch=<?php echo htmlspecialchars($batchNumber); ?>';
            document.body.appendChild(iframe);

            setTimeout(() => {
                document.getElementById('statusMessage').innerText = 'Downloading and logging out...';
            }, 1500);

            setTimeout(() => {
                window.location.href = 'logout.php';
            }, 4000);
        });
    </script>
</body>
</html>
