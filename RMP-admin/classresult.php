<?php
// Start the session
session_start();

// Check if the session variable exists
if(isset($_SESSION['results'])) {
    // Retrieve the session data
    $results = $_SESSION['results'];

    $studentname = htmlspecialchars($results['studentname']);
    $sid = htmlspecialchars($results['sid']);
    $baseclassid = htmlspecialchars($results['baseclassid']);
    $noinclass = htmlspecialchars($results['noinclass']);
    $termname = htmlspecialchars($results['termname']);
    $sessionvalue = htmlspecialchars($results['session']);
    $total_marks_obtainable = htmlspecialchars($results['total_marks_obtainable']);
    $total_marks_obtained = htmlspecialchars($results['total_marks_obtained']);
    $average = htmlspecialchars(round($results['average'], 2));
    $suffixedClassPosition = htmlspecialchars($results['suffixedClassPosition']);
    $ca_marks = $results['ca_marks'];
    $studentcount = $results['studentcount'];
    $status = $results['status'];
    $comments = $results['comments'];
    $criteria = $results['criteria'];
}
else {
    // Handle the case where the session variable does not exist
    echo "No session data found.";
    exit;
}

function generateGradeRemark($marks, $thresholds) {
    usort($thresholds, function($a, $b) {
        return $b['mark'] - $a['mark'];
    });

    foreach ($thresholds as $threshold) {
        if ($marks >= $threshold['mark']) {
            return [
                'grade' => $threshold['grade'],
                'gradeColor' => $threshold['color'],
                'remark' => $threshold['remark'],
                'remarkColor' => $threshold['color']
            ];
        }
    }
    return [
        'grade' => 'F',
        'gradeColor' => '#FF0000',
        'remark' => 'FAIL',
        'remarkColor' => '#FF0000'
    ];
}

// Define thresholds for JSS and SS
$jssThresholds = [
    ['mark' => 80, 'grade' => 'A', 'color' => '#008000', 'remark' => 'DISTINCTION'],
    ['mark' => 70, 'grade' => 'B', 'color' => '#008000', 'remark' => 'VERY GOOD'],
    ['mark' => 60, 'grade' => 'C', 'color' => '#008000', 'remark' => 'CREDIT'],
    ['mark' => 50, 'grade' => 'P', 'color' => '#000000', 'remark' => 'PASS'],
    ['mark' => 0, 'grade' => 'F', 'color' => '#FF0000', 'remark' => 'FAIL']
];

$ssThresholds = [
    ['mark' => 85, 'grade' => 'A1', 'color' => '#008000', 'remark' => 'ALPHA'],
    ['mark' => 80, 'grade' => 'B2', 'color' => '#008000', 'remark' => 'VERY GOOD'],
    ['mark' => 75, 'grade' => 'B3', 'color' => '#008000', 'remark' => 'GOOD'],
    ['mark' => 70, 'grade' => 'C4', 'color' => '#008000', 'remark' => 'CREDIT'],
    ['mark' => 65, 'grade' => 'C5', 'color' => '#008000', 'remark' => 'CREDIT'],
    ['mark' => 60, 'grade' => 'C6', 'color' => '#008000', 'remark' => 'CREDIT'],
    ['mark' => 55, 'grade' => 'D7', 'color' => '#000000', 'remark' => 'PASS'],
    ['mark' => 50, 'grade' => 'E8', 'color' => '#000000', 'remark' => 'PASS'],
    ['mark' => 0, 'grade' => 'F9', 'color' => '#FF0000', 'remark' => 'FAIL']
];

// Determine which thresholds to use
$baseclassid = $_SESSION['results']['baseclassid'] ?? '';
$thresholds = in_array($baseclassid, ['JSS1', 'JSS2', 'JSS3']) ? $jssThresholds : $ssThresholds;

// Check for pass/fail status
$passedSubjects = 0;
$mathPassed = false;
$englishPassed = false;
$govPassed = false;
$comPassed = false;
$chemPassed = false;
$litPassed = false;
$crsPassed = false;
$ecoPassed = false;
$accPassed = false;
$phyPassed = false;
$bioPassed = false;
$tdPassed = false;
$agrPassed = false;

