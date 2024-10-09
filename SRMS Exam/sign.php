<?php
include_once 'dbConnection.php';
ob_start();
$fname = $_POST['fname'];
$fname= ucwords(strtolower($fname));
$lname = $_POST['lname'];
$lname= ucwords(strtolower($lname));
$sid = $_POST['sid'];
$gender = $_POST['gender'];
$classid = $_POST['classid'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$fname = stripslashes($fname);

$fname = addslashes($fname);
$fname = ucwords(strtolower($fname));
$lname = stripslashes($lname);
$lname = addslashes($lname);
$lname = ucwords(strtolower($lname));
$gender = stripslashes($gender);
$gender = addslashes($gender);
$email = stripslashes($email);
$email = addslashes($email);
$mobile = stripslashes($mobile);
$mobile = addslashes($mobile);

$password = stripslashes($password);
$password = addslashes($password);
$password = md5($password);

$class_check_query = mysqli_query($con, "SELECT * FROM class WHERE classid = '$classid'");
if (mysqli_num_rows($class_check_query) == 0) {
    // If classid does not exist, redirect with an error message
    header("location:index.php?q7=Invalid Class ID!");
    ob_end_flush();
    exit();
}

$q3=mysqli_query($con,"INSERT INTO students (fname, lname, sid, gender, classid, email, mobile, password)  VALUES  ('$fname', '$lname', '$sid', '$gender', '$classid', '$email', '$mobile', '$password')");
if($q3)
{
session_start();
$_SESSION["email"] = $email;
$_SESSION["fname"] = $fname;
$_SESSION["sid"] = $sid;


header("location:account.php?q=45");
}
else
{
header("location:index.php?q7=Email Already Registered!!!");
}
ob_end_flush();
?>