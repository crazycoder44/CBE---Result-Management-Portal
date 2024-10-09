<?php
session_start();    
include_once 'dbConnection.php';


// Check if form is submitted
if (isset($_POST['submit'])) {
    // Process form data
    $sessionvalue = htmlspecialchars($_POST['session']);
    $termvalue = htmlspecialchars($_POST['termid']);


    // Check if session and term are valid (you may add more validation as needed)
    if (!empty($sessionvalue) && !empty($termvalue)) {
        // Assuming you have already connected to your database using $con

        // Get the staff ID from session (replace with your actual way of getting staff ID)
        $staffid = $_SESSION['staffid'];

        // Check if the staff is a class guardian and fetch associated classid
        $classQuery = mysqli_query($con, "
            SELECT DISTINCT classid 
            FROM class 
            WHERE staffid = '$staffid'
        ");

        // Fetch the term name
        $termQuery = mysqli_query($con, "
            SELECT term 
            FROM terms 
            WHERE termid = '$termvalue'
        ");

        if (mysqli_num_rows($termQuery) > 0) {
            $termRow = mysqli_fetch_assoc($termQuery);
            $termname = $termRow['term'];
        } else {
            $termname = 'Unknown Term';
        }

        if (mysqli_num_rows($classQuery) > 0) {
            // Staff is a class guardian, proceed to generate broadsheet
            $classRow = mysqli_fetch_assoc($classQuery);
            $classid = $classRow['classid'];
            $baseclassid = substr($classid, 0, -1);
            $class = $baseclassid;

           
            }
        }
    }
    $_SESSION['results'] = [
        'class' => $class,
        'session' =>$sessionvalue,
        'term' =>$termvalue,
        'staffid'=>$staffid
    ];

    header('Location: broadd.php'); // Redirect after processing
   exit; // Ensure no further code is executed

?>
