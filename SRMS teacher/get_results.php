<?php
// Ensure the session is started to access session variables like $staffid
session_start();
include('includes/config.php');
include('includes/dbConnection.php');

// Function to add suffixes to the position
function addPositionSuffix($position) {
    if ($position % 100 >= 11 && $position % 100 <= 13) {
        return $position . 'th';
    }
    switch ($position % 10) {
        case 1: return $position . 'st';
        case 2: return $position . 'nd';
        case 3: return $position . 'rd';
        default: return $position . 'th';
    }
}

// Check if the required POST variables are set
if (isset($_POST['session']) && isset($_POST['term']) && isset($_POST['subject']) && isset($_POST['class']) && isset($_POST['staffid'])) {
    // Retrieve the POST variables
    $session = $_POST['session'];
    $termid = $_POST['term'];
    $sub_id = $_POST['subject'];
    $classid = $_POST['class'];
    $staffid = $_POST['staffid'];

    // Define baseclass variable
    $baseclass = substr($classid, 0, -1); // Remove the last character from the classid
    $class = $baseclass;

    // Prepare the SQL query to get results and student information
    $sql = "SELECT r.email, r.ca, r.examobj, r.examtheory, s.fname, s.lname
            FROM results r
            JOIN students s ON r.email = s.email
            WHERE r.session = :session
            AND r.termid = :termid
            AND r.sub_id = :sub_id
            AND r.classid = :classid";

    $query = $dbh->prepare($sql);
    $query->bindParam(':session', $session, PDO::PARAM_STR);
    $query->bindParam(':termid', $termid, PDO::PARAM_INT);
    $query->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
    $query->bindParam(':classid', $classid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // If results are found
    if ($query->rowCount() > 0) {
        $students = []; // Array to hold student data

        // Loop through results to calculate total and exam score
        foreach ($results as $result) {
            $exam = $result->examobj + $result->examtheory; // Calculate exam score
            $total = $result->ca + $exam; // Calculate total score

            // Add each student and their total score to the array
            $students[] = [
                'sn' => 1, // Placeholder for serial number
                'fname' => $result->fname,
                'lname' => $result->lname,
                'ca' => $result->ca,
                'exam' => $exam,
                'total' => $total,
                'gradeColor' => '',
                'grade' => ''
            ];
        }

        // Sort the students array by total score in descending order
        usort($students, function ($a, $b) {
            return $b['total'] <=> $a['total']; // Sort by total score (descending)
        });

        // Assign positions and calculate grades
        $position = 1;
        foreach ($students as $key => $student) {
            // Calculate grade and grade color based on total score
            $total = $student['total'];
            if (strpos($class, 'jss1') !== false || strpos($class, 'jss2') !== false || strpos($class, 'jss3') !== false) {
                if ($total >= 80) {
                    $students[$key]['grade'] = 'A';
                    $students[$key]['gradeColor'] = 'green';
                } elseif ($total >= 70) {
                    $students[$key]['grade'] = 'B';
                    $students[$key]['gradeColor'] = 'green';
                } elseif ($total >= 60) {
                    $students[$key]['grade'] = 'C';
                    $students[$key]['gradeColor'] = 'green';
                } elseif ($total >= 50) {
                    $students[$key]['grade'] = 'P';
                    $students[$key]['gradeColor'] = 'black';
                } else {
                    $students[$key]['grade'] = 'F';
                    $students[$key]['gradeColor'] = 'red';
                }
            } elseif (strpos($class, 'ss1') !== false || strpos($class, 'ss2') !== false || strpos($class, 'ss3') !== false) {
                if ($total >= 85) {
                    $students[$key]['grade'] = 'A1';
                    $students[$key]['gradeColor'] = 'green';
                } elseif ($total >= 80) {
                    $students[$key]['grade'] = 'B2';
                    $students[$key]['gradeColor'] = 'green';
                } elseif ($total >= 75) {
                    $students[$key]['grade'] = 'B3';
                    $students[$key]['gradeColor'] = 'green';
                } elseif ($total >= 70) {
                    $students[$key]['grade'] = 'C4';
                    $students[$key]['gradeColor'] = 'green';
                } elseif ($total >= 65) {
                    $students[$key]['grade'] = 'C5';
                    $students[$key]['gradeColor'] = 'green';
                } elseif ($total >= 60) {
                    $students[$key]['grade'] = 'C6';
                    $students[$key]['gradeColor'] = 'green';
                } elseif ($total >= 55) {
                    $students[$key]['grade'] = 'D7';
                    $students[$key]['gradeColor'] = 'black';
                } elseif ($total >= 50) {
                    $students[$key]['grade'] = 'E8';
                    $students[$key]['gradeColor'] = 'black';
                } else {
                    $students[$key]['grade'] = 'F9';
                    $students[$key]['gradeColor'] = 'red';
                }
            }

            // Set position and update serial number
            $students[$key]['position'] = addPositionSuffix($position++);
            $students[$key]['sn'] = $key + 1;
        }

        // Output the table rows
        foreach ($students as $student) {
            echo '<tr>';
            echo '<td>' . htmlentities($student['sn']) . '</td>';
            echo '<td>' . htmlentities($student['fname']) . ' ' . htmlentities($student['lname']) . '</td>';
            echo '<td>' . htmlentities($student['ca']) . '</td>';
            echo '<td>' . htmlentities($student['exam']) . '</td>';
            echo '<td>' . htmlentities($student['total']) . '</td>';
            echo '<td style="color:' . htmlentities($student['gradeColor']) . ';">' . htmlentities($student['grade']) . '</td>';
            echo '<td>' . htmlentities($student['position']) . '</td>';
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
