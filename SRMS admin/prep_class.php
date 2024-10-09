<?php
include('includes/config.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form values
    $email = $_POST['email'];
    $sid = $_POST['sid'];
    $sessionvalue = $_POST['session'];
    $termvalue = $_POST['term'];
    // Get student details
    $student_query = mysqli_query($con, "SELECT fname, lname FROM students WHERE email = '$email'");
    $student = mysqli_fetch_array($student_query);
    $studentname = $student['fname'] . ' ' . $student['lname'];

    // Get distinct classid from results table
    $class_query = mysqli_query($con, "
        SELECT DISTINCT classid 
        FROM results 
        WHERE email = '$email' 
        AND session = '$sessionvalue'
    ");
    $class_row = mysqli_fetch_array($class_query);
    $class = $class_row['classid'];
    $baseclassid = substr($class, 0, -1);

    // Get the distinct value of the noinclass column
    $studentcount_query = mysqli_query($con, "
        SELECT DISTINCT noinclass AS studentcount 
        FROM results 
        WHERE email = '$email' 
        AND session = '$sessionvalue' 
        AND termid = '$termvalue'
    ");
    $studentcount_row = mysqli_fetch_array($studentcount_query);
    $studentcount = $studentcount_row['studentcount'];

    // Fetch number of students (noinclass) in the class based on session, term, and classid
    $noinclassquery = "SELECT COUNT(DISTINCT email) AS noinclass 
                          FROM results 
                          WHERE session = '$sessionvalue' 
                            AND termid = '$termvalue' 
                            AND classid LIKE '$baseclassid'";
    
    // Execute the query
    $noinclassresult = mysqli_query($con, $noinclassquery);

    // Check if query execution was successful
    if ($noinclassresult === false) {
        echo "Error: " . mysqli_error($con);
        exit;
    }

    // Fetch the result as an associative array
    $row = mysqli_fetch_array($noinclassresult);
    $noinclass = $row['noinclass'];

    // Free result set
    mysqli_free_result($noinclassresult);

    // Fetch number of students (noinclassarm) in the class arm based on session, term, and classid
    $noinclassarmquery = "SELECT COUNT(DISTINCT email) AS noinclassarm 
                          FROM results 
                          WHERE session = '$sessionvalue' 
                            AND termid = '$termvalue' 
                            AND classid = '$class'";
    
    // Execute the query
    $result = mysqli_query($con, $noinclassarmquery);

    // Check if query execution was successful
    if ($result === false) {
        echo "Error: " . mysqli_error($con);
        exit;
    }

    // Fetch the result as an associative array
    $row = mysqli_fetch_array($result);
    $noinclassarm = $row['noinclassarm'];

    // Free result set
    mysqli_free_result($result);

    // Get term name
    $term_query = mysqli_query($con, "SELECT term FROM terms WHERE termid = '$termvalue'");
    $term_row = mysqli_fetch_array($term_query);
    $termname = $term_row['term'];

    // Fetch termid
    //$term_query = mysqli_query($con, "SELECT termid FROM terms WHERE term='$termname'");
    //$term = mysqli_fetch_assoc($term_query);
    //$termid = $term['termid'];

    // Fetch subjects and CA marks
    $subject_query = mysqli_query($con, "
        SELECT r.sub_id, s.subject 
        FROM results r
        JOIN subjects s ON r.sub_id = s.sub_id
        WHERE r.email = '$email' 
        AND r.session = '$sessionvalue'
        AND r.termid = '$termvalue'
    ");

    // Calculate total_marks_obtainable
    $total_subjects = mysqli_num_rows($subject_query);
    $total_marks_obtainable = $total_subjects * 100;

    // Prepare the CA marks array
    $ca_marks = [];

    // Fetch each subject and its CA marks
    while ($subject_row = mysqli_fetch_assoc($subject_query)) {
        $sub_id = $subject_row['sub_id'];
        $subject_name = strtoupper($subject_row['subject']); 

        // Fetch total scores for the first, second, and third terms
        $total_scores = [];
        for ($term = 1; $term <= $termvalue; $term++) {
            $term_query = mysqli_query($con, "
                SELECT (CA + examobj + examtheory) AS total 
                FROM results 
                WHERE sub_id = '$sub_id' 
                AND email = '$email' 
                AND termid = '$term' 
                AND session = '$sessionvalue'
            ");
            $term_result = mysqli_fetch_assoc($term_query);
            $total_scores[] = isset($term_result['total']) ? (int)$term_result['total'] : 0;
        }

        // Fetch CA marks for the current term
        $ca_query = mysqli_query($con, "
            SELECT CA, examobj, examtheory 
            FROM results 
            WHERE sub_id = '$sub_id' 
            AND email = '$email' 
            AND termid = '$termvalue' 
            AND session = '$sessionvalue'
        ");
        $ca_result = mysqli_fetch_assoc($ca_query);

        // Calculate total marks for the selected term
        $ca_marks_term = isset($ca_result['CA']) ? (int)$ca_result['CA'] : 0;
        $examobj_marks = isset($ca_result['examobj']) ? (int)$ca_result['examobj'] : 0;
        $examtheory_marks = isset($ca_result['examtheory']) ? (int)$ca_result['examtheory'] : 0;
        $total_marks_sum = $ca_marks_term + $examobj_marks + $examtheory_marks;

        // Calculate persubjectaverage
        $total_across_terms = array_sum($total_scores);
        $persubjectaverage = $total_across_terms / $termvalue;

        // Calculate class average for the subject
        $class_average_query = mysqli_query($con, "
            SELECT SUM(CA + examobj + examtheory) AS class_total_marks, COUNT(email) AS student_count 
            FROM results 
            WHERE session = '$sessionvalue' 
            AND termid = '$termvalue' 
            AND sub_id = '$sub_id' 
            AND classid LIKE '$baseclassid%'
        ");
        $class_average_result = mysqli_fetch_assoc($class_average_query);
        $class_total_marks = $class_average_result['class_total_marks'];
        $student_count = $class_average_result['student_count'];
        $classaverage = $class_total_marks / $student_count;

        // Calculate term totals
        $firstterm_query = mysqli_query($con, "
            SELECT (CA + examobj + examtheory) AS total 
            FROM results 
            WHERE sub_id = '$sub_id' 
            AND email = '$email' 
            AND termid = '1' 
            AND session = '$sessionvalue'
        ");
        $firstterm_result = mysqli_fetch_assoc($firstterm_query);
        $firsttermtotal = isset($firstterm_result['total']) ? round($firstterm_result['total'], 1) : null;

        $secondterm_query = mysqli_query($con, "
            SELECT (CA + examobj + examtheory) AS total 
            FROM results 
            WHERE sub_id = '$sub_id' 
            AND email = '$email' 
            AND termid = '2' 
            AND session = '$sessionvalue'
        ");
        $secondterm_result = mysqli_fetch_assoc($secondterm_query);
        $secondtermtotal = isset($secondterm_result['total']) ? round($secondterm_result['total'], 1) : null;

        $thirdterm_query = mysqli_query($con, "
            SELECT (CA + examobj + examtheory) AS total 
            FROM results 
            WHERE sub_id = '$sub_id' 
            AND email = '$email' 
            AND termid = '3' 
            AND session = '$sessionvalue'
        ");
        $thirdterm_result = mysqli_fetch_assoc($thirdterm_query);
        $thirdtermtotal = isset($thirdterm_result['total']) ? round($thirdterm_result['total'], 1) : null;

        // Calculate cumulative total and average
        $cumtotal = array_sum(array_filter([$firsttermtotal, $secondtermtotal, $thirdtermtotal]));
        $non_null_terms = count(array_filter([$firsttermtotal, $secondtermtotal, $thirdtermtotal]));
        $cumavg = $non_null_terms > 0 ? $cumtotal / $non_null_terms : 0;

        // Store CA marks for later use
        $ca_marks[] = [
            'sub_id' => $sub_id,
            'subject_name' => $subject_name,
            'CA' => $ca_result['CA'],
            'examobj' => $ca_result['examobj'],
            'examtheory' => $ca_result['examtheory'],
            'exam' => $ca_result['examobj'] + $ca_result['examtheory'],
            'total' => $ca_result['CA'] + $ca_result['examobj'] + $ca_result['examtheory'],
            'totalColor' => '#000000',
            'persubjectaverage' => round($persubjectaverage, 1), // Round the average to 1 decimal places
            'classaverage' => round($classaverage, 1), // Round the class average to 1 decimal places
            'firsttermtotal' => $firsttermtotal,
            'secondtermtotal' => $secondtermtotal,
            'thirdtermtotal' => $thirdtermtotal,
            'cumtotal' => $cumtotal,
            'cumtotalColor' => '#000000',
            'cumavg' => round($cumavg, 1),
            'grade' => '',
            'gradeColor' => '#000000',
            'remark' => '',
            'remarkColor' => '#000000',
            'cumgrade' => '',
            'cumgradeColor' => '#000000',
            'cumremark' => '',
            'cumremarkColor' => '#000000'
        ];
    }

    // Initialize variables with default values
    $total_marks_obtained = 0;

    foreach ($ca_marks as $marks) {
        $total_marks_obtained += $marks['total'];
    }

    $average = $total_marks_obtained / $total_subjects;

    function ordinalSuffix($num) {
        $suffix = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if (($num % 100) >= 11 && ($num % 100) <= 13) {
            return $num . 'th';
        } else {
            return $num . $suffix[$num % 10];
        }
    }

    // Calculate the class position for each subject
    foreach ($ca_marks as &$marks) {
        $sub_id = $marks['sub_id'];

        // Create a temporary table for ranking
        $temp_table_name = "temp_rankings_$sub_id";
        mysqli_query($con, "CREATE TEMPORARY TABLE $temp_table_name (
          email VARCHAR(100),
          totalscore INT,
          position INT
        )");

        // Insert values into the temporary table
        $insert_query = "INSERT INTO $temp_table_name (email, totalscore, position)
                         SELECT email, (CA + examobj + examtheory) AS totalscore, 
                                ROW_NUMBER() OVER (ORDER BY (CA + examobj + examtheory) DESC) AS position
                         FROM results
                         WHERE sub_id = '$sub_id' AND termid = '$termvalue' AND session = '$sessionvalue'
                           AND email IN (SELECT email FROM students WHERE classid LIKE '$baseclassid%')
                         ORDER BY totalscore DESC";
        mysqli_query($con, $insert_query);

        // Get the position for the student
        $position_query = mysqli_query($con, "SELECT position FROM $temp_table_name WHERE email = '$email'");
        $position_row = mysqli_fetch_assoc($position_query);
        $position = $position_row['position'];
        $suffixedPosition = ordinalSuffix($position);

        // Check if position is null and set default message
        if (is_null($position)) {
            $position = 'NO POSITION YET';
        }

        $marks['position'] = $suffixedPosition;
        
        // Drop the temporary table
        mysqli_query($con, "DROP TEMPORARY TABLE $temp_table_name");

        
    }
    unset($marks);

      // Assuming $conn is your MySQLi connection
    $query = "SELECT status FROM comments WHERE sid = '$sid' AND term = '$termvalue' AND session = '$sessionvalue'";
    $query_comment = "SELECT Guardian_Comments FROM comments WHERE sid = '$sid' AND term = '$termvalue' AND session = '$sessionvalue'";
    $query_criteria = "SELECT Criteria from comments WHERE  sid = '$sid' AND term = '$termvalue' AND session = '$sessionvalue'";
    $result = mysqli_query($con, $query); // Execute the query
    $result_comment = mysqli_query($con, $query_comment); // Execute the query
    $result_criteria = mysqli_query($con, $query_criteria); // Execute the query

    if ($result) {
        $row = mysqli_fetch_assoc($result); // Fetch the result row
        $status =  $row['status']; // Output the 'comments' value
    }

    if ($result_comment) {
        $row = mysqli_fetch_assoc($result_comment); // Fetch the result row
        $comments =  $row['Guardian_Comments']; // Output the 'comments' value
    }

    if ($result_criteria) {
        $row = mysqli_fetch_assoc($result_criteria); // Fetch the result row
        $criteria =  $row['Criteria']; // Output the 'comments' value
    }

    // Calculate the arm position for each subject
    foreach ($ca_marks as &$marks) {
        $sub_id = $marks['sub_id'];

        // Create a temporary table for ranking
        $temp_table_name = "temp_rankings_$sub_id";
        mysqli_query($con, "CREATE TEMPORARY TABLE $temp_table_name (
          email VARCHAR(100),
          totalscore INT,
          position INT
        )");

        // Insert values into the temporary table
        $insert_query = "INSERT INTO $temp_table_name (email, totalscore, position)
                         SELECT email, (CA + examobj + examtheory) AS totalscore, 
                                ROW_NUMBER() OVER (ORDER BY (CA + examobj + examtheory) DESC) AS position
                         FROM results
                         WHERE sub_id = '$sub_id' AND termid = '$termvalue' AND session = '$sessionvalue'
                           AND email IN (SELECT email FROM students WHERE classid = '$class')
                         ORDER BY totalscore DESC";
        mysqli_query($con, $insert_query);

        // Get the position for the student
        $position_query = mysqli_query($con, "SELECT position FROM $temp_table_name WHERE email = '$email'");
        $position_row = mysqli_fetch_assoc($position_query);
        $armposition = $position_row['position'];
        $suffixedArmPosition = ordinalSuffix($armposition);

        // Check if position is null and set default message
        if (is_null($armposition)) {
            $armposition = 'NO POSITION YET';
        }

        $marks['armposition'] = $suffixedArmPosition;
        
        // Drop the temporary table
        mysqli_query($con, "DROP TEMPORARY TABLE $temp_table_name");

        
    }
    unset($marks);


    // Create a temporary table for class ranking
    $temp_table_name = "temp_class_ranking";
    mysqli_query($con, "CREATE TEMPORARY TABLE $temp_table_name (
    email VARCHAR(100),
    average FLOAT,
    position INT
    )");

    // Insert values into the temporary table
    $insert_query = "INSERT INTO $temp_table_name (email, average, position)
                    SELECT email, AVG(CA + examobj + examtheory) AS average,
                            ROW_NUMBER() OVER (ORDER BY AVG(CA + examobj + examtheory) DESC) AS position
                    FROM results
                    WHERE session = '$sessionvalue' AND termid = '$termvalue'
                    AND email IN (SELECT email FROM students WHERE classid LIKE '$baseclassid%')
                    GROUP BY email
                    ORDER BY average DESC";
    mysqli_query($con, $insert_query);

    // Get the position for the current student
    $position_query = mysqli_query($con, "SELECT position FROM $temp_table_name WHERE email = '$email'");
    $classposition_row = mysqli_fetch_assoc($position_query);
    $classposition = $classposition_row['position'];
    $suffixedClassPosition = ordinalSuffix($classposition);

    // Drop the temporary table
    mysqli_query($con, "DROP TEMPORARY TABLE $temp_table_name");

    // Create a temporary table for class arm ranking
    $temp_table_name = "temp_class_arm_ranking";
    mysqli_query($con, "CREATE TEMPORARY TABLE $temp_table_name (
    email VARCHAR(100),
    average FLOAT,
    position INT
    )");

    // Insert values into the temporary table
    $insert_query = "INSERT INTO $temp_table_name (email, average, position)
                    SELECT email, AVG(CA + examobj + examtheory) AS average,
                            ROW_NUMBER() OVER (ORDER BY AVG(CA + examobj + examtheory) DESC) AS position
                    FROM results
                    WHERE session = '$sessionvalue' AND termid = '$termvalue'
                    AND email IN (SELECT email FROM students WHERE classid = '$class')
                    GROUP BY email
                    ORDER BY average DESC";
    mysqli_query($con, $insert_query);

    // Get the position for the current student
    $armposition_query = mysqli_query($con, "SELECT position FROM $temp_table_name WHERE email = '$email'");
    $classarmposition_row = mysqli_fetch_assoc($armposition_query);
    $classarmposition = $classarmposition_row['position'];
    $suffixedClassArmPosition = ordinalSuffix($classarmposition);

    // Drop the temporary table
    mysqli_query($con, "DROP TEMPORARY TABLE $temp_table_name");
 }
    session_start();
    // Store all results in a session 
    $_SESSION['results'] = [
        'email' => $email,
        'session' => $sessionvalue,
        'term' => $termvalue,
        'studentname' => $studentname,
        'noinclass' => $noinclass,
        'noinclassarm' => $noinclassarm,
        'termname' => $termname,
        'ca_marks' => $ca_marks,
        'total_marks_obtainable' => $total_marks_obtainable,
        'total_marks_obtained' => $total_marks_obtained,
        'average'=> $average,
        'suffixedClassPosition' => $suffixedClassPosition,
        'baseclassid' => $baseclassid,
        'sid' => $sid,
        'suffixedClassArmPosition' => $suffixedClassArmPosition,
        'class' => $class,
        'studentcount' => $studentcount,
        'status' => $status,
        'comments' => $comments,
        'criteria' => $criteria
    ];
    
    
    // Redirect to classresult.php
    header('Location: classresult.php');
    exit; // Ensure no further code is executed
?>
