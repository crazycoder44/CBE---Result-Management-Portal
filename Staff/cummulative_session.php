<?php
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
                        'sid' => $sid, // Store student ID
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
                            $students_data[$sid]['marks'][$subject_id] = '-';
                        }
                    }
                }
                // Fetch the status for the student
                $statusQuery = mysqli_query($con, "
                    SELECT status 
                    FROM comments 
                    WHERE sid = '$sid' 
                    AND term = '$termvalue' 
                    AND session = '$sessionvalue'
                ");
                if ($status_row = mysqli_fetch_assoc($statusQuery)) {
                    $students_data[$sid]['status'] = $status_row['status'];
                } else {
                    $students_data[$sid]['status'] = 'No Status'; // Default value if no status found
                }
            }

            // Calculate totalsubjects, total, and average for each student
            foreach ($students_data as $sid => &$student_data) {
                $totalsubjects = 0;
                $total = 0;
                foreach ($student_data['marks'] as $mark) {
                    if ($mark !== '-' && is_numeric($mark)) {
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

           // Calculate cumulative average and percentage for each student
            foreach ($students_data as $sid => &$student_data) {
                $email = $student_data['email']; // Make sure this field exists in your student_data
                $student_data['marks']['1st_term_total'] = null; // Initialize total scores for each term
                $student_data['marks']['2nd_term_total'] = null;
                $student_data['marks']['3rd_term_total'] = null;
                $student_data['cumulative_avg_total'] = null; // Initialize cumulative average total

                foreach ($subjects as $subject_id => $subject_name) {
                    // 1st Term
                    $query = "
                        SELECT SUM(ca + examobj + examtheory) AS total_mark 
                        FROM results 
                        WHERE email = '$email' 
                        AND session = '$sessionvalue' 
                        AND termid = 1 
                        AND sub_id = '$subject_id'
                    ";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_assoc($result);
                    $ft_mark = $row['total_mark'] !== null ? round($row['total_mark'], 1) : '-'; // Use '-' if no marks are found
                    if ($ft_mark !== '-') {
                        $student_data['marks']['1st_term'][$subject_id] = $ft_mark;
                        $student_data['marks']['1st_term_total'] += $ft_mark;
                    } else {
                        $student_data['marks']['1st_term'][$subject_id] = '-';
                    }

                    // 2nd Term
                    $query = "
                        SELECT SUM(ca + examobj + examtheory) AS total_mark 
                        FROM results 
                        WHERE email = '$email' 
                        AND session = '$sessionvalue' 
                        AND termid = 2 
                        AND sub_id = '$subject_id'
                    ";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_assoc($result);
                    $st_mark = $row['total_mark'] !== null ? round($row['total_mark'], 1) : '-'; // Use '-' if no marks are found
                    if ($st_mark !== '-') {
                        $student_data['marks']['2nd_term'][$subject_id] = $st_mark;
                        $student_data['marks']['2nd_term_total'] += $st_mark;
                    } else {
                        $student_data['marks']['2nd_term'][$subject_id] = '-';
                    }

                    // 3rd Term
                    $query = "
                        SELECT SUM(ca + examobj + examtheory) AS total_mark 
                        FROM results 
                        WHERE email = '$email' 
                        AND session = '$sessionvalue' 
                        AND termid = 3 
                        AND sub_id = '$subject_id'
                    ";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_assoc($result);
                    $tt_mark = $row['total_mark'] !== null ? round($row['total_mark'], 1) : '-'; // Use '-' if no marks are found
                    if ($tt_mark !== '-') {
                        $student_data['marks']['3rd_term'][$subject_id] = $tt_mark;
                        $student_data['marks']['3rd_term_total'] += $tt_mark;
                    } else {
                        $student_data['marks']['3rd_term'][$subject_id] = '-';
                    }

                    // Calculate cumulative average for each subject
                    $first_term_mark = is_numeric($student_data['marks']['1st_term'][$subject_id]) ? $student_data['marks']['1st_term'][$subject_id] : null;
                    $second_term_mark = is_numeric($student_data['marks']['2nd_term'][$subject_id]) ? $student_data['marks']['2nd_term'][$subject_id] : null;
                    $third_term_mark = is_numeric($student_data['marks']['3rd_term'][$subject_id]) ? $student_data['marks']['3rd_term'][$subject_id] : null;

                    // Sum the marks and count the number of valid terms
                    $sum_marks = 0;
                    $valid_terms = 0;

                    if ($first_term_mark !== null) {
                        $sum_marks += $first_term_mark;
                        $valid_terms++;
                    }

                    if ($second_term_mark !== null) {
                        $sum_marks += $second_term_mark;
                        $valid_terms++;
                    }

                    if ($third_term_mark !== null) {
                        $sum_marks += $third_term_mark;
                        $valid_terms++;
                    }

                    // Calculate average based on the number of valid terms
                    $student_data['marks']['avg'][$subject_id] = $valid_terms > 0 ? round($sum_marks / $valid_terms, 1) : 0;
                    $student_data['cumulative_avg_total'] += $student_data['marks']['avg'][$subject_id];

                   
                }

                // Calculate cumulative total and cumulative percentage
                $student_data['cumulative_total'] = $student_data['marks']['1st_term_total'] + $student_data['marks']['2nd_term_total'] + $student_data['marks']['3rd_term_total'];
                $student_data['cumulative_average'] = round($student_data['cumulative_avg_total'], 1);
                $student_data['cumulative_percentage'] = round($student_data['cumulative_average']/$student_data['totalsubjects'], 1);


                // Determine cumulative status
                $student_data['cumulative_status'] = $student_data['cumulative_percentage'] >= 50 ? 'Pass' : 'Fail';
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
                $html .= '<td>' . htmlspecialchars($student_data['cumulative_percentage']) .'%'. '</td>';
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
