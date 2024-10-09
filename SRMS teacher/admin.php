<?php
/*session_start();
if(isset($_SESSION['email'])){
session_unset();
}
include_once 'dbConnection.php';
$ref=@$_GET['q'];
$email = $_POST['uname'];
$password = $_POST['password'];

$email = stripslashes($email);
$email = addslashes($email);
$password = stripslashes($password); 
$password = addslashes($password);
$result = mysqli_query($con,"SELECT fname FROM admin WHERE email = '$email' and password = '$password'") or die('Error');
$count=mysqli_num_rows($result);
if($count==1){
while($row = mysqli_fetch_array($result)) {
	$fname = $row['fname'];
	$staffid = $row['staffid'];
}
$_SESSION["fname"] = $fname;
$_SESSION["key"] ='emedit09050118734';
$_SESSION["email"] = $email;
$_SESSION["staffid"] = $staffid;
header("location:dash.php?q=0.5");
}
else header("location:$ref?w=Warning : Access denied");*/
session_start();
if (isset($_SESSION['email'])) {
    session_unset();
}

include_once 'dbConnection.php';
$ref = @$_GET['q'];
$email = $_POST['uname'];
$password = $_POST['password'];

// Escaping special characters for security
$email = mysqli_real_escape_string($con, stripslashes($email));
$password = mysqli_real_escape_string($con, stripslashes($password));

// Select the necessary fields from the admin table
$result = mysqli_query($con, "SELECT fname, staffid FROM admin WHERE email = '$email' AND password = '$password'") or die('Error: ' . mysqli_error($con));
$count = mysqli_num_rows($result);

if ($count == 1) {
    while ($row = mysqli_fetch_array($result)) {
        $fname = $row['fname'];
        $staffid = $row['staffid'];
    }
    $_SESSION["fname"] = $fname;
    $_SESSION["key"] = 'emedit09050118734';
    $_SESSION["email"] = $email;
    $_SESSION["staffid"] = $staffid;
    header("location:dash.php?q=0.5");
} else {
    header("location:$ref?w=Warning : Access denied");
}
?>
