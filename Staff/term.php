<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php
include_once 'dbConnection.php';
session_start();
                if(isset($_SESSION['fname']))
                {
                  echo $_SESSION['fname'];
                }
                else
                {
                  echo '|| Staff Portal';
                }
              ?> || Staff Portal </title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>
 <link rel="stylesheet" href="css/main.css">
 <link  rel="icon" href="image/logo.png">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>
 <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

<script>
$(function () {
    $(document).on( 'scroll', function(){
        console.log('scroll top : ' + $(window).scrollTop());
        if($(window).scrollTop()>=$(".logo").height())
        {
             $(".navbar").addClass("navbar-fixed-top");
        }

        if($(window).scrollTop()<$(".logo").height())
        {
             $(".navbar").removeClass("navbar-fixed-top");
        }
    });
});</script>
</head>

<body  style="background:#eee;">
<div class="header">
<div class="row">
<div class="col-lg-6">
<span class="logo">Staff Portal</span></div>
<?php
 include_once 'dbConnection.php';
//$email=$_SESSION['email'];
  if(!(isset($_SESSION['email']))){
header("location:index.php");

}
else
{
$fname = $_SESSION['fname'];
$email=$_SESSION['email'];
$staffid = $_SESSION['staffid'];

include_once 'dbConnection.php';
echo '<span class="pull-right top title1" ><span class="log1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;<b>Hello,</b></span>
<a href="dash.php" class="log log1" title="You Are Logged In As '.$fname.'"><b>'.$fname.'</b></a>
|&nbsp;<a href="logout.php?q=account.php" class="log"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;<b>Log Out</b></button></a></span>';
}
?>

</div></div>


<?php
include_once 'dbConnection.php';

if(@$_GET['q']==6) {
    // Display Broadsheet Form
    echo '<div class="panel title">';
    echo '<form id="broadsheetForm" class="form-horizontal" method="GET" action="">';
    echo '<input type="hidden" name="q" value="6">';

    // Select Session Dropdown
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="session">Select Session:</label>';
    echo '<div class="col-md-4">';
    echo '<select id="session" name="session" class="form-control" required>';
    echo '<option value="" disabled selected>Select Session</option>';

    // Fetch distinct sessions from the results table
    $session_query = mysqli_query($con, "SELECT DISTINCT session FROM results ORDER BY session");
    while ($row = mysqli_fetch_array($session_query)) {
        $session = $row['session'];
        echo '<option value="'.$session.'">'.$session.'</option>';
    }

    echo '</select>';
    echo '</div>';
    echo '</div>';

    // Select Term Dropdown
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="term">Select Term:</label>';
    echo '<div class="col-md-4">';
    echo '<select id="term" name="term" class="form-control" required>';
    echo '<option value="" disabled selected>Select Term</option>';

    $termQuery = mysqli_query($con, "SELECT termid, term FROM terms");
    while ($termRow = mysqli_fetch_array($termQuery)) {
        $termid = $termRow['termid'];
        $term = $termRow['term'];
        echo '<option value="' . $termid . '">' . $term . '</option>';
    }

    echo '</select>';
    echo '</div>';
    echo '</div>';

    // Submit Button
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="submit"></label>';
    echo '<div class="col-md-4">';
    echo '<button type="submit" name="download" class="btn btn-primary">Download Term</button>';
    echo '</div>';
    echo '</div>';

    echo '</form>';
    echo '</div>';
}
?>


<?php
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
        'term' =>$termvalue,
        'staffid'=>$staffid
    ];
    header('Location: broad.php'); // Redirect after processing
    exit; // Ensure no further code is executed
}
?>

