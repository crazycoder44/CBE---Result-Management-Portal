<?php
// Ensure the session is started to access session variables like $staffid
session_start();
include('includes/config.php');
include('includes/dbConnection.php');

// Check if the required POST variables are set
if (isset($_POST['session']) && isset($_POST['term']) && isset($_POST['staffid'])) {
    // Retrieve the POST variables
    $session = $_POST['session'];
    $termid = $_POST['term'];
    $staffid = $_POST['staffid'];

    // Query to fetch the classid based on staffid
    $class_query = "SELECT classid FROM class WHERE staffid = :staffid";
    $class_stmt = $dbh->prepare($class_query);
    $class_stmt->bindParam(':staffid', $staffid, PDO::PARAM_STR);
    $class_stmt->execute();
    $class_result = $class_stmt->fetch(PDO::FETCH_OBJ);

    // Check if a classid was returned, if not handle the error
    if ($class_result) {
        $classid = $class_result->classid; // Assign the classid value
    } else {
        // Handle case where no classid is found for the staffid
        echo "No class assigned for the given staff.";
        exit;
    }

    // Define baseclass variable
    $baseclass = substr($classid, 0, -1); // Remove the last character from the classid

    // Prepare the SQL query to get email, fname, lname from students table where classid is LIKE baseclass%
    $sql = "SELECT email, classid, fname, lname
    FROM students
    WHERE classid LIKE :baseclass";

    // Prepare and bind the query
    $query = $dbh->prepare($sql);
    $baseclass_param = $baseclass . '%'; // Add a wildcard to the baseclass for the LIKE clause
    $query->bindParam(':baseclass', $baseclass_param, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // If results are found
    if ($query->rowCount() > 0) {
        // Output the table rows
        $sn = 1; // Serial number initialization
        foreach ($results as $student) {
            echo '<tr>';
            echo '<td>' . htmlentities($sn++) . '</td>'; // Serial number
            echo '<td>' . htmlentities($student->fname) . ' ' . htmlentities($student->lname) . '</td>';
            echo '<td>' . htmlentities($student->classid) . '</td>'; // Serial number
            echo '<td>';
            echo '<button class="btn btn-primary" style="font-size: 0.75em; padding: 0.37em 0.75em;" onclick="enterRemarks(\'' . htmlentities($student->email) . '\')">Enter Remarks</button>';            echo '</td>'; // Action column            
            echo '</tr>';
        }
    } else {
        // If no results are found
        echo '<tr><td colspan="7">No results found for the selected criteria.</td></tr>';
    }
} else {
    // If any POST parameter is missing
    echo '<tr><td colspan="7">Invalid request. Please fill in all fields.</td></tr>';
}
?>
