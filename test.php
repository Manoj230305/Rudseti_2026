<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    header("Location: logout.php");
    exit();
}



    $varText3 = isset($_POST['batch']) ? $_POST['batch'] : 'Null';
    $varText4 = isset($_POST['Training']) ? $_POST['Training'] : 'Null';
    $varText5 = isset($_POST['Start_Date']) ? $_POST['Start_Date'] : 'Null';
    $varText6 = isset($_POST['End_Date']) ? $_POST['End_Date'] : 'Null';
    $varText7 = isset($_POST['Sponsors']) ? $_POST['Sponsors'] : 'Null';

    $_SESSION['batch'] = $varText3;
    $_SESSION['Training'] = $varText4;
    $_SESSION['Start_Date'] = $varText5;
    $_SESSION['End_Date'] = $varText6;
    $_SESSION['Sponsors'] = $varText7;

    header("Location: data.php")

?>
