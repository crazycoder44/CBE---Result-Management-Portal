<?php
session_start();
include_once 'dbConnection.php';

// Check if form is submitted
if (isset($_GET['q']) && $_GET['q'] == '6' && isset($_GET['session']) && isset($_GET['term'])) {
    // Process form data
    $sessionvalue = $_GET['session'];
    $termvalue = $_GET['term'];

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

            // Fetch distinct subjects associated with the class
            $subject_query = mysqli_query($con, "
                SELECT DISTINCT s.sub_id, s.subject 
                FROM subjects s
                INNER JOIN class_subject cs ON s.sub_id = cs.sub_id
                WHERE cs.classid LIKE '$baseclassid%'
            ");

            $subjects = [];
            while ($row = mysqli_fetch_assoc($subject_query)) {
                $sub_id = $row['sub_id'];
                $subject_name = $row['subject'];
                $subjects[$sub_id] = $subject_name;
            }

            // Fetch students based on selected term and session
            $student_query = mysqli_query($con, "
                SELECT s.email, s.sid, s.fname, s.lname, r.sub_id
                FROM students s
                LEFT JOIN results r ON s.email = r.email AND r.session = '$sessionvalue' AND r.termid = '$termvalue'
                WHERE s.sid IN (
                    SELECT DISTINCT s.sid
                    FROM students s
                    INNER JOIN results r ON s.email = r.email
                    WHERE r.classid LIKE '$class%'
                    AND r.session = '$sessionvalue'
                    AND r.termid = '$termvalue'
                )
            ");

            $arm_student_query = mysqli_query($con, "
                SELECT s.email, s.sid, s.fname, s.lname, r.sub_id
                FROM students s
                LEFT JOIN results r ON s.email = r.email AND r.session = '$sessionvalue' AND r.termid = '$termvalue'
                WHERE s.sid IN (
                    SELECT DISTINCT s.sid
                    FROM students s
                    INNER JOIN results r ON s.email = r.email
                    WHERE r.classid LIKE '$classid%'
                    AND r.session = '$sessionvalue'
                    AND r.termid = '$termvalue'
                )
            ");

            if (mysqli_num_rows($student_query) > 0) {
                // Organize students and their marks into an associative array
                $students_data = [];

                while ($student_row = mysqli_fetch_assoc($student_query)) {
                    $sid = $student_row['sid'];
                    $fname = $student_row['fname'];
                    $lname = $student_row['lname'];
                    $sub_id = $student_row['sub_id'];
                    $email = $student_row['email'];

                    // Check if student already exists in array
                    if (!isset($students_data[$sid])) {
                        $students_data[$sid] = [
                            'fname' => $fname,
                            'lname' => $lname,
                            'email' => $email,
                            'marks' => [] // Initialize marks array
                        ];
                    }

                    // Add marks for the subject
                    foreach ($subjects as $subject_id => $subject_name) {
                        $result_query = mysqli_query($con, "
                            SELECT ca, examobj, examtheory
                            FROM results
                            WHERE email = '$email'
                            AND sub_id = '$subject_id'
                            AND termid = '$termvalue'
                            AND session = '$sessionvalue'
                        ");
                        
                        if ($result_row = mysqli_fetch_assoc($result_query)) {
                            $ca = $result_row['ca'];
                            $examobj = $result_row['examobj'];
                            $examtheory = $result_row['examtheory'];
                            $totalscore = $ca + $examobj + $examtheory;
                            $totalscore = number_format($totalscore, 1);
                            $students_data[$sid]['marks'][$subject_id] = $totalscore;
                        } else {
                            // Initialize as empty if no marks found
                            if (!isset($students_data[$sid]['marks'][$subject_id])) {
                                $students_data[$sid]['marks'][$subject_id] = '';
                            }
                        }
                    }
                }

                // Organize and process arm students similarly (skipped for brevity)

                // Calculate totalsubjects, total, and average for each student
                foreach ($students_data as $sid => &$student_data) {
                    $totalsubjects = 0;
                    $total = 0;
                    foreach ($student_data['marks'] as $mark) {
                        if ($mark !== '') {
                            $totalsubjects++;
                            $total += $mark;
                        }
                    }

                    $student_data['totalsubjects'] = $totalsubjects;
                    $student_data['totalmarksobtainable'] = $totalsubjects * 100;
                    $student_data['total'] = $total;
                    $student_data['average'] = $totalsubjects > 0 ? $total / $totalsubjects : 0;
                }
                unset($student_data); // Break the reference with the last element

                // Sort students by average in descending order
                uasort($students_data, function($a, $b) {
                    return $b['average'] <=> $a['average'];
                });

                // Assign positions based on sorted averages
                $position = 1;
                foreach ($students_data as &$student_data) {
                    $student_data['position'] = $position++;
                }
                unset($student_data); // Break the reference with the last element

                // Process arm students similarly (skipped for brevity)
            }
        }
    }
    $_SESSION['results'] = [
        'class' => $class,
        'session' =>$sessionvalue,
        'term' =>$termvalue
    ];
    header('Location: broad.php'); // Redirect after processing
    exit; // Ensure no further code is executed
}
?>
