<?php
include_once 'dbConnection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure proper escaping and validation of input data
    $session = mysqli_real_escape_string($con, $_POST['session']);
    $term = mysqli_real_escape_string($con, $_POST['term']);
    $sub_id = mysqli_real_escape_string($con, $_POST['sub_id']);
    $staffid = mysqli_real_escape_string($con, $_POST['staffid']);

    $students = json_decode($_POST['students'], true);

    foreach ($students as $student) {
        $sid = mysqli_real_escape_string($con, $student['sid']);
        $ca = mysqli_real_escape_string($con, $student['ca']);
        $examobj = mysqli_real_escape_string($con, $student['examobj']);
        $examtheory = mysqli_real_escape_string($con, $student['examtheory']);

        // Retrieve student email and classid from students table
        $emailQuery = mysqli_query($con, "
            SELECT email, classid 
            FROM students 
            WHERE sid = '$sid'
        ");
        
        if (!$emailQuery) {
            die('Error fetching student email and classid: ' . mysqli_error($con));
        }

        $emailRow = mysqli_fetch_assoc($emailQuery);
        $email = mysqli_real_escape_string($con, $emailRow['email']);
        $classid = mysqli_real_escape_string($con, $emailRow['classid']);

        $baseclassid = substr($classid, 0, -1);

        // Get the number of students in the class
        $noinclassQuery = mysqli_query($con, "
            SELECT COUNT(*) AS noinclass 
            FROM students 
            WHERE classid LIKE '$baseclassid%'
        ");
    
        if (!$noinclassQuery) {
            die('Error fetching number of students in class: ' . mysqli_error($con));
        }
    
        $noinclassRow = mysqli_fetch_assoc($noinclassQuery);
        $noinclass = $noinclassRow['noinclass'];

        // Insert into results table
        $query = "
            INSERT INTO results (email, session, termid, classid, sub_id, ca, examobj, examtheory, noinclass)
            VALUES ('$email', '$session', '$term', '$classid', '$sub_id', '$ca', '$examobj', '$examtheory', '$noinclass')
            ON DUPLICATE KEY UPDATE ca='$ca', examobj='$examobj', examtheory='$examtheory', noinclass='$noinclass'
        ";
        
        if (!mysqli_query($con, $query)) {
            die('Error inserting results: ' . mysqli_error($con));
        }
    }

    echo 'Results posted successfully!';
} else {
    die('Invalid request.');
}
?>
