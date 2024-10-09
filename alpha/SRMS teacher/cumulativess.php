<?php
set_time_limit(3600);
include_once 'dbConnection.php';
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);
session_start();
if (isset($_SESSION['results'])) {
    $results = $_SESSION['results'];

    $sessionvalue = htmlspecialchars($results['session']);
    $termvalue = htmlspecialchars($results['term']);
    $class = htmlspecialchars($results['class']);
    $staffid = htmlspecialchars($results['staffid']);

    // Check if the staff is a class guardian and fetch associated classid
    $classQuery = mysqli_query($con, "SELECT DISTINCT classid FROM class WHERE staffid = '$staffid'");

    // Fetch the term name
    $termQuery = mysqli_query($con, "SELECT term FROM terms WHERE termid = '$termvalue'");

    if (mysqli_num_rows($termQuery) > 0) {
        $termRow = mysqli_fetch_assoc($termQuery);
        $termname = $termRow['term'];
    } else {
        $termname = 'Unknown Term';
    }

    function fetchSubjectMarks($subject_id, $emails, $termvalue, $sessionvalue, $con) {
        // Prepare the placeholders for the IN clause
        $placeholders = rtrim(str_repeat('?,', count($emails)), ','); // Create a string of placeholders
        // Prepared statement to fetch marks for a specific subject, multiple emails, term, and session
        $stmt = $con->prepare("
            SELECT email, ca, examobj, examtheory
            FROM results
            WHERE email IN ($placeholders) AND sub_id = ? AND termid = ? AND session = ?
        ");
    
        // Create an array for binding parameters
        $params = [...$emails, $subject_id, $termvalue, $sessionvalue];
        
        // Determine types for each parameter
        $types = str_repeat('s', count($emails)) . 'iis'; // Email parameters are strings, subject_id and termid are integers, session is a string        // Bind parameters
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $marks = []; // Initialize an array to hold the results
        
        while ($result_row = $result->fetch_assoc()) {
            $email = $result_row['email'];
            $ca = $result_row['ca'] ?? 0; // Default to 0 if not set
            $examobj = $result_row['examobj'] ?? 0; // Default to 0 if not set
            $examtheory = $result_row['examtheory'] ?? 0; // Default to 0 if not set
            $totalscore = $ca + $examobj + $examtheory;
    
            // Store the results in the marks array
            $marks[$email] = number_format($totalscore, 1); // Format the total score to one decimal place
        }
    
        // Return the marks array or a dash if no marks were found for any student
        return empty($marks) ? '-' : $marks;
    }    
    
    function calculateTotalsAndAverage($student_data) {
        $total = 0;
        $subjects_with_marks = [];
    
        // Initialize total obtainable marks per term
        $total_marks_obtainable_per_term = 100; // Assuming each subject is out of 100
        $total_terms = 3; // Assuming we are fetching marks for 3 terms
    
        // Iterate through each term's marks for the student
        foreach (['1st_term', '2nd_term', '3rd_term'] as $term) {
            // Check if term marks exist for the student
            if (isset($student_data['marks'][$term])) {
                foreach ($student_data['marks'][$term] as $subject_id => $mark) {
                    // If a mark exists and is numeric, include it in the totals
                    if ($mark !== '-' && is_numeric($mark)) {
                        $total += $mark;
                        // Track subjects that have valid marks in any term
                        if (!in_array($subject_id, $subjects_with_marks)) {
                            $subjects_with_marks[] = $subject_id;
                        }
                    }
                }
            }
        }
    
        // The total subjects is the count of unique subjects with marks
        $totalsubjects = count($subjects_with_marks);
    
        // Calculate the total obtainable marks for all subjects across all terms
        $student_data['totalsubjects'] = $totalsubjects;
        $student_data['totalmarksobtainable'] = $totalsubjects * $total_marks_obtainable_per_term;
        $student_data['total'] = $total;
    
        // Calculate the average based on the total subjects
        $student_data['average'] = $totalsubjects > 0 ? round($total / ($totalsubjects * $total_terms), 1) : 0;
    
        return $student_data;
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
            LEFT JOIN results r ON s.email = r.email
            AND r.session = '$sessionvalue'
            AND r.termid = '$termvalue'
            WHERE s.sid IN (
                SELECT DISTINCT s.sid
                FROM students s
                INNER JOIN results r ON s.email = r.email
                WHERE r.classid LIKE '$class%'
                AND r.session = '$sessionvalue'
                AND r.termid = '$termvalue'
            )
        ");

        if (mysqli_num_rows($student_query) > 0) {
            // Organize students and their marks into an associative array
            $students_data = [];

            // Fetch all results for the batch of students (by email, session, and subject) in one query
            $results_query = mysqli_query($con, "
            SELECT email, sub_id, termid, SUM(ca + examobj + examtheory) AS total_mark
            FROM results
            WHERE session = '$sessionvalue'
            AND termid IN (1, 2, 3) -- Fetch for all terms
            AND sub_id IN (" . implode(',', array_keys($subjects)) . ")
            GROUP BY email, sub_id, termid
            ");

            // Organize fetched results into an array (by student email, subject ID, and term)
            $results_data = [];
            while ($result_row = mysqli_fetch_assoc($results_query)) {
                $results_data[$result_row['email']][$result_row['sub_id']][$result_row['termid']] = round($result_row['total_mark'], 1);
            }

            function calculateTermMarksAndAverage($subject_id, $subject_name, $email, $results_data, &$student_data) {
                // Define an array of term ids
                $terms = [1 => '1st_term', 2 => '2nd_term', 3 => '3rd_term'];
                $total_avg = 0;
                $valid_terms = 0;
            
                // Loop through each term and fetch the marks from batch-fetched results
                foreach ($terms as $term_id => $term_name) {
                    $term_mark = $results_data[$email][$subject_id][$term_id] ?? '-';
            
                    // Store the term mark and calculate term totals
                    if ($term_mark !== '-') {
                        $student_data['marks'][$term_name][$subject_id] = $term_mark;
                        $student_data['marks'][$term_name . '_total'] += $term_mark;
                        $total_avg += $term_mark;
                        $valid_terms++;
                    } else {
                        $student_data['marks'][$term_name][$subject_id] = '-';
                    }
                }
            
                // Calculate cumulative average for each subject
                $student_data['marks']['avg'][$subject_id] = $valid_terms > 0 ? round($total_avg / $valid_terms, 1) : 0;
                $student_data['cumulative_avg_total'] += $student_data['marks']['avg'][$subject_id];
            
                return $student_data;
            }
            
            // Function to calculate cumulative totals for a student
            function calculateStudentCumulative($student_data, $subjects, $results_data) {
                $email = $student_data['email'];
                $student_data['marks']['1st_term_total'] = 0; // Initialize total scores for each term
                $student_data['marks']['2nd_term_total'] = 0;
                $student_data['marks']['3rd_term_total'] = 0;
                $student_data['cumulative_avg_total'] = 0; // Initialize cumulative average total

                // Iterate over the subjects and apply the function to each subject
                foreach ($subjects as $subject_id => $subject_name) {
                    $student_data = calculateTermMarksAndAverage($subject_id, $subject_name, $email, $results_data, $student_data);
                }

                // Calculate cumulative total and cumulative percentage
                $student_data['cumulative_total'] = $student_data['marks']['1st_term_total'] + $student_data['marks']['2nd_term_total'] + $student_data['marks']['3rd_term_total'];
                $student_data['cumulative_average'] = round($student_data['cumulative_avg_total'], 1);
                $student_data['cumulative_percentage'] = $student_data['totalsubjects'] > 0 ? round($student_data['cumulative_average'] / $student_data['totalsubjects'], 1) : 0;

                // Determine cumulative status
                $student_data['cumulative_status'] = $student_data['cumulative_percentage'] >= 50 ? 'Pass' : 'Fail';

                return $student_data;
            }

            while ($student_row = mysqli_fetch_assoc($student_query)) {
                $sid = $student_row['sid'];
                $fname = $student_row['fname'];
                $lname = $student_row['lname'];
                $email = $student_row['email'];
            
                // Initialize student data if not already set
                if (!isset($students_data[$sid])) {
                    $students_data[$sid] = [
                        'sid' => $sid,
                        'fname' => $fname,
                        'lname' => $lname,
                        'email' => $email,
                        'marks' => [],
                        'totalsubjects' => count($subjects), // Total number of subjects
                    ];
                }
            
                // Calculate cumulative data for the student
                $students_data[$sid] = calculateStudentCumulative($students_data[$sid], $subjects, $results_data);
            }

            // Fetch and store the status for each student
            foreach ($students_data as $sid => &$student_data) {
                $email = $student_data['email'];
                $status_query = mysqli_query($con, "
                    SELECT status 
                    FROM comments 
                    WHERE sid = '$sid'
                    AND term = '$termvalue'
                    AND session = '$sessionvalue'
                ");

                if ($status_row = mysqli_fetch_assoc($status_query)) {
                    $student_data['status'] = $status_row['status'];
                } else {
                    $student_data['status'] = 'No Status'; // Default status if not found
                }
            }

            unset($student_data); // Break the reference with the last element

            // Map the calculateTotalsAndAverage function to the students_data array
            $students_data = array_map('calculateTotalsAndAverage', $students_data);

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

            // Sort students by cumulative average in descending order
            uasort($students_data, function($a, $b) {
                return $b['cumulative_average'] <=> $a['cumulative_average'];
            });

            // Assign cumulative positions based on sorted averages
            $cumulative_position = 1;
            foreach ($students_data as &$student_data) {
                $student_data['cumulative_position'] = $cumulative_position++;
            }
            unset($student_data); // Break the reference with the last element
            
            // Generate HTML for PDF


            
            $html = '<html><head>';
            $html .= '<style>
                        @page { margin: 50px 20px 20px 20px; }
                        body { font-family: Arial, sans-serif; font-size: 10px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid black; padding: 5px; text-align: center; }
                        th { background-color: white; }
                        h1 { text-align: center; font-size: 25px; }
                        h2 { text-align: center; font-size: 16px; }
                        .pass { background-color: white; color: green; }
                        .fail { background-color: white; color: red; }
                        .zero { background-color: white; color: red; }
                      </style>';
            $html .= '</head><body>';

            $html .= '<h1><b>DOMINICAN COLLEGE MAFOLUKU BROADSHEET</b></h1>';
            $html .= '<h2><i>Class: ' . $class . ' ' . '   Session: ' . $sessionvalue .'   Term: ' . $termname . '</i></h2>';

            $html .= '<table>';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th rowspan="2">S/N</th>'; // Serial Number
            $html .= '<th rowspan="2">Admission Number</th>'; // Admission Number
            $html .= '<th rowspan="2">Student Name</th>';

            foreach ($subjects as $subject_name) {
                $html .= '<th colspan="4">' . htmlspecialchars($subject_name) . '</th>';
            }

            $html .= '<th rowspan="2">Total Subjects</th>';
            $html .= '<th rowspan="2">Total AVG Obtainable</th>';
           // $html .= '<th rowspan="2">Total Marks Obtained</th>';
           // $html .= '<th rowspan="2">Average</th>';
           // $html .= '<th rowspan="2">Position</th>';
            $html .= '<th rowspan="2">1st Term Total</th>';
            $html .= '<th rowspan="2">2nd Term Total</th>';
            $html .= '<th rowspan="2">3rd Term Total</th>';
            $html .= '<th rowspan="2">Cumulative Total</th>';
            $html .= '<th rowspan="2">Cumulative Average</th>';
            $html .= '<th rowspan="2">Cumulative Percentage</th>';
            $html .= '<th rowspan="2">Cumulative Position</th>';
            $html .= '<th rowspan="2">Cumulative Status</th>';
            $html .= '</tr>';

            $html .= '<tr>';
            foreach ($subjects as $subject_name) {
                $html .= '<th>F.T</th>';
                $html .= '<th>S.T</th>';
                $html .= '<th>T.T</th>';
                $html .= '<th>AVG</th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $serial_number = 1; // Initialize serial number
            foreach ($students_data as $student_data) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($serial_number++) . '</td>'; // Display serial number
                $html .= '<td>' . htmlspecialchars($student_data['sid']) . '</td>'; // Display admission number
                $html .= '<td>' . htmlspecialchars($student_data['fname'] . ' ' . $student_data['lname']) . '</td>';

                foreach ($subjects as $subject_id => $subject_name) {
                    $ft = htmlspecialchars($student_data['marks']['1st_term'][$subject_id] ?? '-');
                    $st = htmlspecialchars($student_data['marks']['2nd_term'][$subject_id] ?? '-');
                    $tt = htmlspecialchars($student_data['marks']['3rd_term'][$subject_id] ?? '-');
                    $avg = htmlspecialchars($student_data['marks']['avg'][$subject_id] ?? '-');

                    $ft_style = is_numeric($ft) && $ft < 60 ? 'style="color: red;"' : '';
                    $st_style = is_numeric($st) && $st < 60 ? 'style="color: red;"' : '';
                    $tt_style = is_numeric($tt) && $tt < 60 ? 'style="color: red;"' : '';
                    $avg_class = is_numeric($avg) && $avg > 0 && $avg < 60 ? 'style="color: red;"' : '';

                    $html .= "<td $ft_style>$ft</td>";
                    $html .= "<td $st_style>$st</td>";
                    $html .= "<td $tt_style>$tt</td>";
                    $html .= "<td $avg_class><b>$avg</b></td>";
                }

                $html .= '<td>' . htmlspecialchars($student_data['totalsubjects']) . '</td>';
                $html .= '<td>' . htmlspecialchars($student_data['totalmarksobtainable']) . '</td>';
               // $html .= '<td>' . htmlspecialchars($student_data['total']) . '</td>';
               // $html .= '<td>' . htmlspecialchars(number_format($student_data['average'], 1)) . '</td>';
               // $html .= '<td>' . htmlspecialchars($student_data['position']) . '</td>';
                $html .= '<td>' . htmlspecialchars($student_data['marks']['1st_term_total']) . '</td>';
                $html .= '<td>' . htmlspecialchars($student_data['marks']['2nd_term_total']) . '</td>';
                $html .= '<td>' . htmlspecialchars($student_data['marks']['3rd_term_total']) . '</td>';
                $html .= '<td>' . htmlspecialchars($student_data['cumulative_total']) . '</td>';
                $html .= '<td>' . htmlspecialchars($student_data['cumulative_average']) . '</td>';
                $html .= '<td></td>';
                $html .= '<td>' . htmlspecialchars($student_data['cumulative_position']). '</td>';
                $html .= '<td>' . htmlspecialchars($student_data['status']). '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</body></html>';

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A0', 'landscape');
            $dompdf->render();
            $dompdf->stream('broadsheet.pdf', ['Attachment' => 0]);
        } else {
            echo 'No students found for the selected class and term.';
        }
    } else {
        echo 'You are not a class guardian for the selected class.';
    }
} else {
    echo 'No results data available.';
}
?>
