<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php
include_once 'dbConnection.php';
session_start();
if (isset($_SESSION['fname'])) {
    echo $_SESSION['fname'];
} else {
    echo '|| Staff Portal';
}
?> || Staff Portal</title>
<link rel="stylesheet" href="css/bootstrap.min.css"/>
<link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
<link rel="stylesheet" href="css/main.css">
<link rel="icon" href="image/logo.png">
<link rel="stylesheet" href="css/font.css">
<script src="js/jquery.js" type="text/javascript"></script>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

<script>
$(function () {
    $(document).on('scroll', function(){
        console.log('scroll top : ' + $(window).scrollTop());
        if ($(window).scrollTop() >= $(".logo").height()) {
            $(".navbar").addClass("navbar-fixed-top");
        }

        if ($(window).scrollTop() < $(".logo").height()) {
            $(".navbar").removeClass("navbar-fixed-top");
        }
    });
});
</script>
</head>

<body style="background:#eee;">
<div class="header">
<div class="row">
<div class="col-lg-6">
<span class="logo">Staff Portal</span></div>
<?php
include_once 'dbConnection.php';
if (!(isset($_SESSION['email']))) {
    header("location:index.php");
} else {
    $fname = $_SESSION['fname'];
    $email = $_SESSION['email'];
    $staffid = $_SESSION['staffid'];

    echo $staffid;

    echo '<span class="pull-right top title1"><span class="log1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;<b>Hello,</b></span>
    <a href="dash.php" class="log log1" title="You Are Logged In As '.$fname.'"><b>'.$fname.'</b></a>
    |&nbsp;<a href="logout.php?q=account.php" class="log"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;<b>Log Out</b></a></span>';
}
?>
</div>
</div>

<!-- Remark start -->
<?php 
if (@$_GET['q'] == 8) {
    // Display Remark Form
    echo '<div class="panel title">';
    echo '<form id="broadsheetForm" class="form-horizontal" method="GET" action="">';
    echo '<input type="hidden" name="remark" value="remark">';

    // Select Session Dropdown
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="session">Select Session:</label>';
    echo '<div class="col-md-4">';
    echo '<select id="session" name="session" class="form-control" required>';
    echo '<option value="" disabled selected>Select Session</option>';

    // Fetch distinct sessions from the exams table
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
    echo '<button id="submit" name="submit" class="btn btn-primary">Submit</button>';
    echo '</div>';
    echo '</div>';

    echo '</form>';
    echo '</div>';
}

if (isset($_GET['remark']) && $_GET['remark'] == 'remark' && isset($_GET['session']) && isset($_GET['term'])) {
    // Process form data
    $sessionvalue = $_GET['session'];
    $termvalue = $_GET['term'];

    // Check if session and term are valid
    if (!empty($sessionvalue) && !empty($termvalue)) {
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
            $baseclassid = substr($classid, 0);
            $class = $baseclassid;

            // Fetch students based on selected term and session
            $student_query = mysqli_query($con, "
                SELECT s.sid, s.fname, s.lname
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
            if (mysqli_num_rows($student_query) > 0) {
                // Start HTML output
                echo '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
                        <tr>
                            <td><b>S.N.</b></td>
                            <td><b>Student Name</b></td>
                            <td><b>Term</b></td>
                            <td><b>Session</b></td>
                            <td><b>PASS/WEAK PASS/FAILED</b></td>
                            <td><b>Guardian comment</b></td>
                            <td><b>Result Criteria</b></td>
                            <td><b>Action</b></td>
                        </tr>';

                // Loop through each student and output their data
                // Loop through each student and output their data
                $sn = 1; // Serial number counter
                while ($student_row = mysqli_fetch_assoc($student_query)) {
                    $sid = $student_row['sid'];
                    $fname = $student_row['fname'];
                    $lname = $student_row['lname'];
                    $fullname = $fname . ' ' . $lname;

                    echo '<form action="update_remark.php" method="post">';
                    echo '<input type="hidden" name="term" value="' . htmlspecialchars($termvalue) . '">';
                    echo '<input type="hidden" name="session" value="' . htmlspecialchars($sessionvalue) . '">';
                    echo '<input type="hidden" name="staffid" value="' . htmlspecialchars($_SESSION['staffid']) . '">';
                    echo '<input type="hidden" name="sid" value="' . htmlspecialchars($sid) . '">'; // Add sid hidden input

                    echo '<tr>
                            <td>' . htmlspecialchars($sn) . '</td>
                            <td>' . htmlspecialchars($fullname) . '</td>
                            <td>' . htmlspecialchars($termname) . '</td>
                            <td>' . htmlspecialchars($sessionvalue) . '</td>
                            <td><input type="text" name="status" /></td>
                            <td>
                            <textarea
                                rows="3"
                                placeholder="Enter comment here...."
                                name="comment"
                            ></textarea></td>
                            <td>
                            <textarea
                                rows="3"
                                placeholder="Enter criteria here...."
                                name="criteria"
                            ></textarea></td>
                            <td><button type="submit" name="submit" class="btn btn-success">Update</button></td>
                        </tr>';
                    echo '</form>';

                    $sn++; // Increment serial number
                }


                // End HTML output for table
                echo '</table></div></div>';
            } else {
                echo 'No students found for the selected term and session.';
            }
        } else {
            echo 'You are not assigned as a guardian for any class.';
        }
    }
}
?>
<!--Remark closed -->

</body>
</html>
