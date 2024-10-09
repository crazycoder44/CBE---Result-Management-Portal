<?php
session_start();
if(isset($_SESSION["email"])){
session_destroy();
}
include_once 'dbConnection.php';
$ref=@$_GET['q'];
$email = $_POST['email'];
$password = $_POST['password'];

$email = stripslashes($email);
$email = addslashes($email);
$password = stripslashes($password); 
$password = addslashes($password);
$password=md5($password); 
$result = mysqli_query($con,"SELECT fname, sid FROM students WHERE email = '$email' and password = '$password'") or die('Error');
$count=mysqli_num_rows($result);
if($count==1){
while($row = mysqli_fetch_array($result)) {
	$fname = $row['fname'];
	$sid = $row['sid'];
}
$_SESSION["fname"] = $fname;
$_SESSION["email"] = $email;
$_SESSION["sid"] = $sid;
header("location:account.php?q=47");
}
else
header("location:$ref?w=Wrong Username or Password");

?>