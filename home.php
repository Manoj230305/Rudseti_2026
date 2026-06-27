<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    header("Location: logout.php");
    exit();
}

if (isset($_SESSION['batch'])) {
    header("Location: data.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - RUDSETI</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/nexlogo.png"/>

    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            color: #333333;
        }

        .navbar-custom {
            background-color: #284266;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 15px 0;
        }

        .navbar-brand-custom {
            color: #ffffff;
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand-custom img {
            max-height: 36px;
            width: auto;
        }

        .nav-link-custom {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            padding: 8px 16px !important;
            border-radius: 6px;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .nav-link-logout {
            color: #ff8a8a !important;
            font-weight: 600;
            border: 1px solid rgba(255, 138, 138, 0.3);
            border-radius: 6px;
            padding: 7px 16px !important;
            transition: all 0.2s ease;
        }

        .nav-link-logout:hover {
            color: #ffffff !important;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .dashboard-container {
            padding: 50px 0;
        }

        .card-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            padding: 35px;
            height: 100%;
        }

        .card-title-custom {
            font-weight: 700;
            color: #1e293b;
            font-size: 1.35rem;
            margin-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-label-custom {
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 25px;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.15rem;
            z-index: 10;
        }

        .form-control-custom {
            padding-left: 46px;
            height: 50px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            color: #1e293b;
            width: 100%;
        }

        .form-control-custom:focus {
            border-color: #284266;
            box-shadow: 0 0 0 4px rgba(40, 66, 102, 0.12);
            outline: none;
        }

        /* Upload Area Styling */
        .upload-zone {
            border: 2px dashed #cbd5e1;
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 35px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
        }

        .upload-zone:hover {
            border-color: #284266;
            background-color: #f1f5f9;
        }

        .upload-icon {
            font-size: 2.5rem;
            color: #64748b;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .upload-zone:hover .upload-icon {
            color: #284266;
            transform: translateY(-2px);
        }

        .upload-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 4px;
        }

        .upload-subtext {
            font-size: 0.8rem;
            color: #64748b;
        }

        .btn-submit-custom {
            background-color: #284266;
            color: #ffffff;
            font-weight: 600;
            height: 50px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.95rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit-custom:hover {
            background-color: #1a2d42;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(40, 66, 102, 0.2);
        }

        /* File Preview Badge */
        .file-preview {
            margin-bottom: 25px;
        }

        .file-preview-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #166534;
            font-weight: 500;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .file-remove {
            background: none;
            border: none;
            color: #86efac;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .file-remove:hover {
            color: #b91c1c;
        }

        .guideline-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.5;
        }

        .guideline-icon {
            color: #284266;
            font-size: 1.1rem;
            margin-top: 1px;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand-custom" href="index.html">
                <img src="assets/img/rsetilogo.png" alt="RUDSETI Logo">
                <span>RUDSETI</span>
            </a>

            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-3"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2 mt-3 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link-custom" href="index.html">Generator Home</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link-logout" href="logout.php">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Sign Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Main Panel -->
    <main class="container dashboard-container">
        <div class="row g-4 justify-content-center">
            
            <!-- Left Column: Upload Form -->
            <div class="col-lg-7">
                <div class="card-custom">
                    <h3 class="card-title-custom">
                        <i class="bi bi-file-earmark-arrow-up text-primary"></i>
                        Upload Training Batch
                    </h3>

                    <form action="process.php" method="post" enctype="multipart/form-data">
                        
                        <!-- Sponsor Input -->
                        <div class="mb-4">
                            <label for="volunteer-name" class="form-label-custom">Sponsoring Agency</label>
                            <div class="input-group-custom">
                                <i class="bi bi-briefcase input-icon"></i>
                                <input type="text" name="Sponsors" id="volunteer-name" class="form-control-custom" placeholder="Sponsor name (e.g., RUDSETI, NRLM)" required>
                            </div>
                        </div>

                        <!-- Upload Zone -->
                        <div class="mb-2">
                            <label class="form-label-custom">Batch Spreadsheet</label>
                            <label for="inputGroupFile02" class="upload-zone">
                                <input type="file" name="excel_file" class="d-none" id="inputGroupFile02" accept=".xlsx, .xls" required>
                                <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                <span class="upload-text">Choose Excel Spreadsheet</span>
                                <span class="upload-subtext">Drag and drop or click to browse files</span>
                            </label>
                        </div>

                        <!-- File Preview Zone -->
                        <div id="demo" class="file-preview"></div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="btn-submit-custom">
                                <i class="bi bi-gear-fill"></i>
                                Process Spreadsheet
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Right Column: Guidelines -->
            <div class="col-lg-5">
                <div class="card-custom">
                    <h3 class="card-title-custom">
                        <i class="bi bi-info-circle text-primary"></i>
                        System Guidelines
                    </h3>
                    
                    <div class="pt-2">
                        <div class="guideline-item">
                            <i class="bi bi-filetype-xls guideline-icon"></i>
                            <div>
                                <strong>File Formats Supported</strong>
                                <div class="text-muted small mt-1">Please upload spreadsheets in standard Microsoft Excel formats: <code>.xlsx</code> or <code>.xls</code>.</div>
                            </div>
                        </div>

                        <div class="guideline-item">
                            <i class="bi bi-table guideline-icon"></i>
                            <div>
                                <strong>Template Configuration</strong>
                                <div class="text-muted small mt-1">Verify that the spreadsheet is aligned with the official template. Columns for Roll No, Name, and Course Name must contain data.</div>
                            </div>
                        </div>

                        <div class="guideline-item">
                            <i class="bi bi-image-fill guideline-icon"></i>
                            <div>
                                <strong>Photograph Mapping</strong>
                                <div class="text-muted small mt-1">Ensure that images have been uploaded to the server's <code>pictures/</code> directory, named exactly by their Roll Number.</div>
                            </div>
                        </div>

                        <div class="guideline-item">
                            <i class="bi bi-shield-check-fill guideline-icon"></i>
                            <div>
                                <strong>Data Integrity Check</strong>
                                <div class="text-muted small mt-1">Double check that there are no empty rows between student records to prevent truncation of processed lists.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- JavaScript Files -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
        document.getElementById('inputGroupFile02').addEventListener('change', function(event) {
            var x = document.getElementById("inputGroupFile02");
            var txt = "";
            if ('files' in x) {
                if (x.files.length > 0) {
                    var file = x.files[0];
                    txt += "<div class='file-preview-item'>";
                    txt += "<span><i class='bi bi-file-earmark-excel-fill text-success me-2 fs-5 align-middle'></i>" + file.name + " </span>";
                    txt += "<button type='button' class='file-remove' data-file-index='0'><i class='bi bi-x-lg'></i></button></div>";
                }
            } 
            document.getElementById("demo").innerHTML = txt;

            // Add event listener for remove button
            document.querySelectorAll('.file-remove').forEach(function(button) {
                button.addEventListener('click', function() {
                    x.value = ''; // Clear the file input
                    document.getElementById("demo").innerHTML = ''; // Clear the preview area
                });
            });
        });
    </script>

</body>
</html>