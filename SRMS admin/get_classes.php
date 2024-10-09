<?php
include_once 'dbConnection.php'; // Ensure this file includes your database connection code

session_start();
if (isset($_SESSION['fname'])) {
    echo $_SESSION['fname'];
} else {
    echo '|| Dominican Portal';
}

// Check if email is set in session, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("location:index.php");
    exit(); // It's good practice to exit after header redirection
}

// Retrieve session variables
$fname = $_SESSION['fname'];
$email = $_SESSION['email'];
$staffid = $_SESSION['staffid'];

if (isset($_POST['sub_id'])) {
    $sub_id = $_POST['sub_id'];

    // Debugging line
    error_log("Received sub_id: " . $sub_id . ", staffid: " . $staffid);

    // Fetch classids associated with the staffid and selected sub_id
    $class_query = "
    SELECT DISTINCT scs.classid 
    FROM staff_class_subject scs
    WHERE scs.staffid = '$staffid' AND scs.sub_id = '$sub_id'";
    $class_result = mysqli_query($con, $class_query) or die(mysqli_error($con));

    if (mysqli_num_rows($class_result) > 0) {
        echo '<option value="" disabled selected>Select Class</option>';
        while ($row = mysqli_fetch_assoc($class_result)) {
            echo '<option value="' . $row['classid'] . '">' . $row['classid'] . '</option>';
        }
    } else {
        echo '<option value="" disabled>No classes found</option>';
    }
}
?>