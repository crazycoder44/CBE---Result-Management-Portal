<?php
set_time_limit(3600);
session_start();
include('includes/dbConnection.php');
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
if (isset($_POST['submit'])) {

    // Get and sanitize input values
    $classid = htmlspecialchars($_POST['classid']);
    $sessionvalue = htmlspecialchars($_POST['session']);
    $termvalue = htmlspecialchars($_POST['termid']);
    $baseclassid = substr($classid, 0, -1);
    $class = $baseclassid;

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

            while ($student_row = mysqli_fetch_assoc($arm_student_query)) {
                $sid = $student_row['sid'];
                $fname = $student_row['fname'];
                $lname = $student_row['lname'];
                $sub_id = $student_row['sub_id'];
                $email = $student_row['email'];

                // Check if student already exists in array
                if (!isset($arm_students_data[$sid])) {
                    $arm_students_data[$sid] = [
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
                        $arm_students_data[$sid]['marks'][$subject_id] = $totalscore;
                    } else {
                        // Initialize as empty if no marks found
                        if (!isset($arm_students_data[$sid]['marks'][$subject_id])) {
                            $arm_students_data[$sid]['marks'][$subject_id] = '';
                        }
                    }
                }
            }

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
                $student_data['Result_status'] = $student_data['average'] >= 50 ? 'Pass' : 'Fail';
            }
            unset($student_data); // Break the reference with the last element

            // Calculate totalsubjects, total, and average for each student in arm_students_data
            foreach ($arm_students_data as $sid => &$arm_student_data) {
                $totalsubjects = 0;
                $total = 0;
                foreach ($arm_student_data['marks'] as $mark) {
                    if ($mark !== '') {
                        $totalsubjects++;
                        $total += $mark;
                    }
                }

                $arm_student_data['totalsubjects'] = $totalsubjects;
                $arm_student_data['totalmarksobtainable'] = $totalsubjects * 100;
                $arm_student_data['total'] = $total;
                $arm_student_data['average'] = $totalsubjects > 0 ? $total / $totalsubjects : 0;
            }
            unset($arm_student_data); // Break the reference with the last element

            // Sort arm_students_data by average in descending order
            uasort($arm_students_data, function($a, $b) {
                return $b['average'] <=> $a['average'];
            });

            // Assign positions based on sorted averages in arm_students_data
            $position = 1;
            foreach ($arm_students_data as &$arm_student_data) {
                $arm_student_data['position'] = $position++;
                $arm_student_data['Result_status'] = $arm_student_data['average'] >= 50 ? 'Pass' : 'Fail';
            }
            unset($arm_student_data); // Break the reference with the last element

            // Display Class Broadsheet table within a responsive div
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true); // Enables PHP code in the HTML
            $options->set('isJavascriptEnabled', true); // Enables JavaScript (not always necessary for static content)
            $options->set('isFontSubsettingEnabled', true);
            $options->set('isFontEmbedded', true);
            $dompdf = new Dompdf($options);
            // Initialize HTML
            $css = '    <style>
                                        /* General reset and styles */
                            body {
                                font-family: Arial, sans-serif;
                                margin: 0;
                                padding: 0;
                            }

                            .panel.title {
                                margin: 20px;
                                padding: 10px;
                                border: 1px solid #ccc;
                                background-color: #f9f9f9;
                            }

                            /* Table styles */
                            .table-responsive {
                                overflow-x: auto;
                            }

                            .table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-bottom: 20px;
                            }

                            .table th,
                            .table td {
                                border: 1px solid #ccc;
                                padding: 8px;
                                text-align: center;
                                vertical-align: middle;
                            }

                            .table th {
                                background-color: #f2f2f2;
                                font-size: 14px;
                                font-weight: bold;
                            }

                            .table td {
                                font-size: 12px;
                            }

                            /* Header styling */
                            h3, p {
                                margin: 0;
                                padding: 5px;
                            }

                            h3 {
                                font-size: 24px;
                            }

                            p {
                                font-size: 16px;
                            }

                            /* Sticky column styles */
                            .table th.sticky,
                            .table td.sticky {
                                position: -webkit-sticky; /* For Safari */
                                position: sticky;
                                left: 0;
                                background: white;
                                z-index: 1;
                            }

                            /* Image styling */
                            img {
                                display: block;
                                margin: 0 auto;
                                max-width: 100px;
                                height: auto;
                            }

                            /* Media query for A1 size paper */
                            @media print {
                                @page {
                                    size: A1 landscape;
                                    margin: 2cm;
                                }

                                /* Ensure the table fits within A1 dimensions */
                                .table {
                                    font-size: 10px;
                                }

                                .table th,
                                .table td {
                                    padding: 5px;
                                }

                                /* Reduce the header sizes for print */
                                h3 {
                                    font-size: 20px;
                                }

                                p {
                                    font-size: 14px;
                                }

                                /* Remove margins and padding for print */
                                body {
                                    margin: 0;
                                    padding: 0;
                                }

                                .panel.title {
                                    margin: 0;
                                    padding: 0;
                                }
                            }

                        </style>'; 
            $html = $css. '<div class="panel title" id="class">';
            $html .= '<img src="uploads/logo.jpg" alt="Dominican Logo" style="width: 100px; height: auto; display: block; margin: 0 auto;">';
            $html .= "<h3 style='text-align: center; color: navy;'>DOMINICAN COLLEGE MAFOLUKU</h3>";
            $html .= "<p style='text-align: center; color: navy;'>BROADSHEET REPORT FOR $sessionvalue SESSION | TERM: $termname | CLASS: $class</p>";
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr style="color: navy;">';
            $html .= '<th style="position: sticky; left: 0; background: white; z-index: 1;">S/N</th>';
            $html .= '<th style="position: sticky; left: 40px; background: white; z-index: 1;">Admission No</th>';
            $html .= '<th style="position: sticky; left: 140px; background: white; z-index: 1;">Name of Students</th>';

            // Display columns for each subject
            foreach ($subjects as $sub_id => $subject_name) {
                $html .= "<th>$subject_name</th>";
            }

            $html .= '<th>Total No. of Subjects</th>';
            $html .= '<th>Total Marks Obtainable</th>';
            $html .= '<th>Total</th>';
            $html .= '<th>Average</th>';
            $html .= '<th>Percentage</th>';
            $html .= '<th>Position</th>';
            $html .= '<th>Result Status</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $serial_number = 1;
            foreach ($students_data as $sid => $student_data) {
                $fname = $student_data['fname'];
                $lname = $student_data['lname'];

                // Output the student details and marks in one row
                $html .= "<tr style='color: navy;'>";
                $html .= "<td style='position: sticky; left: 0; background: white; z-index: 1;'>$serial_number</td>";
                $html .= "<td style='position: sticky; left: 40px; background: white; z-index: 1;'>$sid</td>";
                $html .= "<td style='position: sticky; left: 140px; background: white; z-index: 1;'>$fname $lname</td>";

                // Output marks for each subject
                foreach ($subjects as $subject_id => $subject_name) {
                    $mark = $student_data['marks'][$subject_id];
                    $mark_display = $mark !== '' ? $mark : '';
                    $mark_color = $mark !== '' && $mark < 50 ? 'style="color: red;"' : '';
                    $html .= "<td $mark_color>$mark_display</td>";
                }

                $html .= '<td>' . $student_data["totalsubjects"] . '</td>';
                $html .= '<td>' . $student_data["totalmarksobtainable"] . '</td>';
                $html .= '<td>' . $student_data["total"] . '</td>';
                $html .= '<td>' . number_format($student_data['average'], 1) . '</td>';
                $html .= '<td>' . number_format($student_data['average'], 0) . '%</td>';
                $html .= '<td>' . $student_data["position"] . '</td>';
                $html .= '<td>'.($student_data['Result_status'] === 'Pass' ? 'pass' : 'fail').'</td>';
                $html .= '</tr>';
                $serial_number++;
            }

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>'; // Close table-responsive div
            $html .= '</div>'; // Close panel div

            // Repeating similar structure for the arm broadsheet
            $html .= '<div class="panel title" style="margin-top: 20px;" id="arm">';
            $html .= '<img src="uploads/logo.jpg" alt="Dominican Logo" style="width: 100px; height: auto; display: block; margin: 0 auto;">';
            $html .= "<h3 style='text-align: center; color: navy;'>DOMINICAN COLLEGE MAFOLUKU</h3>";
            $html .= "<p style='text-align: center; color: navy;'>BROADSHEET REPORT FOR $sessionvalue SESSION | TERM: $termname | CLASS: $classid</p>";
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr style="color: navy;">';
            $html .= '<th style="position: sticky; left: 0; background: white; z-index: 1;">S/N</th>';
            $html .= '<th style="position: sticky; left: 40px; background: white; z-index: 1;">Admission No</th>';
            $html .= '<th style="position: sticky; left: 140px; background: white; z-index: 1;">Name of Students</th>';

            // Display columns for each subject
            foreach ($subjects as $sub_id => $subject_name) {
                $html .= "<th>$subject_name</th>";
            }

            $html .= '<th>Total No. of Subjects</th>';
            $html .= '<th>Total Marks Obtainable</th>';
            $html .= '<th>Total</th>';
            $html .= '<th>Average</th>';
            $html .= '<th>Percentage</th>';
            $html .= '<th>Position</th>';
            $html .= '<th>Result Status</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $serial_number = 1;
            foreach ($arm_students_data as $sid => $arm_student_data) {
                $fname = $arm_student_data['fname'];
                $lname = $arm_student_data['lname'];

                // Output the student details and marks in one row
                $html .= "<tr style='color: navy;'>";
                $html .= "<td style='position: sticky; left: 0; background: white; z-index: 1;'>$serial_number</td>";
                $html .= "<td style='position: sticky; left: 40px; background: white; z-index: 1;'>$sid</td>";
                $html .= "<td style='position: sticky; left: 140px; background: white; z-index: 1;'>$fname $lname</td>";

                // Output marks for each subject
                foreach ($subjects as $subject_id => $subject_name) {
                    $mark = $arm_student_data['marks'][$subject_id];
                    $mark_display = $mark !== '' ? $mark : '';
                    $mark_color = $mark !== '' && $mark < 50 ? 'style="color: red;"' : '';
                    $html .= "<td $mark_color>$mark_display</td>";
                }

                $html .= '<td>' . $arm_student_data["totalsubjects"] . '</td>';
                $html .= '<td>' . $arm_student_data["totalmarksobtainable"] . '</td>';
                $html .= '<td>' . $arm_student_data["total"] . '</td>';
                $html .= '<td>' . number_format($arm_student_data['average'], 1) . '</td>';
                $html .= '<td>' . number_format($arm_student_data['average'], 0) . '%</td>';
                $html .= '<td>' . $arm_student_data["position"] . '</td>';
                $html .= '<td>'. ($arm_student_data['Result_status'] === 'Pass' ? 'pass' : 'fail') .'</td>';
                $html .= '</tr>';
                $serial_number++;
            }

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>'; // Close table-responsive div
            $html .= '</div>'; // Close panel div

            // Fetch term scores and store in $students_data
            foreach ($students_data as $sid => &$student_data) {
                $email = $student_data['email']; // Make sure this field exists in your student_data
                $student_data['marks']['1st_term_total'] = null; // Initialize total scores for each term
                $student_data['marks']['2nd_term_total'] = null;
                $student_data['marks']['3rd_term_total'] = null;

                foreach ($subjects as $subject_id => $subject_name) {
                    // 1st Term
                    $query = "SELECT SUM(ca + examobj + examtheory) AS total_mark 
                            FROM results 
                            WHERE email = '$email' 
                            AND session = '$sessionvalue' 
                            AND termid = 1 
                            AND sub_id = '$subject_id'";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_assoc($result);
                    $ft_mark = $row['total_mark'] !== null ? round($row['total_mark'], 1) : '-'; // Check for null and format
                    if ($ft_mark !== '-') {
                        $student_data['marks']['1st_term'][$subject_id] = $ft_mark;
                        $student_data['marks']['1st_term_total'] += $ft_mark;
                    } else {
                        $student_data['marks']['1st_term'][$subject_id] = '-';
                    }

                    // 2nd Term
                    $query = "SELECT SUM(ca + examobj + examtheory) AS total_mark 
                            FROM results 
                            WHERE email = '$email' 
                            AND session = '$sessionvalue' 
                            AND termid = 2 
                            AND sub_id = '$subject_id'";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_assoc($result);
                    $st_mark = $row['total_mark'] !== null ? round($row['total_mark'], 1) : '-'; // Check for null and format
                    if ($st_mark !== '-') {
                        $student_data['marks']['2nd_term'][$subject_id] = $st_mark;
                        $student_data['marks']['2nd_term_total'] += $st_mark;
                    } else {
                        $student_data['marks']['2nd_term'][$subject_id] = '-';
                    }

                    // 3rd Term
                    $query = "SELECT SUM(ca + examobj + examtheory) AS total_mark 
                            FROM results 
                            WHERE email = '$email' 
                            AND session = '$sessionvalue' 
                            AND termid = 3 
                            AND sub_id = '$subject_id'";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_assoc($result);
                    $tt_mark = $row['total_mark'] !== null ? round($row['total_mark'], 1) : '-'; // Check for null and format

                    // Store marks for each term
                    $student_data['marks']['1st_term'][$subject_id] = $ft_mark;
                    $student_data['marks']['2nd_term'][$subject_id] = $st_mark;
                    $student_data['marks']['3rd_term'][$subject_id] = $tt_mark;

                    // Calculate total for each term
                    $student_data['marks']['1st_term_total'] += is_numeric($ft_mark) ? $ft_mark : 0;
                    $student_data['marks']['2nd_term_total'] += is_numeric($st_mark) ? $st_mark : 0;
                    $student_data['marks']['3rd_term_total'] += is_numeric($tt_mark) ? $tt_mark : 0;
                }
            }
                    }
                }
            

            // Load HTML into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A0', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF (stream to browser)
$dompdf->stream("broadsheet_report_$sessionvalue.pdf", array("Attachment" => 0));

?>