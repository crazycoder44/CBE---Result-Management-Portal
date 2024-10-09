<?php
include_once 'dbConnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $session = mysqli_real_escape_string($con, $_POST['session']);
    $term = mysqli_real_escape_string($con, $_POST['term']);
    $sub_id = mysqli_real_escape_string($con, $_POST['subject']);
    $class = mysqli_real_escape_string($con, $_POST['class']);
    $staffid = mysqli_real_escape_string($con, $_POST['staffid']);

    // Define baseclass variable
    $baseclass = substr($class, 0, -1); // Remove the last character from the classid

    // Fetch students based on subject and class
    $studentQuery = mysqli_query($con, "
        SELECT s.sid, s.fname, s.lname, s.email 
        FROM students s
        JOIN student_class_subject scs ON s.sid = scs.sid
        WHERE scs.sub_id = '$sub_id' AND scs.classid = '$class'
    ");

    $sn = 1;
    while ($studentRow = mysqli_fetch_assoc($studentQuery)) {
        $sid = $studentRow['sid'];
        $fname = $studentRow['fname'];
        $lname = $studentRow['lname'];
        $email = $studentRow['email'];
        $studentName = $fname . ' ' . $lname;

        // Initialize score to 0
        $score = 0;

        // Query to get eid from exams table
        $eidQuery = mysqli_query($con, "
            SELECT eid 
            FROM exams 
            WHERE session = '$session' AND termid = '$term' AND sub_id = '$sub_id' AND classid LIKE '$baseclass%'
        ");

        if (mysqli_num_rows($eidQuery) > 0) {
            $eidRow = mysqli_fetch_assoc($eidQuery);
            $eid = $eidRow['eid'];

            // Query to get score from history table
            $scoreQuery = mysqli_query($con, "
                SELECT score 
                FROM history 
                WHERE eid = '$eid' AND email = '$email'
            ");
            if (mysqli_num_rows($scoreQuery) > 0) {
                $scoreRow = mysqli_fetch_assoc($scoreQuery);
                $score = $scoreRow['score'];
            }
        }

        // Check if values exist in tempresult table
        $resultQuery = mysqli_query($con, "
            SELECT * 
            FROM tempresult 
            WHERE email = '$email' AND session = '$session' AND termid = '$term' AND sub_id = '$sub_id' AND classid = '$class'
        ");
        
        // If a result is found, use its data. Otherwise, default to 0 or empty.
        if (mysqli_num_rows($resultQuery) > 0) {
            $resultRow = mysqli_fetch_assoc($resultQuery);
            $ca = $resultRow['ca'];
            // $examobj = $resultRow['examobj'];
            $examtheory = $resultRow['examtheory'];
            $total = $resultRow['total'];
            $grade = $resultRow['grade'];
        } else {
            // Default values if no record in tempresult
            $ca = 0;
            // $examobj = 0;
            $examtheory = 0;
            $total = 0;
            $grade = 'F';
        }

        echo '<tr>';
        echo '<td>' . $sn . '</td>';
        echo '<td>' . $studentName . '</td>';
        echo '<td>' . $session . '</td>';
        echo '<td>' . $term . '</td>';
        echo '<td>' . $class . '</td>';
        echo '<td><input type="number" name="ca[' . $sid . ']" value="' . $ca . '" min="0" max="40" required></td>';
        echo '<td><input type="number" name="examobj[' . $sid . ']" value="' . $score . '" min="0" max="30" required></td>';
        echo '<td><input type="number" name="examtheory[' . $sid . ']" value="' . $examtheory . '" min="0" required></td>';
        echo '<td class="total">' . $total . '</td>';
        echo '<td class="grade">' .$grade. '</td>';
        echo '<td><input type="hidden" name="sid[' . $sid . ']" value="' . $sid . '"></td>';
        echo '</tr>';
        $sn++;
    }
}
?>
