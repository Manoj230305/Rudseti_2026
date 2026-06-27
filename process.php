<?php
session_start();
require 'vendor/autoload.php'; // Include PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];

    $_SESSION['Sponsors'] = isset($_POST['Sponsors']) ? $_POST['Sponsors'] : 'Null';

    // Load the spreadsheet
    $spreadsheet = @IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();

    // Helper function to extract value after colon, robust to RichText objects and label length changes
    $getValueAfterColon = function($cellVal) {
        $str = trim((string)$cellVal);
        $pos = strpos($str, ':');
        if ($pos !== false) {
            return trim(substr($str, $pos + 1));
        }
        return $str;
    };

    $_SESSION['Training'] = $getValueAfterColon($sheet->getCell("E3")->getValue());
    $_SESSION['Start_Date'] = $getValueAfterColon($sheet->getCell("A4")->getValue());
    $_SESSION['End_Date'] = $getValueAfterColon($sheet->getCell("E4")->getValue());
    $batchFull = $sheet->getCell("A3")->getValue();
    $_SESSION['batch'] = $getValueAfterColon($batchFull);

    // Find the header row dynamically by looking for "Roll No" in Column A
    $headerRow = null;
    for ($r = 1; $r <= 20; $r++) {
        $cellVal = trim((string)$sheet->getCell("A" . $r)->getValue());
        if (strcasecmp($cellVal, "Roll No") === 0 || strcasecmp($cellVal, "RollNo") === 0) {
            $headerRow = $r;
            break;
        }
    }

    if ($headerRow !== null) {
        $row = $headerRow + 1;
    } else {
        // Fallback: search for first non-empty row from 5 onwards
        for ($r = 5; $r <= 20; $r++) {
            $rowData = [];
            for ($col = 'A'; $col <= 'E'; $col++) {
                $rowData[] = $sheet->getCell($col . $r)->getValue();
            }
            if (array_filter($rowData) !== []) {
                $valA = trim((string)$sheet->getCell("A" . $r)->getValue());
                if (!is_numeric($valA) && !empty($valA)) {
                    $headerRow = $r;
                    break;
                }
            }
        }
        $row = ($headerRow !== null) ? ($headerRow + 1) : 6;
    }

    $dataArray = [];

    while (true) {
        $rowData = [];
        for ($col = 'A'; $col <= 'U'; $col++) {
            $cellValue = $sheet->getCell($col . $row)->getValue();
            // Cast to string to prevent TypeErrors in downstream string operations (e.g. htmlspecialchars)
            $rowData[] = $cellValue !== null ? (string)$cellValue : '';
        }

        if (array_filter($rowData) === []) {
            break; // Stop if row is empty
        }

        $dataArray[] = $rowData;
        $row++;
    }

    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Processing Batch - RUDSETI</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                background-color: #f4f6f9;
                font-family: \'Inter\', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                min-height: 100vh;
                display: flex;
                align-items: center;
                color: #333333;
                padding: 40px 0;
            }

            .container {
                position: relative;
                z-index: 1;
            }

            .dashboard-card {
                background: #ffffff;
                border: 1px solid #e3e8ee;
                border-radius: 16px;
                padding: 35px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            .section-title {
                font-weight: 600;
                color: #1e293b;
                font-size: 1.5rem;
            }

            .subtitle {
                color: #64748b;
                font-size: 0.875rem;
                letter-spacing: 0.2px;
            }

            .info-card {
                background: #f8fafc;
                border: 1px solid #f1f5f9;
                border-radius: 12px;
                padding: 24px;
                height: 100%;
            }

            .info-title {
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #284266;
                font-weight: 700;
                margin-bottom: 20px;
                border-bottom: 2px solid #e2e8f0;
                padding-bottom: 8px;
            }

            .info-item {
                display: flex;
                align-items: flex-start;
                margin-bottom: 18px;
            }

            .info-icon {
                font-size: 1.1rem;
                color: #475569;
                margin-right: 12px;
                margin-top: 1px;
            }

            .info-label {
                font-size: 0.75rem;
                color: #64748b;
                font-weight: 500;
                margin-bottom: 2px;
            }

            .info-value {
                font-size: 0.9rem;
                font-weight: 600;
                color: #1e293b;
            }

            .progress-section {
                background: #ffffff;
                border: 1px solid #f1f5f9;
                border-radius: 12px;
                padding: 24px;
                height: 100%;
            }

            .status-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 12px;
            }

            .status-badge {
                background: #e0f2fe;
                color: #0369a1;
                border: 1px solid #bae6fd;
                font-weight: 600;
                font-size: 0.8rem;
                padding: 6px 14px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s ease;
            }

            .status-dot {
                width: 6px;
                height: 6px;
                background-color: #0284c7;
                border-radius: 50%;
                display: inline-block;
            }

            .progress {
                background-color: #f1f5f9;
                border-radius: 4px;
                height: 8px;
                overflow: hidden;
                margin-bottom: 25px;
            }

            .progress-bar {
                background-color: #284266;
                transition: width 0.4s ease;
            }

            .audit-container {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                overflow: hidden;
            }

            .audit-header {
                background: #f8fafc;
                padding: 10px 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-bottom: 1px solid #e2e8f0;
            }

            .audit-title {
                font-size: 0.75rem;
                font-weight: 600;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            #logs {
                background: #ffffff;
                height: 230px;
                overflow-y: auto;
                padding: 16px;
                font-family: \'Consolas\', \'Monaco\', monospace;
                font-size: 0.8rem;
                color: #334155;
                scrollbar-width: thin;
            }

            .log-entry {
                margin-bottom: 6px;
                display: flex;
                align-items: flex-start;
                gap: 8px;
                padding-bottom: 4px;
                border-bottom: 1px dashed #f1f5f9;
            }

            .log-icon-success { color: #16a34a; }
            .log-icon-warning { color: #d97706; }
            .log-icon-danger { color: #dc2626; }
            
            .spinner-border {
                width: 1.2rem;
                height: 1.2rem;
                color: #284266;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-11">
                    <div class="dashboard-card">
                        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-light pb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div>
                                    <h3 class="section-title mb-0">Record Processing Engine</h3>
                                    <p class="subtitle mb-0">CB_RSETI Automated Batch System</p>
                                </div>
                            </div>
                            <span class="status-badge" id="completionStatus">
                                <span class="status-dot"></span>
                                Processing Records
                            </span>
                        </div>

                        <div class="row g-4">
                            <!-- Left Panel: Batch Metadata -->
                            <div class="col-md-5 col-lg-4">
                                <div class="info-card">
                                    <h4 class="info-title">Batch Metadata</h4>
                                    
                                    <div class="info-item">
                                        <i class="bi bi-folder2-open info-icon"></i>
                                        <div>
                                            <div class="info-label">Batch Identifier</div>
                                            <div class="info-value">' . htmlspecialchars($_SESSION['batch']) . '</div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <i class="bi bi-journal-text info-icon"></i>
                                        <div>
                                            <div class="info-label">Training Program</div>
                                            <div class="info-value">' . htmlspecialchars($_SESSION['Training']) . '</div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <i class="bi bi-briefcase info-icon"></i>
                                        <div>
                                            <div class="info-label">Sponsoring Agency</div>
                                            <div class="info-value">' . htmlspecialchars($_SESSION['Sponsors']) . '</div>
                                        </div>
                                    </div>

                                    <div class="info-item mb-0">
                                        <i class="bi bi-calendar3 info-icon"></i>
                                        <div>
                                            <div class="info-label">Duration Period</div>
                                            <div class="info-value">' . htmlspecialchars($_SESSION['Start_Date']) . ' to ' . htmlspecialchars($_SESSION['End_Date']) . '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Panel: Progress and Console -->
                            <div class="col-md-7 col-lg-8">
                                <div class="progress-section d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="status-header">
                                            <span class="text-muted small fw-medium" id="progressText">Initializing system...</span>
                                            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.8rem;" id="percentageText">0%</span>
                                        </div>
                                        
                                        <div class="progress">
                                            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="' . count($dataArray) . '"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="audit-container">
                                            <div class="audit-header">
                                                <span class="audit-title">System Activity Log</span>
                                                <i class="bi bi-list-task text-muted"></i>
                                            </div>
                                            <div id="logs"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';

    foreach ($dataArray as $index => $data) {
        $imageName = $data[0] . '.jpg';
        $imagePath = 'pictures/' . $_SESSION['batch'] . '/' . $imageName;
    
        // Use default image if not found
        $uploadFile = file_exists($imagePath) ? $imagePath : 'pictures/1050.jpg';
    
        // Clean and extract Father's/Dependent's name (only strip S/O, D/O prefixes if present)
        $depName = trim($data[3]);
        if (preg_match('/^(s[\/\.]?o|d[\/\.]?o|w[\/\.]?o|s\.o\.|d\.o\.|w\.o\.)[\s\.:]*/i', $depName, $matches)) {
            $depName = trim(substr($depName, strlen($matches[0])));
        }

        // Clean and format Address (avoid trailing comma if Column H / $data[7] is empty)
        $addressParts = [];
        if (!empty(trim($data[4]))) {
            $addressParts[] = trim($data[4]);
        }
        if (isset($data[7]) && !empty(trim($data[7]))) {
            $addressParts[] = trim($data[7]);
        }
        $address = implode(", ", $addressParts);

        echo '<form method="POST" action="test3.php" enctype="multipart/form-data" data-image-path="' . htmlspecialchars($uploadFile) . '">
            <input type="hidden" name="roll_no" value="' . htmlspecialchars($data[0]) . '">
            <input type="hidden" name="candidate_id" value="' . htmlspecialchars($data[1]) . '">
            <input type="hidden" name="customer_name" value="' . htmlspecialchars($data[2]) . '">
            <input type="hidden" name="Dependent_name" value="' . htmlspecialchars($depName) . '">
            <input type="hidden" name="address" value="' . htmlspecialchars($address) . '">
            <input type="file" name="user_image" style="display:none;">
        </form>';
    }

    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            let forms = document.querySelectorAll("form");
            let progressBar = document.getElementById("progressBar");
            let progressText = document.getElementById("progressText");
            let percentageText = document.getElementById("percentageText");
            let logs = document.getElementById("logs");
            let completed = 0;

            function updateProgress() {
                completed++;
                let percentage = (completed / forms.length) * 100;
                progressBar.style.width = percentage + "%";
                progressBar.setAttribute("aria-valuenow", completed);
                progressText.innerHTML = "Processed <strong>" + completed + "</strong> of <strong>" + forms.length + "</strong> records";
                percentageText.innerText = Math.round(percentage) + "%";
                
                if (completed === forms.length) {
                    let completionStatus = document.getElementById("completionStatus");
                    completionStatus.innerHTML = "<i class=\'bi bi-check-circle-fill\'></i> Process Completed";
                    completionStatus.className = "status-badge bg-success-subtle text-success border border-success-subtle px-3 py-2";
                    
                    setTimeout(() => {
                        window.location.href = "http://localhost/rseti/generate_pdf.php";
                    }, 2000);
                }
            }

            // Initial text
            progressText.innerHTML = "Ready to process <strong>" + forms.length + "</strong> records...";

            forms.forEach((form, index) => {
                setTimeout(() => {
                    const filePath = form.dataset.imagePath;
                    const studentName = form.querySelector(\'input[name="customer_name"]\').value;

                    let extension = filePath.toLowerCase().endsWith(".jpg") ? "jpg" : "png";

                    fetch(filePath)
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error("File not found: " + filePath);
                            }
                            return response.blob();
                        })
                        .then((blob) => {
                            let file = new File([blob], "user_image." + extension, { type: "image/" + extension });

                            let formData = new FormData(form);
                            formData.append("user_image", file);

                            fetch(form.action, {
                                method: "POST",
                                body: formData,
                            })
                            .then((response) => response.text())
                            .then((data) => {
                                logs.innerHTML += "<div class=\'log-entry\'><i class=\'bi bi-check-circle-fill log-icon-success\'></i> <span>Processed record: " + studentName + " (" + filePath.split("/").pop() + ")</span></div>";
                                logs.scrollTop = logs.scrollHeight;
                                updateProgress();
                            })
                            .catch((error) => {
                                logs.innerHTML += "<div class=\'log-entry\'><i class=\'bi bi-x-circle-fill log-icon-danger\'></i> <span>Error sending " + studentName + ": " + error + "</span></div>";
                                logs.scrollTop = logs.scrollHeight;
                            });
                        })
                        .catch((err) => {
                            logs.innerHTML += "<div class=\'log-entry\'><i class=\'bi bi-exclamation-triangle-fill log-icon-warning\'></i> <span>File fetch error for " + studentName + " (" + filePath.split("/").pop() + "): " + err.message + "</span></div>";
                            logs.scrollTop = logs.scrollHeight;
                        });
                }, index * 2000);
            });
        });
    </script>
    </body>
    </html>';
} else {
    echo "No file uploaded.";
}
?>
