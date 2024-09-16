<?php

/*include_once 'dbConnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $session = mysqli_real_escape_string($con, $_POST['session']);
    $term = mysqli_real_escape_string($con, $_POST['term']);
    $sub_id = mysqli_real_escape_string($con, $_POST['subject']);
    $class = mysqli_real_escape_string($con, $_POST['class']);
    $staffid = mysqli_real_escape_string($con, $_POST['staffid']);

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
            WHERE session = '$session' AND termid = '$term' AND sub_id = '$sub_id' AND classid = '$class'
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

        echo '<tr>';
        echo '<td>' . $sn . '</td>';
        echo '<td>' . $studentName . '</td>';
        echo '<td>' . $email . '</td>';
        echo '<td>' . $session . '</td>';
        echo '<td>' . $term . '</td>';
        echo '<td>' . $sub_id . '</td>';
        echo '<td>' . $class . '</td>';
        echo '<td><input type="number" name="ca[' . $sid . ']" value="0" min="0" max="40" required></td>';
        echo '<td><input type="number" name="examobj[' . $sid . ']" value="' . $score . '" min="0" max="30" required></td>';
        echo '<td><input type="number" name="examtheory[' . $sid . ']" value="0" min="0" max="30" required></td>';
        echo '<td class="total">0</td>';
        echo '<td class="grade">F</td>';
        echo '<td><input type="hidden" name="sid[' . $sid . ']" value="' . $sid . '"></td>';
        echo '</tr>';
        $sn++;
    }
}*/

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

        echo '<tr>';
        echo '<td>' . $sn . '</td>';
        echo '<td>' . $studentName . '</td>';
        echo '<td>' . $email . '</td>';
        echo '<td>' . $session . '</td>';
        echo '<td>' . $term . '</td>';
        echo '<td>' . $sub_id . '</td>';
        echo '<td>' . $class . '</td>';
        echo '<td><input type="number" name="rt[' . $sid . ']" value="0" min="0" max="2" required></td>';
        echo '<td><input type="number" name="hass[' . $sid . ']" value="0" min="0" max="5" required></td>';
        echo '<td><input type="number" name="ass1[' . $sid . ']" value="0" min="0" max="5" required></td>';
        echo '<td><input type="number" name="ass2[' . $sid . ']" value="0" min="0" max="5" required></td>';
        echo '<td class="ass-avg">0</td>';
        echo '<td><input type="number" name="cl1[' . $sid . ']" value="0" min="0" max="5" required></td>';
        echo '<td><input type="number" name="cl2[' . $sid . ']" value="0" min="0" max="5" required></td>';
        echo '<td><input type="number" name="cl3[' . $sid . ']" value="0" min="0" max="5" required></td>';
        echo '<td class="cl-avg">0</td>';
        echo '<td><input type="number" name="mtt[' . $sid . ']" value="0" min="0" max="20" required></td>';
        echo '<td><input type="number" name="nt1[' . $sid . ']" value="0" min="0" max="3" required></td>';
        echo '<td><input type="number" name="nt2[' . $sid . ']" value="0" min="0" max="3" required></td>';
        echo '<td><input type="number" name="nt3[' . $sid . ']" value="0" min="0" max="3" required></td>';
        echo '<td class="nt-avg">0</td>';
        echo '<td><input type="number" name="proj[' . $sid . ']" value="0" min="0" max="5" required></td>';
        echo '<td><input type="number" name="ca[' . $sid . ']" value="0" min="0" max="40" required></td>';
        echo '<td><input type="number" name="examobj[' . $sid . ']" value="' . $score . '" min="0" max="30" required></td>';
        echo '<td><input type="number" name="examtheory[' . $sid . ']" value="0" min="0" max="60" required></td>';
        echo '<td class="total">0</td>';
        echo '<td class="grade">F</td>';
        echo '<td><input type="hidden" name="sid[' . $sid . ']" value="' . $sid . '"></td>';
        echo '</tr>';
        $sn++;
    }
}
?>
