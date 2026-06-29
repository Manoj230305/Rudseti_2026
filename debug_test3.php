<?php
session_start();
$_SESSION['username'] = 'admin';
$_SESSION['admin'] = true;
$_SESSION['batch'] = '402';
$_SESSION['Training'] = 'Computer Training Program (EDP) John Doe John Doe John Doe John Doe';
$_SESSION['Start_Date'] = '01/06/2026';
$_SESSION['End_Date'] = '30/06/2026';
$_SESSION['Sponsors'] = 'Test Sponsor John Doe John Doe John Doe John Doe John Doe ';

$_POST['serial'] = '12345';
$_POST['roll_no'] = '1';
$_POST['customer_name'] = 'John Doe John Doe John Doe';
$_POST['Dependent_name'] = 'Richard Doe Richard Doe John Doe';
$_POST['address'] = '123 Test Street, Test City John Doe John Doe John Doe John DoeJohn Doe John Doe John Doe John Doe';

require_once 'test3.php';
echo "\nExecution completed successfully!\n";
?>