foreach ($ca_marks as $marks) {
    if ($marks['cumavg'] >= 60) {
        $passedSubjects++;
        if (strtolower($marks['subject_name']) === 'mathematics') {
            $mathPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'english language' || strtolower($marks['subject_name']) === 'english studies') {
            $englishPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'government') {
            $govPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'commerce') {
            $comPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'chemistry') {
            $chemPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'literature in english') {
            $litPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'christian religious studies') {
            $crsPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'economics') {
            $ecoPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'accounts') {
            $accPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'physics') {
            $phyPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'biology') {
            $bioPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'technical drawing') {
            $tdPassed = true;
        }
        if (strtolower($marks['subject_name']) === 'agricultural science') {
            $agrPassed = true;
        }
    }
}



if (!isset($status) || empty($status)) {
    if ($baseclassid === 'JSS1') {
        $weakPass = false;
        $jss1PassSubjects = 0;
        foreach ($ca_marks as $marks) {
            if ($marks['cumavg'] >= 60) {
                $jss1PassSubjects++;
            } elseif ($marks['cumavg'] >= 55 && (strtolower($marks['subject_name']) === 'mathematics' || strtolower($marks['subject_name']) === 'english language' || strtolower($marks['subject_name']) === 'english studies')) {
                $weakPass = true;
            }
        }
        if ($average >= 55 && $jss1PassSubjects >= 9) {
            if ($mathPassed && $englishPassed) {
                $status = "PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, and 7 other subjects. 
                Minimum subjects to be offered are 17. 
                Promotion average score is 55, you scored {$average}.";
            } elseif (($mathPassed || $englishPassed) && $weakPass) {
                $status = "WEAK PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, and 7 other subjects. 
                Minimum subjects to be offered are 17. 
                Promotion average score is 55, you scored {$average}.";
            } else {
                $status = "FAILED";
                $criteria = "Compulsory subjects to credit are Mathematics, English, and 7 other subjects. 
                Minimum subjects to be offered are 17. 
                Promotion average score is 55, you scored {$average}.";
            }
        } else {
            $status = "FAILED";
            $criteria = "Compulsory subjects to credit are Mathematics, English, and 7 other subjects. 
            Minimum subjects to be offered are 17. 
            Promotion average score is 55, you scored {$average}.";
        }
    } else if (in_array($baseclassid, ['JSS2', 'JSS3'])) {
        if ($average >= 55 && $passedSubjects >= 9 && $mathPassed && $englishPassed) {
            $status = "PASS";
            $criteria = "Compulsory subjects to credit are Mathematics, English, and 7 other subjects. 
            Minimum subjects to be offered are 17. 
            Promotion average score is 55, you scored {$average}.";
        } else {
            $status = "FAILED";
            $criteria = "Compulsory subjects to credit are Mathematics, English, and 7 other subjects. 
            Minimum subjects to be offered are 17. 
            Promotion average score is 55, you scored {$average}.";
        }
    } else if ($baseclassid === 'SS1') {
        if ($litPassed) {
            if ($passedSubjects >= 6 && $mathPassed && $englishPassed && $govPassed && $litPassed && $crsPassed) {
                $status = "PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Government, Literature, CRS, and 1 other subject. 
                Minimum subjects to be offered are 15. 
                Promotion average score is 60, you scored {$average}.";
            } else {
                $status = "FAILED";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Government, Literature, CRS, and 1 other subject. 
                Minimum subjects to be offered are 15. 
                Promotion average score is 60, you scored {$average}.";
            }
        } elseif ($comPassed) {
            if ($passedSubjects >= 6 && $mathPassed && $englishPassed && $comPassed && $ecoPassed && $accPassed) {
                $status = "PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Commerce, Economics, Accounts, and 1 other subject. 
                Minimum subjects to be offered are 15. 
                Promotion average score is 60, you scored {$average}.";
            } else {
                $status = "FAILED";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Commerce, Economics, Accounts, and 1 other subject. 
                Minimum subjects to be offered are 15. 
                Promotion average score is 60, you scored {$average}.";
            }
        } elseif ($chemPassed) {
            if ($passedSubjects >= 6 && $mathPassed && $englishPassed && $chemPassed && $phyPassed && $bioPassed) {
                $status = "PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Chemistry, Physics, Biology, and 1 other subject. 
                Minimum subjects to be offered are 15. 
                Promotion average score is 60, you scored {$average}.";
            } else {
                $status = "FAILED";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Chemistry, Physics, Biology, and 1 other subject. 
                Minimum subjects to be offered are 15. 
                Promotion average score is 60, you scored {$average}.";
            }
        } else {
            $status = "FAILED";
            $criteria = "Compulsory subjects to credit are Mathematics, English, 3 core subjects, and 1 other subject. 
            Minimum subjects to be offered are 15. 
            Promotion average score is 60, you scored {$average}.";
        }
    } else if (in_array($baseclassid, ['SS2', 'SS3'])) {
        if ($litPassed) {
            if ($passedSubjects >= 6 && $mathPassed && $englishPassed && $govPassed && $litPassed && $crsPassed) {
                $status = "PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Government, Literature, CRS, and 1 other subject. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            } else {
                $status = "FAILED";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Government, Literature, CRS, and 1 other subject. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            }
        } elseif ($comPassed) {
            if ($passedSubjects >= 6 && $mathPassed && $englishPassed && $comPassed && $ecoPassed && $accPassed) {
                $status = "PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Commerce, Economics, Accounts, and 1 other subject. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            } else {
                $status = "FAILED";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Commerce, Economics, Accounts, and 1 other subject. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            }
        } elseif ($tdPassed) {
            if ($passedSubjects >= 6 && $mathPassed && $englishPassed && $chemPassed && $phyPassed && $tdPassed) {
                $status = "PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Chemistry, Physics, Technical Drawing, and 1 other subject. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            } else {
                $status = "FAILED";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Chemistry, Physics, Technical Drawing, and 1 other subject. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            }
        } elseif ($agrPassed) {
            if ($passedSubjects >= 6 && $mathPassed && $englishPassed && $chemPassed && $phyPassed && $agrPassed) {
                $status = "PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Chemistry, Physics, Agricultural Science, and 1 other subject. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            } else {
                $status = "FAILED";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Chemistry, Physics, Agricultural Science, and 1 other subject. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            }
        } elseif ($bioPassed) {
            if ($passedSubjects >= 6 && $mathPassed && $englishPassed && $chemPassed && $phyPassed && $bioPassed) {
                $status = "PASS";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Chemistry, Physics, and Biology. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            } else {
                $status = "FAILED";
                $criteria = "Compulsory subjects to credit are Mathematics, English, Chemistry, Physics, and Biology. 
                Minimum subjects to be offered are 12. 
                Promotion average score is 60, you scored {$average}.";
            }
        } else {
            $status = "FAILED";
            $criteria = "Compulsory subjects to credit are Mathematics, English, 3 core subjects, and 1 other subject. 
            Minimum subjects to be offered are 12. 
            Promotion average score is 60, you scored {$average}.";
        }
    } else {
        $status = "FAILED";
        $criteria = "Compulsory subjects to credit are Mathematics, English, 3 core subjects, and 1 other subject. 
        Minimum subjects to be offered are 12. 
        Promotion average score is 60, you scored {$average}.";
    }
}

