<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    header("Location: logout.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>NexGenDeV</title>

        <!-- CSS FILES -->        
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/style.css" rel="stylesheet">

        <style>
            .file-preview {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 10px;
            }
            .file-preview-item {
                display: flex;
                align-items: center;
                gap: 10px;
                position: relative;
            }
            .file-preview-item img {
                max-width: 100px;
                max-height: 100px;
            }
            .file-remove {
                background: rgba(255, 0, 0, 0.7);
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                line-height: 20px;
            }    
        </style>

    </head>
    
    <body>

        <nav class="navbar navbar-expand-lg bg-light shadow-lg">
            <div class="container">
                <a class="navbar-brand" href="index.html">
                    <img src="images/Rseti-removebg-preview.png" class="logo img-fluid" alt="">
                    <span>
                        rseti
                        <small>Rural Development and Self Training Institute</small>
                    </span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="/rseti/home/">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="/rseti/generate_pdf.php">Generate PDF</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="/rseti/logout.php">Logout</a>
                        </li>
                 
                    </ul>
                </div>
            </div>
        </nav>

        <main>

            <section class="donate-section">
                <div class="section-overlay"></div>
                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-12 mx-auto">
                            <form class="custom-form volunteer-form mb-5 mb-lg-0" action="test3.php" method="post" enctype="multipart/form-data">
                                <h3 class="mb-4">Certificate Credentials</h3>

                                <div class="row">
                                    <!-- <div class="col-lg-12 col-12">
                                        <input type="text" name="customer_name" id="volunteer-name" class="form-control" placeholder="Name" required>
                                    </div>

                                    <div class="col-lg-12 col-12">
                                        <input type="text" name="Dependent_name" id="volunteer-name" class="form-control" placeholder="Father's Name" required>
                                    </div>

                                    <div class="col-lg-6 col-12">
                                        <input type="hidden" name="Start_Date" class="form-control" placeholder="Start Date" value="" required>
                                    </div>

                                    <div class="col-lg-6 col-12">
                                        <input type="hidden" name="End_Date" class="form-control" placeholder="End Date" value="" required>
                                    </div>

                                    <div class="col-lg-12 col-12">    
                                        <input type="text" name="serial" id="volunteer-name"  class="form-control" placeholder="Unique Number" required>
                                    </div>

                                    <div class="col-lg-12 col-12">    
                                        <input type="hidden" name="batch" id="volunteer-name"  class="form-control" placeholder="Batch Number" value="" required>
                                    </div>

                                    <div class="col-lg-12 col-12">    
                                        <input type="hidden" name="Training" id="volunteer-name"  class="form-control" placeholder="Training Program" value="" required>
                                    </div>
                                    
                                    <div class="col-lg-12 col-12">    
                                        <input type="hidden" name="Sponsors" id="volunteer-name" class="form-control" placeholder="Sponsored by" value="" required>
                                    </div>

                                    <div class="col-lg-12 col-12">
                                        <div class="input-group input-group-file">
                                            <input type="file" name="user_image" class="form-control" id="inputGroupFile02" accept="image/*" multiple>
                                            <label class="input-group-text" for="inputGroupFile02">Upload Photo</label>
                                            <i class="bi-cloud-arrow-up ms-auto"></i>
                                        </div>
                                        <div id="demo" class="file-preview"></div>
                                    </div>
                                </div>

                                <textarea name="address" rows="3" class="form-control" id="volunteer-message" placeholder="Address"></textarea> -->

                                <button type="submit" class="form-control">Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </section>
        </main>


        <footer class="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-12 mb-4">
                        <img src="images/LOGo (2).png" class="logo img-fluid" alt="">
                        <span><br>
                            Nex Gen Dev
                            <small>Solutions</small>
                        </span>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <h5 class="site-footer-title mb-3">Client Information</h5>

                        <p class="text-white d-flex mb-2">
                            <i class="bi-telephone me-2"></i>

                            <a href="tel: 04546-251578" class="site-footer-link">
                                04546-251578
                            </a>
                        </p>

                        <p class="text-white d-flex">
                            <i class="bi-envelope me-2"></i>

                            <a href="mailto:cbrsetitheni@gmail.com" class="site-footer-link">
                                cbrsetitheni@gmail.com
                            </a>
                        </p>

                        <p class="text-white d-flex mt-3">
                            <i class="bi-geo-alt me-2"></i>
                            Karuvelnaickenpatti, Theni - 625531
                        </p>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mx-auto">
                        <h5 class="site-footer-title mb-3">Contact Infomation</h5>

                        <p class="text-white d-flex mb-2">
                            <i class="bi-telephone me-2"></i>

                            <a href="tel: 9791219621" class="site-footer-link">
                                979-121-9621
                            </a>
                        </p>

                        <p class="text-white d-flex">
                            <i class="bi-envelope me-2"></i>

                            <a href="mailto:nexgendev05@gmail.com" class="site-footer-link">
                                nexgendev05@gmail.com
                            </a>
                        </p>

                        <p class="text-white d-flex mt-3">
                            <i class="bi-geo-alt me-2"></i>
                            Madurai
                        </p>

                    </div>
                </div>
            </div>

            <div class="site-footer-bottom">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-md-7 col-12">
                            <p class="copyright-text mb-0">© <a href="#">NeX</a> Gen.
                        	DeV <a href="" target="_blank"></a></p>
                        </div>
                        
                        <div class="col-lg-6 col-md-5 col-12 d-flex justify-content-center align-items-center mx-auto">
                            <ul class="social-icon">
                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-twitter"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-facebook"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-instagram"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-linkedin"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="" class="social-icon-link bi-youtube"></a>
                                </li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
            </div>
        </footer>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/counter.js"></script>
        <script src="js/custom.js"></script>

        <script>
            document.getElementById('inputGroupFile02').addEventListener('change', function(event) {
              var x = document.getElementById("inputGroupFile02");
              var txt = "";
              if ('files' in x) {
                if (x.files.length == 0) {
                  txt = "Select one or more files.";
                } else {
                  for (var i = 0; i < x.files.length; i++) {
                    var file = x.files[i];
                    var fileURL = URL.createObjectURL(file);
                    txt += "<div class='file-preview-item'><img src='" + fileURL + "' alt='" + file.name + "'>";
                    txt += "<span>" + file.name +  " </span>";
                    txt += "<button class='file-remove' data-file-index='" + i + "'>&times;</button></div>";
                  }
                }
              } 
              document.getElementById("demo").innerHTML = txt;
    
              // Add event listeners for remove buttons
              document.querySelectorAll('.file-remove').forEach(function(button) {
                button.addEventListener('click', function() {
                  var index = this.getAttribute('data-file-index');
                  x.value = ''; // Clear the file input
                  var filePreviewItem = this.parentElement;
                  filePreviewItem.parentElement.removeChild(filePreviewItem); // Remove the preview item
                });
              });
            });
        </script>

    </body>
</html>