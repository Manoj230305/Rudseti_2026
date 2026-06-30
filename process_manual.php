<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    header("Location: logout.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Set session variables from post parameters
    $_SESSION['batch'] = isset($_POST['batch']) ? trim($_POST['batch']) : 'Null';
    $_SESSION['Training'] = isset($_POST['Training']) ? trim($_POST['Training']) : 'Null';
    $_SESSION['Start_Date'] = isset($_POST['Start_Date']) ? trim($_POST['Start_Date']) : 'Null';
    $_SESSION['End_Date'] = isset($_POST['End_Date']) ? trim($_POST['End_Date']) : 'Null';
    $_SESSION['Sponsors'] = isset($_POST['Sponsors']) ? trim($_POST['Sponsors']) : 'Null';
    $_SESSION['manual'] = true;

    // Clean and extract Father's/Dependent's name (only strip S/O, D/O prefixes if present)
    $depName = isset($_POST['Dependent_name']) ? trim($_POST['Dependent_name']) : 'Null';
    if (preg_match('/^(s[\/\.]?o|d[\/\.]?o|w[\/\.]?o|s\.o\.|d\.o\.|w\.o\.)[\s\.:]*/i', $depName, $matches)) {
        $depName = trim(substr($depName, strlen($matches[0])));
    }

    // Clean and format Address
    $address = isset($_POST['address']) ? trim($_POST['address']) : 'Null';

    // Prepare $_POST variables for test3.php
    $_POST['roll_no'] = isset($_POST['roll_no']) ? trim($_POST['roll_no']) : 'Null';
    $_POST['candidate_id'] = isset($_POST['candidate_id']) ? trim($_POST['candidate_id']) : 'Null';
    $_POST['customer_name'] = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : 'Null';
    $_POST['Dependent_name'] = $depName;
    $_POST['address'] = $address;

    // Handle photo upload logic: if no photo is uploaded, unset user_image so test3.php falls back to pictures/1050.jpg
    if (!isset($_FILES['user_image']) || $_FILES['user_image']['error'] == UPLOAD_ERR_NO_FILE || empty($_FILES['user_image']['tmp_name'])) {
        unset($_FILES['user_image']);
    }

    // Run the certificate generator logic in test3.php
    require 'test3.php';
} else {
    header("Location: home.php");
    exit();
}
?>
