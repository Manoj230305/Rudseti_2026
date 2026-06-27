<?php
// Start the session (if not already started)
session_start();

if (isset($_SESSION['username']) && isset($_SESSION['admin'])) {
    header("Location: /rseti/home.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    
    // Check if username and password are 'admin'
    if ($username == 'admin' && $password == 'admin') {
        // Valid credentials, redirect to a success page or do further actions
        $_SESSION['username'] = $username; // Storing username in session variable
        $_SESSION['admin'] = true;
        header("Location: /rseti/home.php"); // Redirect to success page
        exit();
    } else {
        // Invalid credentials, show error message
        header("Location: index.php?log=invalid");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - RUDSETI</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/img/nexlogo.png"/>
    
    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #152232 0%, #284266 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #333333;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 410px;
            padding: 40px 35px;
            transition: all 0.3s ease;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo {
            max-height: 56px;
            width: auto;
            margin-bottom: 14px;
        }

        .brand-title {
            color: #284266;
            font-weight: 800;
            font-size: 1.65rem;
            margin-bottom: 4px;
            letter-spacing: -0.8px;
        }

        .brand-subtitle {
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
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

        .btn-primary-custom {
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

        .btn-primary-custom:hover {
            background-color: #1a2d42;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 66, 102, 0.2);
        }

        .btn-primary-custom:active {
            transform: translateY(0);
        }
        
        .footer-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.85rem;
        }
        
        .footer-link a {
            color: #284266;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-header">
            <img src="../assets/img/rsetilogo.png" class="brand-logo" alt="RUDSETI Logo">
            <h1 class="brand-title">RUDSETI</h1>
            <p class="brand-subtitle">Automated Certificate Portal</p>
        </div>

        <?php
        if (isset($_GET['log']) && $_GET['log'] == 'invalid') {
            echo '<div class="alert alert-danger py-2 px-3 border-0 small text-center mb-4 d-flex align-items-center justify-content-center gap-2" role="alert" style="border-radius: 8px; font-size: 0.85rem;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Invalid Credentials!
                  </div>';
        }
        ?>

        <form class="login-form" method="POST" action="">
            <div class="input-group-custom">
                <i class="bi bi-person input-icon"></i>
                <input class="form-control-custom" type="text" name="username" placeholder="Username" required>
            </div>

            <div class="input-group-custom">
                <i class="bi bi-lock input-icon"></i>
                <input class="form-control-custom" type="password" name="password" placeholder="Password" required>
            </div>
            
            <div class="pt-2">
                <button type="submit" class="btn-primary-custom">
                    Sign In
                    <i class="bi bi-box-arrow-in-right"></i>
                </button>
            </div>
        </form>

        <div class="footer-link">
            <a href="../index.html">
                <i class="bi bi-arrow-left"></i>
                Back to Home
            </a>
        </div>
    </div>

</body>
</html>
