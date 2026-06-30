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

        /* Custom Pills Styling */
        .nav-pills .nav-link {
            color: #64748b;
            background: transparent;
            border-radius: 8px;
            font-size: 0.9rem;
            padding: 10px 16px;
            transition: all 0.2s ease;
        }
        .nav-pills .nav-link:hover {
            color: #1e293b;
        }
        .nav-pills .nav-link.active {
            background-color: #284266 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(40, 66, 102, 0.15);
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
                    <h3 class="card-title-custom mb-3">
                        <i class="bi bi-file-earmark-arrow-up text-primary"></i>
                        Certificate Generator
                    </h3>

                    <!-- Tab Switcher -->
                    <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist" style="background: #f1f5f9; padding: 6px; border-radius: 10px;">
                        <li class="nav-item w-50" role="presentation">
                            <button class="nav-link active w-100 py-2 fw-semibold border-0 text-center" id="pills-upload-tab" data-bs-toggle="pill" data-bs-target="#pills-upload" type="button" role="tab" aria-controls="pills-upload" aria-selected="true">
                                <i class="bi bi-file-earmark-spreadsheet me-2"></i>Upload Batch
                            </button>
                        </li>
                        <li class="nav-item w-50" role="presentation">
                            <button class="nav-link w-100 py-2 fw-semibold border-0 text-center" id="pills-manual-tab" data-bs-toggle="pill" data-bs-target="#pills-manual" type="button" role="tab" aria-controls="pills-manual" aria-selected="false">
                                <i class="bi bi-pencil-square me-2"></i>Manual Entry
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- Spreadsheet Upload Tab -->
                        <div class="tab-pane fade show active" id="pills-upload" role="tabpanel" aria-labelledby="pills-upload-tab">
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

                        <!-- Manual Entry Tab -->
                        <div class="tab-pane fade" id="pills-manual" role="tabpanel" aria-labelledby="pills-manual-tab">
                            <form action="process_manual.php" method="post" enctype="multipart/form-data">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="manual-batch" class="form-label-custom">Batch Number</label>
                                        <div class="input-group-custom mb-0">
                                            <i class="bi bi-folder2 input-icon"></i>
                                            <input type="text" name="batch" id="manual-batch" class="form-control-custom" placeholder="e.g., 1045" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="manual-roll" class="form-label-custom">Roll Number</label>
                                        <div class="input-group-custom mb-0">
                                            <i class="bi bi-hash input-icon"></i>
                                            <input type="text" name="roll_no" id="manual-roll" class="form-control-custom" placeholder="e.g., 12" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="manual-candidate" class="form-label-custom">Candidate ID</label>
                                        <div class="input-group-custom mb-0">
                                            <i class="bi bi-person-badge input-icon"></i>
                                            <input type="text" name="candidate_id" id="manual-candidate" class="form-control-custom" placeholder="Candidate ID" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="manual-name" class="form-label-custom">Candidate Name</label>
                                        <div class="input-group-custom mb-0">
                                            <i class="bi bi-person input-icon"></i>
                                            <input type="text" name="customer_name" id="manual-name" class="form-control-custom" placeholder="Full Name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="manual-father" class="form-label-custom">Father's Name</label>
                                        <div class="input-group-custom mb-0">
                                            <i class="bi bi-person-fill input-icon"></i>
                                            <input type="text" name="Dependent_name" id="manual-father" class="form-control-custom" placeholder="Father's name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="manual-sponsor" class="form-label-custom">Sponsoring Agency</label>
                                        <div class="input-group-custom mb-0">
                                            <i class="bi bi-briefcase input-icon"></i>
                                            <input type="text" name="Sponsors" id="manual-sponsor" class="form-control-custom" placeholder="e.g., RUDSETI, NRLM" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="manual-start" class="form-label-custom">Start Date</label>
                                        <div class="input-group-custom mb-0">
                                            <i class="bi bi-calendar-event input-icon"></i>
                                            <input type="text" name="Start_Date" id="manual-start" class="form-control-custom" placeholder="e.g., 20-04-2026" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="manual-end" class="form-label-custom">End Date</label>
                                        <div class="input-group-custom mb-0">
                                            <i class="bi bi-calendar-check input-icon"></i>
                                            <input type="text" name="End_Date" id="manual-end" class="form-control-custom" placeholder="e.g., 10-05-2026" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Program Name -->
                                <div class="mb-4">
                                    <label for="manual-program" class="form-label-custom">Program Name</label>
                                    <div class="input-group-custom mb-0">
                                        <i class="bi bi-journal-text input-icon"></i>
                                        <input type="text" name="Training" id="manual-program" class="form-control-custom" placeholder="Program name (e.g., Electrician, Dress Designing)" required>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="mb-4">
                                    <label for="manual-address" class="form-label-custom">Address</label>
                                    <div class="input-group-custom mb-0">
                                        <i class="bi bi-geo-alt input-icon"></i>
                                        <input type="text" name="address" id="manual-address" class="form-control-custom" placeholder="Complete address" required>
                                    </div>
                                </div>

                                <!-- Photo Upload Zone -->
                                <div class="mb-4">
                                    <label class="form-label-custom">Candidate Photo</label>
                                    <label for="inputGroupFile03" class="upload-zone py-3 mb-0" style="padding: 15px 20px;">
                                        <input type="file" name="user_image" class="d-none" id="inputGroupFile03" accept="image/*">
                                        <i class="bi bi-image upload-icon fs-4 mb-1"></i>
                                        <span class="upload-text text-sm">Choose Photo (Optional)</span>
                                        <span class="upload-subtext small">Click to upload JPG/PNG</span>
                                    </label>
                                </div>

                                <!-- File Preview Zone -->
                                <div id="demo-manual" class="file-preview mb-3"></div>

                                <!-- Submit Button -->
                                <div class="pt-2">
                                    <button type="submit" class="btn-submit-custom">
                                        <i class="bi bi-patch-check-fill"></i>
                                        Generate Certificate
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
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

        document.getElementById('inputGroupFile03').addEventListener('change', function(event) {
            var x = document.getElementById("inputGroupFile03");
            var txt = "";
            if ('files' in x) {
                if (x.files.length > 0) {
                    var file = x.files[0];
                    txt += "<div class='file-preview-item'>";
                    txt += "<span><i class='bi bi-image text-success me-2 fs-5 align-middle'></i>" + file.name + " </span>";
                    txt += "<button type='button' class='btn-remove-manual border-0 bg-transparent text-danger ms-2'><i class='bi bi-x-lg'></i></button></div>";
                }
            } 
            document.getElementById("demo-manual").innerHTML = txt;

            // Add event listener for remove button
            var removeBtn = document.querySelector('.btn-remove-manual');
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    x.value = ''; // Clear the file input
                    document.getElementById("demo-manual").innerHTML = ''; // Clear the preview area
                });
            }
        });
    </script>

</body>
</html>