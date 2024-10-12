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
    $termQuery = mysqli_query($con, "SELECT term FROM terms WHERE termid = '$termvalue'");
    $termname = (mysqli_num_rows($termQuery) > 0) ? mysqli_fetch_assoc($termQuery)['term'] : 'Unknown Term';

    // Fetch student marks for a particular subject
    function fetch_student_marks($con, $email, $subject_id, $termvalue, $sessionvalue) {
        $result_query = mysqli_query($con, "
            SELECT ca, examobj, examtheory
            FROM results
            WHERE email = '$email' AND sub_id = '$subject_id' AND termid = '$termvalue' AND session = '$sessionvalue'
        ");
        
        if ($result_row = mysqli_fetch_assoc($result_query)) {
            return number_format(array_sum($result_row), 1); // Sum and format scores
        } 
        return ''; // Return empty if no marks found
    }

    // Calculate totals and averages for students
    function calculate_totals($student_data) {
        $marks = $student_data['marks'];
        $totalsubjects = count(array_filter($marks));
        $total = array_sum(array_filter($marks));
        
        return [
            'totalsubjects' => $totalsubjects,
            'totalmarksobtainable' => $totalsubjects * 100,
            'total' => $total,
            'average' => $totalsubjects > 0 ? $total / $totalsubjects : 0,
            // 'Result_status' => ($totalsubjects > 0 && $total / $totalsubjects >= 50) ? 'Pass' : 'Fail'
        ];
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
            $subjects[$row['sub_id']] = $row['subject'];
        }

        // Fetch students based on selected term and session
        $student_query = mysqli_query($con, "
            SELECT s.email, s.sid, s.fname, s.lname 
            FROM students s
            WHERE s.sid IN (
                SELECT DISTINCT s.sid
                FROM students s
                INNER JOIN results r ON s.email = r.email
                WHERE r.classid LIKE '$class%'
                AND r.session = '$sessionvalue'
                AND r.termid = '$termvalue'
            )
        ");

        // Initialize students_data array
        $students_data = [];
        while ($student_row = mysqli_fetch_assoc($student_query)) {
            $sid = $student_row['sid'];
            $students_data[$sid] = array_merge($student_row, ['marks' => []]); // Combine student info and initialize marks
            
            // Fetch marks for all subjects
            foreach ($subjects as $sub_id => $subject_name) {
                $students_data[$sid]['marks'][$sub_id] = fetch_student_marks($con, $student_row['email'], $sub_id, $termvalue, $sessionvalue);
            }

            // Calculate totals for each student
            $students_data[$sid] = array_merge($students_data[$sid], calculate_totals($students_data[$sid]));
        }

        // Sort students by average in descending order
        uasort($students_data, fn($a, $b) => $b['average'] <=> $a['average']);

        // Assign positions based on sorted averages
        $position = 1;
        foreach ($students_data as &$student_data) {
            $student_data['position'] = $position++;
        }

        // Generate HTML for the broadsheet
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isJavascriptEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        $options->set('isFontEmbedded', true);
        
        $dompdf = new Dompdf($options);
        
        // Initialize HTML
        $css = '<style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
            .panel.title { margin: 20px; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9; }
            .table-responsive { overflow-x: auto; }
            .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .table th, .table td { border: 1px solid #ccc; padding: 8px; text-align: center; vertical-align: middle; }
            .table th { background-color: #f2f2f2; font-size: 14px; font-weight: bold; }
            .table td { font-size: 12px; }
            h3, p { margin: 0; padding: 5px; }
            h3 { font-size: 24px; }
            p { font-size: 16px; }
            .table th.sticky, .table td.sticky { position: sticky; left: 0; background: white; z-index: 1; }
            img { display: block; margin: 0 auto; max-width: 100px; height: auto; }
            @media print {
                @page { size: A1 landscape; margin: 2cm; }
                .table { font-size: 10px; }
                h3 { font-size: 20px; }
                p { font-size: 14px; }
                body { margin: 0; padding: 0; }
                .panel.title { margin: 0; padding: 0; }
            }
        </style>';
        
        $html = $css . '<div class="panel title" id="class">';
        // $html .= '<img src="uploads/logo.jpg" alt="Sample Logo" style="width: 100px; height: auto;">';
        $html .= "<h3 style='text-align: center; color: navy;'>SAMPLE COLLEGE MAFOLUKU</h3>";
        $html .= "<p style='text-align: center; color: navy;'>BROADSHEET REPORT FOR $sessionvalue SESSION | TERM: $termname | CLASS: $class</p>";
        $html .= '<div class="table-responsive"><table class="table"><thead><tr style="color: navy;">';
        $html .= '<th class="sticky">S/N</th><th class="sticky">Admission No</th><th class="sticky">Name of Students</th>';
        
        // Display columns for each subject
        foreach ($subjects as $subject_name) {
            $html .= "<th>$subject_name</th>";
        }

        $html .= '<th>Total No. of Subjects</th><th>Total Marks Obtainable</th><th>Total</th><th>Average</th><th>Percentage</th><th>Position</th><th>Result Status</th></tr></thead><tbody>';

        $serial_number = 1;
        foreach ($students_data as $student_data) {
            $html .= "<tr style='color: navy;'><td class='sticky'>$serial_number</td>";
            $html .= "<td class='sticky'>{$student_data['sid']}</td>";
            $html .= "<td class='sticky'>{$student_data['fname']} {$student_data['lname']}</td>";

            // Output marks for each subject
            foreach ($subjects as $sub_id => $subject_name) {
                $mark = $student_data['marks'][$sub_id];
                $mark_display = $mark !== '' ? $mark : '';
                $mark_color = $mark < 50 ? 'style="color: red;"' : '';
                $html .= "<td $mark_color>$mark_display</td>";
            }

            $html .= '<td>' . $student_data['totalsubjects'] . '</td>';
            $html .= '<td>' . $student_data['totalmarksobtainable'] . '</td>';
            $html .= '<td>' . $student_data['total'] . '</td>';
            $html .= '<td>' . number_format($student_data['average'], 1) . '</td>';
            $html .= '<td>' . number_format($student_data['average'], 1) . '%</td>';
            $html .= '<td>' . $student_data['position'] . '</td>';
            $html .= '<td></td></tr>';

            $serial_number++;
        }

        $html .= '</tbody></table></div></div>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A1', 'landscape');
        $dompdf->render();
        $dompdf->stream("Broadsheet_Report_{$class}_{$sessionvalue}_{$termname}.pdf", array("Attachment" => false));
}
?>