// Principal comment generation
function generatePrincipalComment($status, $termname, $average) {
    if ($status === "PASS") {
        $comment = "Congratulations on passing your exams.";
        if ($termname === "Third Term") {
            $comment .= " You are promoted to the next class.";
        }
    } elseif ($status === "WEAK PASS") {
        $comment = "Weak Pass. You can definitely do better than this.";
        if ($termname === "Third Term") {
            $comment .= "Promoted on trial.";
        }
    } else {
        $comment = "You need to work harder, especially in subjects you did not credit.";
        if ($termname === "Third Term") {
            $comment .= " Not promoted.";
        }
    }
    return $comment;
}


$principalComment = generatePrincipalComment($status, $termname, $average);


require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A3', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Terminal Report');
$pdf->SetSubject('Report');
$pdf->SetKeywords('TCPDF, PDF, report');

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 10);

// Add a page in portrait mode
$pdf->AddPage('P', 'A3');

// Background image path
$backgroundImage = 'logo.png';
$logoFile = 'logo.png';

// Add the image with 50% opacity (opacity value ranges from 0 to 1)
$pdf->Image($logoFile, 30, 30, 30, 30, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->setAlpha(0.1);
$pdf->Image($backgroundImage, 15, 80, 300, 300, '', '', '', false, 300, '', false, false, 0, 'T', false, 0.9);
$pdf->setAlpha(1);

// Capture the HTML content
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Report</title>
    <style>
        body {
            font-family: helvetica;
            font-size: 12px; /* Increased base font size */
        }
        .card {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .card-header {
            margin: 0 auto;
            text-align: center;
            width: fit-content; /* Adjust the width as needed */
        }

        .card-header .logo {
            max-width: 60px;
            margin-bottom: 10px;
        }
        .info-table, .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table th, .info-table td, .result-table th, .result-table td {
            border: 1px solid #ddd;
            padding: 20px; /* Increased padding for all table cells */
            font-size: 12px; /* Increased font size for table cells */
            text-align: center;
        }
        .info-table th {
            background-color: #f2f2f2;
        }
        .result-table th {
            background-color: #e0e0e0;
        }
        .card-footer {
            text-align: center;
            margin-top: 15px;
        }
        .principal-comment {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .principal-comment h4 {
            margin: 0 0 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>DOMINICAN COLLEGE MAFOLUKU</h1>
            <p>36, Old Ewu Road, Mafoluku, Lagos</p>
            <p>Forming head, heart, and hand in the Dominican tradition</p>
            <h2 style="color: red">TERMINAL REPORT</h2>
        </div>
        <table class="info-table">
            <thead>
                <tr>
                    <th colspan="2">NAME OF STUDENT: <?php echo htmlspecialchars($studentname, ENT_QUOTES, 'UTF-8'); ?></th>
                    <th>ADMISSION NUMBER: <?php echo htmlspecialchars($sid, ENT_QUOTES, 'UTF-8'); ?></th>
                    <td colspan="2">CLASS: <?php echo htmlspecialchars($baseclassid, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <td colspan="2">NUMBER IN CLASS: <?php echo htmlspecialchars($studentcount, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>TERM: <?php echo htmlspecialchars($termname, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td colspan="2">SESSION: <?php echo htmlspecialchars($sessionvalue, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <td>TOTAL MARKS OBTAINABLE: <?php echo htmlspecialchars($total_marks_obtainable, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>TOTAL MARKS OBTAINED: <?php echo htmlspecialchars($total_marks_obtained, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>AVERAGE: <?php echo htmlspecialchars($average, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>POSITION: <?php echo htmlspecialchars($suffixedClassPosition, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>STATUS: <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            </thead>
        </table>

        <table class="result-table">
            <thead>
                <tr>
                    <th style="width: 12%;">SUBJECTS</th>
                    <th style="width: 4%;">CA</th>
                    <th style="width: 4%;">EXAM</th>
                    <th style="width: 5%;">TOTAL</th>
                    <th style="width: 7%;">CLASS AVERAGE</th>
                    <th style="width: 4%;">POS</th>
                    <th style="width: 4%;">GRADE</th>
                    <th style="width: 10%; border-right: 3px solid black;">REMARK</th>
                    <th style="width: 5%;">1ST TERM</th>
                    <th style="width: 5%;">2ND TERM</th>
                    <th style="width: 5%;">3RD TERM</th>
                    <th style="width: 7%;">CUM. TOTAL</th>
                    <th style="width: 7%;">CUM. AVERAGE</th>
                    <th style="width: 4%;">POS</th>
                    <th style="width: 7%;">CUM. GRADE</th>
                    <th style="width: 10%;">CUM. REMARK</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 12%;">Marks Obtainable</td>
                    <td style="width: 4%;">40</td>
                    <td style="width: 4%;">60</td>
                    <td style="width: 5%;">100</td>
                    <td style="width: 7%;"></td>
                    <td style="width: 4%;"></td>
                    <td style="width: 4%;"></td>
                    <td style="width: 10%; border-right: 3px solid black"></td>
                    <td style="width: 5%;">100</td>
                    <td style="width: 5%;">100</td>
                    <td style="width: 5%;">100</td>
                    <td style="width: 7%;"></td>
                    <td style="width: 7%;"></td>
                    <td style="width: 4%;"></td>
                    <td style="width: 7%;"></td>
                    <td style="width: 10%;"></td>
                </tr>
                <?php foreach ($ca_marks as $marks): 
                    $totalMarks = $marks['total'];
                    $gradeRemark = generateGradeRemark($totalMarks, $thresholds);

                    $cumAvg = $marks['cumavg'];
                    $cumGradeRemark = generateGradeRemark($cumAvg, $thresholds);

                    $totalColor = $totalMarks < 50 ? '#FF0000' : '#000000';
                    $cumTotalColor = $marks['cumtotal'] < 50 ? '#FF0000' : '#000000';
                ?>
                <tr>
                    <td style="width: 12%;"><?php echo htmlspecialchars($marks['subject_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="width: 4%;"><?php echo htmlspecialchars($marks['CA'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="width: 4%;"><?php echo htmlspecialchars($marks['exam'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="color: <?php echo htmlspecialchars($totalColor, ENT_QUOTES, 'UTF-8'); ?>; width: 5%;">
                    <?php echo htmlspecialchars($marks['total'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                    <td style="width: 7%;"><?php echo htmlspecialchars($marks['classaverage'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="width: 4%;"><?php echo htmlspecialchars($marks['armposition'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="color: <?php echo htmlspecialchars($gradeRemark['gradeColor'], ENT_QUOTES, 'UTF-8'); ?>; width: 4%;">
                        <?php echo htmlspecialchars($gradeRemark['grade'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                    <td style="border-right: 3px solid black; color: <?php echo htmlspecialchars($gradeRemark['remarkColor'], ENT_QUOTES, 'UTF-8'); ?>;width: 10%;">
                        <?php echo htmlspecialchars($gradeRemark['remark'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                    <td style="width: 5%;"><?php echo htmlspecialchars($marks['firsttermtotal'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="width: 5%;"><?php echo htmlspecialchars($marks['secondtermtotal'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="width: 5%;"><?php echo htmlspecialchars($marks['thirdtermtotal'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="color: <?php echo htmlspecialchars($cumTotalColor, ENT_QUOTES, 'UTF-8'); ?>; width: 7%;">
                    <?php echo htmlspecialchars($marks['cumtotal'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                    <td style="width: 7%;"><?php echo htmlspecialchars($marks['cumavg'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="width: 4%;"><?php echo htmlspecialchars($marks['armposition'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td style="color: <?php echo htmlspecialchars($cumGradeRemark['gradeColor'], ENT_QUOTES, 'UTF-8'); ?>; width: 7%;">
                        <?php echo htmlspecialchars($cumGradeRemark['grade'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                    <td style="color: <?php echo htmlspecialchars($cumGradeRemark['remarkColor'], ENT_QUOTES, 'UTF-8'); ?>; width: 10%;">
                        <?php echo htmlspecialchars($cumGradeRemark['remark'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Principal Comment Section -->
        <div class="principal-comment">
            <h4>Principal's Comment:</h4>
            <p><?php echo htmlspecialchars($principalComment, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <!--<div class="principal-comment">
            <h4>Guardian Comment:</h4>
            <p><?php echo htmlspecialchars($comments, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>-->

        <!-- Result Criteria -->
        <div class="principal-comment">
            <h4>Result Criteria</h4>
            <p><?php echo htmlspecialchars($criteria, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </div>
</div>
</body>
</html>

<?php
$html = ob_get_clean();

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('Student_result_report.pdf', 'I');
?>
