<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php
include_once 'dbConnection.php';
session_start();
                if(isset($_SESSION['fname']))
                {
                  echo $_SESSION['fname'];
                }
                else
                {
                  echo '|| Test Your Skill';
                }
              ?>CBE Portal</title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>
 <link rel="stylesheet" href="css/main.css">
 <link  rel="icon" href="image/logo.png">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js"  type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

 <!--alert message-->
<?php if(@$_GET['w'])
{echo'<script>alert("'.@$_GET['w'].'");</script>';}
?>
<!--alert message end-->
</head>
<?php
include_once 'dbConnection.php';
?>
<body>
<div class="header">
<div class="row">
<div class="col-lg-6">
<span class="logo">CBE Portal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <span class="glyphicon glyphicon-calendar" style="font-size:19px"></span> <span style="font-size:20px" id="clockbox"></span></span></div>
  <script type="text/javascript">
tday=new Array("Sun","Mon","Tue","Wed","Thur","Fri","Sat");
  tmonth=new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

  function GetClock(){
  var d=new Date();
  var nday=d.getDay(),nmonth=d.getMonth(),ndate=d.getDate(),nyear=d.getYear();
  if(nyear<1000) nyear+=1900;
  var nhour=d.getHours(),nmin=d.getMinutes(),nsec=d.getSeconds(),ap;

  document.getElementById('clockbox').innerHTML=""+tday[nday]+", "+tmonth[nmonth]+" "+ndate+", "+nyear+"";
  }

  {
  GetClock();
  setInterval(GetClock,1000);
  }
</script>
<div class="col-md-4 col-md-offset-1">
 <?php
 include_once 'dbConnection.php';
  if(!(isset($_SESSION['email']))){
header("location:index.php");

}
else
{
$fname = $_SESSION['fname'];
$email=$_SESSION['email'];
$sid =$_SESSION['sid'];

include_once 'dbConnection.php';
echo '<span class="pull-right top title1" ><span class="log1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;<b>Hello,</span>
<a href="?q=45" class="log log1" title="You Are Logged In As '.$fname.'"><b>'.$fname.'</b></a>
|&nbsp;<a href="logout.php?q=account.php" class="log"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;<b>Signout</b></button></a></span>';

}
?>
</div>
</div></div>
<div class="bg">

<!--navigation menu-->

<div class="bg2">
<nav class="navbar navbar-default title1">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="true">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a class="navbar-brand title" title="You Are Logged in" >  Dashboard </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php if(@$_GET['q']==1) echo'class="active"'; ?>><a href="account.php?q=1"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;<b>Home</b><span class="sr-only"></span></a></li>
        <li <?php if(@$_GET['q']==9) echo'class="active"'; ?>><a href="account.php?q=9"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;<b>Profile</b></a></li>
        <li class="pull-right"> <a href="logout.php?q=account.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;<b>Log Out</b></a></li>
      </ul>
      </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav><!--navigation menu closed-->

<div class="container"><!--container start-->
<div class="row">
<div class="col-md-12">
  <!--Update Page start -->
 <?php
    @$page=  $_GET['page'];
      if($page!="")
      {
        if($page=="update_password")
      {
        include('update_password.php');

      }

        if($page=="update_profile")
      {
        include('update_profile.php');

      }
      if($page=="feedback")
      {
        include('give_feedback.php');

      }
      if($page=="search_client")
      {
        include('search.php');

      }
      }
      else
      {

      ?>
      <!--Update Page ends -->
      <!--Update Profile -->
      <?php if(@$_GET['q']==9) {
echo '<div class="panel"><h5 class="title" style="color:#660033">🧥 '.$fname.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;📨 '.$email.'</h5></div>';
echo '<div class="panel"><center><tr style="color:#990000" ><a href="?page=update_profile"><button class="btn btn-primary"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;Update Profile</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?page=update_password"><button class="btn btn-primary"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;Update Password</button></a></tr></center></div>';
echo '<div class="panel title"><center><tr style="color:#990000" >"This site is protected by reCAPTCHA and the Google <a href=https:policies.google.com/privacy>Privacy Policy</a> and <a href=https:policies.google.com/terms>Terms of Service</a> apply."</center></div>';}
}?>

<!--Update Profile Ends -->
  <!--Default Page-->
  <?php if(@$_GET['q']==45) {
    echo '<div class="panel"><center><h1 class="title" style="color:#660033">You Are Welcome, '.$fname.'!!</h1><center><br /></div>';
    }?>
    <!--Default Page Ends-->
    <!-- Default welcome page -->
     <?php if(@$_GET['q']==47) {
    echo '<div class="panel"><center><h1 class="title" style="color:#660033">Welcome Back, '.$fname.'!!</h1><center><br /></div>';
    }?>
<!--home start-->
<?php 
if (@$_GET['q'] == 1) {
    // Initialize $sid and $email with appropriate values
    $sid = mysqli_real_escape_string($con, $_SESSION['sid']); // Assuming sid is stored in session
    $email = mysqli_real_escape_string($con, $_SESSION['email']); // Assuming email is stored in session

    // Fetch the student's classid from the students table
    $student_query = "SELECT classid FROM students WHERE sid = '$sid'";
    $student_result = mysqli_query($con, $student_query) or die(mysqli_error($con));

    if (mysqli_num_rows($student_result) > 0) {
        $student_row = mysqli_fetch_array($student_result);
        $student_classid = $student_row['classid'];
        
        // Remove the last character from the classid
        $base_classid = substr($student_classid, 0, -1);

        // Fetch subjects associated with the student
        $subject_query = "
            SELECT subject 
            FROM subjects 
            WHERE sub_id IN (
                SELECT sub_id 
                FROM student_class_subject 
                WHERE sid = '$sid'
            )
        ";
        $subject_result = mysqli_query($con, $subject_query) or die(mysqli_error($con));

        $subjects = [];
        if ($subject_result) {
            while ($row = mysqli_fetch_array($subject_result)) {
                $subjects[] = $row['subject'];
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }

        // Query to fetch exams associated with the base classid
        $exam_query = "
            SELECT e.eid, s.subject, c.classid, e.timelimit, e.instruction, e.tnoq, e.date, t.term, e.session
            FROM exams e
            LEFT JOIN subjects s ON e.sub_id = s.sub_id
            LEFT JOIN class c ON e.classid = c.classid
            LEFT JOIN terms t ON e.termid = t.termid
            WHERE e.classid LIKE '$base_classid%'
        ";
        $exam_result = mysqli_query($con, $exam_query) or die(mysqli_error($con));

        echo '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
        <tr><td><b>S.N.</b></td><td><b>Subject</b></td><td><b>Term</b></td><td><b>Session</b></td><td><b>Class</b></td><td><b>Time Limit (mins)</b></td><td><b>No. of Questions</b></td><td><b>Exam Time</b></td><td><b>Instructions</b></td><td></td></tr>';

        $c = 1;
        while ($row = mysqli_fetch_array($exam_result)) {
            $subject = $row['subject'];
            if (in_array($subject, $subjects)) { // Check if the subject is in the student's subjects
                $term = $row['term'];
                $session = $row['session'];
                $classid = $row['classid'];
                $base_classid = substr($classid, 0, -1); // Remove the last character from the classid
                $timelimit = $row['timelimit'];
                $instruction = $row['instruction'];
                $examid = $row['eid'];
                $tnoq = $row['tnoq']; // Number of questions
                $exam_time = date('Y-m-d H:i:s', strtotime($row['date'])); // Format exam date/time

                // Check if student has written exam already
                $q12 = mysqli_query($con, "SELECT score FROM history WHERE eid='$examid' AND email='$email'") or die('Error98');
                $rowcount = mysqli_num_rows($q12);
                $current_time = date('Y-m-d H:i:s'); // Get current date/time

                if ($rowcount == 0) {
                    if ($current_time >= $exam_time) {
                        echo '<tr><td>'.$c++.'</td><td>'.$subject.'</td><td>'.$term.'</td><td>'.$session.'</td><td>'.$base_classid.'</td><td>'.$timelimit.'</td><td>'.$tnoq.'</td><td>'.$exam_time.'</td><td>'.$instruction.'</td>
                        <td><b><a href="account.php?q=quiz&step=2&eid='.$examid.'&n=1&t='.$tnoq.'" class="pull-right btn sub1" style="margin:0px;background:#99cc32"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;
                        <span class="title1" class="start" ><b>Start</b></span></a></b></td></tr>';
                    } else {
                        echo '<tr><td>'.$c++.'</td><td>'.$subject.'</td><td>'.$term.'</td><td>'.$session.'</td><td>'.$base_classid.'</td><td>'.$timelimit.'</td><td>'.$tnoq.'</td><td>'.$exam_time.'</td><td>'.$instruction.'</td>
                        <td><b><a href="#" class="pull-right btn sub1" style="margin:0px;background:gray; pointer-events: none; cursor: default;"><span class="glyphicon glyphicon-time" aria-hidden="true"></span>&nbsp;
                        <span class="title1" class="start" ><b>Start</b></span></a></b></td></tr>';
                    }
                } else {
                    echo '<tr><td>'.$c++.'</td><td>'.$subject.'&nbsp;<span title="This quiz is already solved by you" class="glyphicon glyphicon-ok" aria-hidden="true"></span></td><td>'.$term.'</td><td>'.$session.'</td><td>'.$base_classid.'</td><td>'.$timelimit.'</td><td>'.$tnoq.'</td><td>'.$exam_time.'</td><td>'.$instruction.'</td>
                    <td><b><a href="#" class="pull-right btn sub1" style="margin:0px;background:red"><span class="glyphicon glyphicon-stop" aria-hidden="true"></span>&nbsp;
                    <span class="title1" class="restart" ><b>Taken</b></span></a></b></td></tr>';
                }
            }
        }

        echo '</table></div></div>';
    } else {
        echo "No class information found for the student.";
    }
}
?>




<!-- ⏰: <span id="timebox" style=" font-family: 'typo'"></span>
<script type="text/javascript">
  function GetTime(){
  var d=new Date();
  var nhour=d.getHours(),nmin=d.getMinutes(),nsec=d.getSeconds(),ap;

  if(nhour===0){ap=" AM";nhour=12;}
  else if(nhour<12){ap=" AM";}
  else if(nhour==12){ap=" PM";}
  else if(nhour>12){ap=" PM";nhour-=12;}

  if(nmin<=9) nmin="0"+nmin;
  if(nsec<=9) nsec="0"+nsec;

  document.getElementById('timebox').innerHTML=""+nhour+":"+nmin+":"+nsec+ap+"";
  }

  {
  GetTime();
  setInterval(GetTime,1000);
  }
</script> -->


<!-- When User Clicks Start Button -->
<!--home closed-->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Establish database connection
// $con = mysqli_connect("host", "user", "password", "database");
// if (!$con) {
//     die("Database connection error: " . mysqli_connect_error());
// }

function executeQuery($query) {
    global $con;
    $result = mysqli_query($con, $query);
    if (!$result) {
        die('Database query error: ' . mysqli_error($con));
    }
    return $result;
}

// Check if the user is logged in and has started an exam
if (isset($_SESSION['email']) && @$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
    $eid = mysqli_real_escape_string($con, $_GET['eid']);
    $sn = mysqli_real_escape_string($con, $_GET['n']);
    $total = mysqli_real_escape_string($con, $_GET['t']);
    $email = mysqli_real_escape_string($con, $_SESSION['email']);

    // Check and set session lock for the exam
    if (!isset($_SESSION['exam_in_progress'])) {
        $_SESSION['exam_in_progress'] = true;
    }

    // Store the exam ID in session
    $_SESSION['eid'] = $eid;

    // Fetch and shuffle questions if not already done
    if (!isset($_SESSION['quiz'][$eid]['questions'])) {
        $questions = [];
        $q = executeQuery("SELECT * FROM questions WHERE eid='$eid'");
        while ($row = mysqli_fetch_array($q)) {
            $questions[] = $row;
        }

        // Shuffle questions
        shuffle($questions);

        // Reassign serial numbers
        foreach ($questions as $index => $question) {
            $_SESSION['quiz'][$eid]['questions'][$index + 1] = $question;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $qid = mysqli_real_escape_string($con, $_POST['qid']);
        $ans = mysqli_real_escape_string($con, $_POST['ans'] ?? 0);
        $_SESSION['quiz'][$eid]['answers'][$qid] = $ans;

        if (isset($_POST['next'])) {
            $sn++;
        } elseif (isset($_POST['prev'])) {
            $sn--;
        } elseif (isset($_POST['submit'])) {
            // Check if there is an existing row in the history table
            $existing_history_query = "SELECT * FROM history WHERE email='$email' AND eid='$eid'";
            $existing_history_result = executeQuery($existing_history_query);

            if (mysqli_num_rows($existing_history_result) > 0) {
                // Delete existing row if found
                $delete_history_query = "DELETE FROM history WHERE email='$email' AND eid='$eid'";
                executeQuery($delete_history_query);
            }

            $score = 0;
            $sahi = 0;

            foreach ($_SESSION['quiz'][$eid]['answers'] as $qid => $selected_option_id) {
                $option_query = executeQuery("SELECT optionid FROM options WHERE qid='$qid' AND optionid='$selected_option_id'");
                $option_row = mysqli_fetch_array($option_query);
                $optionid = $option_row['optionid'] ?? 0;

                $answer_query = executeQuery("SELECT ansid FROM answer WHERE qid='$qid'");
                $answer_row = mysqli_fetch_array($answer_query);
                $ansid = $answer_row['ansid'];

                if ($optionid == $ansid) {
                    $exams_query = executeQuery("SELECT sahi FROM exams WHERE eid='$eid'");
                    $exams_row = mysqli_fetch_array($exams_query);
                    $sahi_value = $exams_row['sahi'];

                    $score += $sahi_value;
                    $sahi++;
                }
            }

            $tscore = $score;
            $tsahi = $sahi;

            $tnoq_query = executeQuery("SELECT tnoq FROM exams WHERE eid='$eid'");
            $tnoq_row = mysqli_fetch_array($tnoq_query);
            $tnoq = $tnoq_row['tnoq'];
            $wrong = $tnoq - $tsahi;

            $history_query = "INSERT INTO history (email, eid, score, sahi, wrong, date, tnoq) VALUES ('$email', '$eid', '$tscore', '$tsahi', '$wrong', NOW(), $tnoq)";
            executeQuery($history_query);

            echo '<script>';
            echo 'alert("You have submitted successfully");';
            echo 'clearTimer();'; // Clear the timer session storage
            echo 'window.location.href = "account.php?q=result&eid=' . $eid . '";'; // Redirect to result page after alert
            echo '</script>';
            exit;
        }
    }

    if ($sn < 1) $sn = 1;
    if ($sn > $total) $sn = $total;
    
    //question nav
    echo '<div class="navigation-card">';
    echo '<div class="navigation-calendar">';
    echo '<h3>Question Navigation</h3>';
    echo '<div class="question-links">';
    echo '<ul class="calendar-grid">'; // Start a list for the question links

    for ($i = 1; $i <= $total; $i++) {
        $linkClass = isset($_SESSION['quiz'][$eid]['answers'][$_SESSION['quiz'][$eid]['questions'][$i]['qid']]) ? 'answered' : 'unanswered';
        $activeClass = ($i == $sn) ? 'active' : ''; // Add 'active' class to the current question number
        echo '<li><a href="account.php?q=quiz&step=2&eid=' . $eid . '&n=' . $i . '&t=' . $total . '" id="question-link-' . $i . '" class="question-link ' . $linkClass . ' ' . $activeClass . '" data-answered="' . (isset($_SESSION['quiz'][$eid]['answers'][$_SESSION['quiz'][$eid]['questions'][$i]['qid']]) ? 'true' : 'false') . '">' . $i . '</a></li>';
    }

    echo '</ul>'; // Close the list
    echo '</div>';
    echo '</div>';
    echo '</div>';

    //display current question
    $question = $_SESSION['quiz'][$eid]['questions'][$sn];
    $qns = $question['qns'];
    $qid = $question['qid'];
    $imageData = $question['image'];

    echo '<div class="panel" style="margin:4%">';
    echo '<div id="quiz-timer" style="text-align:right; font-weight:bold; margin-bottom:10px;"></div>';

    if (!empty($imageData)) {
        echo '<div style="text-align:center; margin-bottom:15px;">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($imageData) . '" alt="Question Image" style="max-width:100%; height:auto;"/><br /><br />';
        echo '</div>';
    }

    echo '<b style="font-family:sans; font-size:20px;">Question ' . $sn . ':<br />' . $qns . '?</b><br /><br />';

    $q = executeQuery("SELECT * FROM options WHERE qid='$qid'");
    echo '<form id="quizForm" name="quizForm" action="account.php?q=quiz&step=2&eid=' . $eid . '&n=' . $sn . '&t=' . $total . '" method="POST" class="form-horizontal">';
    echo '<input type="hidden" name="qid" value="' . $qid . '">';

    while ($row = mysqli_fetch_array($q)) {
        $option = $row['option'];
        $optionid = $row['optionid'];
        $checked = isset($_SESSION['quiz'][$eid]['answers'][$qid]) && $_SESSION['quiz'][$eid]['answers'][$qid] == $optionid ? 'checked' : '';
        echo '<input type="radio" name="ans" value="' . $optionid . '" ' . $checked . '>&nbsp;<span style="font-size:20px;">' . $option . '</span><br /><br />';
    }

    echo '<br />';

    $selectTimeQuery = "SELECT timelimit FROM exams WHERE eid = '$eid'";
    $result = executeQuery($selectTimeQuery);
    $time = 0;

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $time = (int)$row['timelimit'] * 60;
    }

    echo '<div id="quiz-timer" style="font-size:20px;"></div>';

    echo '<script>
    let timerInterval;

    document.addEventListener("DOMContentLoaded", function() {
        const timerElement = document.getElementById("quiz-timer");
        let remainingTime = getRemainingTime();

        timerInterval = setInterval(() => {
            if (remainingTime <= 0) {
                clearInterval(timerInterval);
                timerElement.innerHTML = "<span style=\"color: red;\">Time\'s up!</span>";
                document.getElementById("quiz-modal").style.display = "block"; // Display the modal
                return;
            }
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            const timerText = "⏰ Time remaining: <em><strong>" + minutes + " minutes and " + (seconds < 10 ? "0" : "") + seconds + " seconds</strong></em>";

            timerElement.innerHTML = remainingTime < 60
                ? "<span style=\"color: red;\">" + timerText + "</span>"
                : timerText;

            remainingTime--;

            sessionStorage.setItem("quiz_timer_' . $eid . '_remaining_time", remainingTime.toString());
        }, 1000);

        function getRemainingTime() {
            const storedTime = sessionStorage.getItem("quiz_timer_' . $eid . '_remaining_time");
            return storedTime !== null ? parseInt(storedTime, 10) : ' . $time . ';
        }

        function clearTimer() {
            // Clear session storage for this quiz timer
            sessionStorage.removeItem("quiz_timer_' . $eid . '_remaining_time");
        }

        // Prevent switching tabs or minimizing the browser window
        let isTabActive = true;
        window.addEventListener("blur", () => {
            isTabActive = false;
        });

        window.addEventListener("focus", () => {
            if (!isTabActive) {
                alert("You are not allowed to switch tabs during the exam.");
                document.hasFocus() ? isTabActive = true : window.focus();
            }
        });

        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                isTabActive = false;
            }
        });
    });
</script>';

    if ($sn > 1) {
        echo '<button type="navigate" name="prev" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Previous</button>&nbsp;';
    }

    if ($sn < $total) {
        echo '<button type="navigate" name="next" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-right"></span>&nbsp;Next</button>&nbsp;';
    } else {
        echo '<button type="button" name="submit" class="btn btn-success" onclick="showConfirmationModal()">Submit</button>';
    }

    echo '</form>';
    echo '</div>';
}
?>

<!--for timer modal-->
<div id="quiz-modal" class="modal-timer">
    <div class="modal-content-timer">
        <div id="quiz-timer"></div>
        <div class="modal-body">
          <p>⏰ Time is up, click submit to finish!!!</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="submitQuiz_on_timer()">Submit</button>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Submit Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to submit this exam?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" onclick="submitQuiz()">Yes</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questionLinks = document.querySelectorAll('.question-link');

        questionLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default navigation

                const form = document.getElementById('quizForm');
                const actionUrl = this.href;
                form.action = actionUrl;

                // Add a hidden input to indicate that this is a navigation
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'navigate';
                hiddenInput.value = 'true';
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            });
        });
    });

  document.addEventListener('DOMContentLoaded', function () {
    const questionLinks = document.querySelectorAll('.question-link');

    questionLinks.forEach(link => {
        link.addEventListener('click', function () {
            questionLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });

    document.querySelectorAll('input[name="ans"]').forEach(input => {
        input.addEventListener('change', function () {
            const currentQuestionLink = document.getElementById(`question-link-${currentQuestionNumber}`);
            currentQuestionLink.classList.add('answered');
            currentQuestionLink.classList.remove('unanswered');
            sessionStorage.setItem(`answered-${currentQuestionNumber}`, true);
        });
    });

    updateLinkColors();
});

function updateLinkColors() {
    document.querySelectorAll('.question-link').forEach(link => {
        const qid = link.id.split('-').pop();
        if (sessionStorage.getItem(`answered-${qid}`)) {
            link.classList.add('answered');
            link.classList.remove('unanswered');
        } else {
            link.classList.add('unanswered');
            link.classList.remove('answered');
        }
    });
}

const currentQuestionNumber = <?php echo $sn; ?>;

function showConfirmationModal() {
        $('#confirmationModal').modal('show');
    }

    function submitQuiz() {
        clearTimeout(submitTimer);
        const formData = new FormData(document.getElementById("quizForm"));
        formData.append("submit", true);

        fetch("account.php?q=quiz&step=2&eid=<?php echo $eid; ?>&n=<?php echo $sn; ?>&t=<?php echo $total; ?>", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(responseText => {
            document.body.innerHTML = responseText;
            clearTimer(); // Clear timer after auto submission
        })
        .catch(error => {
            console.error("Error submitting the quiz:", error);
        });
    }

    let submitTimer;

    function autoSubmitQuiz() {
        clearTimeout(submitTimer);
        submitTimer = setTimeout(() => {
            const formData = new FormData(document.getElementById("quizForm"));
            formData.append("submit", true);
            
            fetch("account.php?q=quiz&step=2&eid=<?php echo $eid; ?>&n=<?php echo $sn; ?>&t=<?php echo $total; ?>", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(responseText => {
                document.body.innerHTML = responseText;
                clearTimer(); // Clear timer after auto submission
            })
            .catch(error => {
                console.error("Error submitting the quiz:", error);
            });
        }, 1000); // Adjust debounce time as needed (1 second in this example)
    }

    function clearTimer() {
        sessionStorage.removeItem("quiz_timer_<?php echo $eid; ?>_remaining_time");
    }

    document.addEventListener("visibilitychange", function() {
        if (document.visibilityState === 'hidden') {
            autoSubmitQuiz(); // Trigger auto submission on tab switch or minimize
        }
    });

    window.addEventListener("blur", function() {
        autoSubmitQuiz(); // Trigger auto submission on tab switch or minimize
    });

    window.addEventListener("focus", function() {
        clearTimeout(submitTimer); // Clear debounce timeout on focus
    });

    function submitQuiz_on_timer() {
        clearTimeout(submitTimer);
        const formData = new FormData(document.getElementById("quizForm"));
        formData.append("submit", true);

        fetch("account.php?q=quiz&step=2&eid=<?php echo $eid; ?>&n=<?php echo $sn; ?>&t=<?php echo $total; ?>", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(responseText => {
            document.body.innerHTML = responseText;
            clearTimer(); // Clear timer after auto submission
        })
        .catch(error => {
            console.error("Error submitting the quiz:", error);
        });
    }
</script>

<style>
#progress-bar {
    height: 20px;
    background-color: #28a745;
    width: 100%;
}


.navigation-card {
    width: fit-content;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    background-color: #fff;
}

.navigation-calendar {
    margin: 20px auto;
    width: fit-content;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(25, 1fr); 
    /*grid-template-columns: repeat(auto-fill, minmax(30px, 1fr));*/
    gap: 5px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.calendar-grid li {
    text-align: center;
    padding: 5px;
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 50%; /* Makes the list items circular */
    font-family: Arial, sans-serif;
    font-size: 14px;
}

.calendar-grid li a {
    display: block;
    text-decoration: none;
    color: #333;
    padding: 5px;
    border-radius: 50%; /* Makes the links circular */
}

.calendar-grid li a.active,
.calendar-grid li a.answered,
.calendar-grid li a.unanswered {
    color: #fff;
}

.calendar-grid li a.active {
    background-color: #007bff;
}

.calendar-grid li a.answered {
    background-color: #28a745;
}

.calendar-grid li a.unanswered {
    background-color: #dc3545;
}
.modal-timer {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content-timer {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

#progress-bar {
    height: 20px;
    background-color: #28a745;
    width: 100%;
}
</style>



<?php
//result display
/*if(@$_GET['q']== 'result') {
    $eid=@$_GET['eid'];
    $email=$_SESSION['email'];
    $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error157');
    echo  '<div class="panel">
    <center><h1 class="title" style="color:#660033">Result</h1><center><br />
    <table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';

    while($row=mysqli_fetch_array($q)) {
        $s=$row['score'];
        $w=$row['wrong'];
        $r=$row['sahi'];
        $qa=$row['tnoq'];
        $per =round((($r / $qa) * 100),2);
        $q23=mysqli_query($con,"SELECT sub_id FROM exams WHERE  eid='$eid' " )or die('Error208');
        while($row=mysqli_fetch_array($q23)) {
            $sub_id=$row['sub_id'];
        }

        // Fetch the subject name from the subjects table
        $q24 = mysqli_query($con, "SELECT subject FROM subjects WHERE sub_id='$sub_id'") or die('Error209');
        while($row = mysqli_fetch_array($q24)) {
            $title = $row['subject'];
        }

        // Translates a precentage grade into a letter grade based on our customized scale.
        function translateToGrade($per) {
            if ($per >= 80.0) { return "A🥇"; }
            else if ($per >= 70.0) { return "B🥈"; }
            else if ($per >= 60.0) { return "C🥉"; }
            else if ($per >= 50.0) { return "Average"; }
            else { return "FAIL"; }
        }

        echo '<tr style="color:#AAAA11"><td>Username</td><td>'.$fname.'</td></tr>
              <tr style="color:#66BBII"><td>Course Title</td><td>'.$title.'</td></tr>
              <tr style="color:#66CCFF"><td>Total Questions</td><td>'.$qa.'</td></tr>
              <tr style="color:#99cc32"><td>Right Answer&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>'.$r.'</td></tr>
              <tr style="color:red"><td>Wrong Answer&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>'.$w.'</td></tr>
              <tr style="color:#66CCFF"><td>Score&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span></td><td>'.$s.'</td></tr>
              <tr style="color:#66CCGG"><td>Percentage&nbsp;<span class="glyphicon glyphicon-check" aria-hidden="true"></span></td><td>'.$per.'% - '.translateToGrade($per) .'</td></tr>';
    }
    $q=mysqli_query($con,"SELECT * FROM rank WHERE  email='$email' " )or die('Error157');
    while($row=mysqli_fetch_array($q) )
    {
        $s=$row['score'];
        echo '<tr style="color:#990000"><td>Overall Score&nbsp;<span class="glyphicon glyphicon-stats" aria-hidden="true"></span></td><td>'.$s.'</td></tr>';
    }
    echo '<center<tr style="color:#990000"  onclick="window.print()"><button class="btn btn-primary"><b>Download Result&nbsp;</b><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></button></tr></center> <br><br>';
    echo '</table></div>';
}*/
?>


<!-- Custom Modal for Confirm Submit -->
<!-- <div id="confirmModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeConfirmModal()">&times;</span>
    <p>Are you sure you want to submit?</p>
    <button id="yesButton" class="btn btn-success">Yes</button>
    <button class="btn btn-secondary" onclick="closeConfirmModal()">No</button>
  </div>
</div>

<script>
function showConfirmModal() {
    document.getElementById('confirmModal').style.display = 'block';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
}

document.getElementById('yesButton').onclick = function() {
    var form = document.getElementById('quizForm');
    form.setAttribute('action', 'update.php?q=quiz&step=2&eid=<?php echo $eid; ?>&n=<?php echo $sn; ?>&t=<?php echo $total; ?>&submit=1');
    form.submit();
}
</script> -->

<!--quiz end-->
<?php
//result start
if (@$_GET['q'] == 2) {
    echo '<div class="panel title">
    <form action="" method="GET">
        <input type="hidden" name="q" value="result">
        <div class="form-group">
            <label for="session">Select Session</label>
            <select id="session" name="session" class="form-control" required>
                <option value="">Select Session</option>';

    // Fetch distinct sessions from the exams table
    $session_query = mysqli_query($con, "SELECT DISTINCT session FROM results ORDER BY session");
    while ($row = mysqli_fetch_array($session_query)) {
        $session = $row['session'];
        echo '<option value="'.$session.'">'.$session.'</option>';
    }

    echo '  </select>
        </div>
        <div class="form-group">
            <label for="term">Select Term</label>
            <select id="term" name="term" class="form-control" required>
                <option value="">Select Term</option>';

    // Fetch terms from the terms table
    $term_query = mysqli_query($con, "SELECT termid, term FROM terms ORDER BY term");
    while ($row = mysqli_fetch_array($term_query)) {
        $termid = $row['termid'];
        $term = $row['term'];
        echo '<option value="'.$termid.'">'.$term.'</option>';
    }

    echo '  </select>
        </div>
        <button type="submit" class="btn btn-primary">View Result</button>
    </form>
    </div>';
}

if (@$_GET['q'] == 'result' && isset($_GET['session']) && isset($_GET['term'])) {
    $sessionvalue = $_GET['session'];
    $termvalue = $_GET['term'];

    // Get student details
    $email = $_SESSION['email'];
    $sid =$_SESSION['sid'];
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
    /*$studentcount_query = mysqli_query($con, "
        SELECT DISTINCT noinclass AS studentcount 
        FROM results 
        WHERE email = '$email' 
        AND session = '$sessionvalue' 
        AND termid = '$termvalue'
    ");
    $studentcount_row = mysqli_fetch_array($studentcount_query);
    $studentcount = $studentcount_row['studentcount'];*/

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
        $firsttermtotal = isset($firstterm_result['total']) ? (int)$firstterm_result['total'] : null;

        $secondterm_query = mysqli_query($con, "
            SELECT (CA + examobj + examtheory) AS total 
            FROM results 
            WHERE sub_id = '$sub_id' 
            AND email = '$email' 
            AND termid = '2' 
            AND session = '$sessionvalue'
        ");
        $secondterm_result = mysqli_fetch_assoc($secondterm_query);
        $secondtermtotal = isset($secondterm_result['total']) ? (int)$secondterm_result['total'] : null;

        $thirdterm_query = mysqli_query($con, "
            SELECT (CA + examobj + examtheory) AS total 
            FROM results 
            WHERE sub_id = '$sub_id' 
            AND email = '$email' 
            AND termid = '3' 
            AND session = '$sessionvalue'
        ");
        $thirdterm_result = mysqli_fetch_assoc($thirdterm_query);
        $thirdtermtotal = isset($thirdterm_result['total']) ? (int)$thirdterm_result['total'] : null;

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

    // Generate the per class result table
    echo '
    <div id="classResult" style="background-color: #f2f2f2; padding: 50px; font-size: 12px;">
        <img src="image/logo.png" alt="Dominican Logo" style="width: 100px; height: auto; display: block; margin: 0 auto;">
        <h3 style="text-align: center; font-size: 30px; font-weight: bolder;">DOMINICAN COLLEGE</h3>
        <p style="text-align: center; font-size: 18px;">36, Old Ewu Road, Mafoluku, Lagos</p>
        <p style="text-align: center; font-size: 15px;">Forming head, heart, and hand in the Dominican tradition</p>
        <p style="text-align: center; font-size: 18px; color: red;">TERMINAL REPORT</p>
        <table id="classResultTable" class="table table-bordered" style="border: 1px solid black; width: 100%;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid black;" colspan="2">NAME OF STUDENT: ' . $studentname . '</th>
                    <th style="border: 1px solid black;">ADMISSION NUMBER: ' . $sid . '</th>
                    <td style="border: 1px solid black;" colspan="2"><span style="font-weight: bold; color: blue;">CLASS:</span> ' . $baseclassid . '</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;" colspan="2"><span style="font-weight: bold; color: blue;">NUMBER IN CLASS:</span> ' . $noinclass . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">TERM:</span> ' . $termname . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">SESSION:</span> ' . $sessionvalue . '</td>
                </tr>
                <tr style="background-color: #f2f2f2;">
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">TOTAL MARKS OBTAINABLE:</span> ' . $total_marks_obtainable . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">TOTAL MARKS OBTAINED:</span> ' . $total_marks_obtained . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">AVERAGE:</span> ' . round($average, 2) . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">POSITION:</span> ' . $suffixedClassPosition . '</td>
                </tr>
            </thead>
        </table>

        <table class="table table-bordered" style="border: 1px solid black;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid black; font-weight: bold; color: blue;"></th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue; text-align: center; border-right: 3px solid black;" colspan="7"> ' . $termname . ' Result</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue; text-align: center;" colspan="8">Cumulative Result</th>

                </tr>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">SUBJECTS</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CA</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">EXAM</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">TOTAL</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CLASS AVERAGE</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">POSITION</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">GRADE</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue; border-right: 3px solid black;">REMARK</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">1ST TERM</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">2ND TERM</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">3RD TERM</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CUM. TOTAL</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CUM. AVERAGE</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">POSITION</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CUM. GRADE</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CUM. REMARK</th>

                </tr>
            </thead>    
            <tbody>
                <tr>
                    <td style="border: 1px solid black;">Marks Obtainable</td>
                    <td style="border: 1px solid black;">40</td>
                    <td style="border: 1px solid black;">60</td>
                    <td style="border: 1px solid black;">100</td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black; border-right: 3px solid black;"></td>
                    <td style="border: 1px solid black;">100</td>
                    <td style="border: 1px solid black;">100</td>
                    <td style="border: 1px solid black;">100</td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                </tr>';

    foreach ($ca_marks as $marks) {
        $total_marks = $marks['total'];
        $cum_avg = $marks['cumavg'];
        
        
        if ($baseclassid == 'JSS1' || $baseclassid == 'JSS2' || $baseclassid == 'JSS3') {
            // Grade and remark criteria for jss
            if ($total_marks >= 80) {
                $marks['grade'] = 'A';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'DISTINCTION';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 70) {
                $marks['grade'] = 'B';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'VERY GOOD';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 60) {
                $marks['grade'] = 'C';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'CREDIT';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 50) {
                $marks['grade'] = 'P';
                $marks['gradeColor'] = '#000000'; // Black
                $marks['remark'] = 'PASS';
                $marks['remarkColor'] = '#000000'; // Black
            } else {
                $marks['totalColor'] = '#FF0000'; // Red
                $marks['grade'] = 'F';
                $marks['gradeColor'] = '#FF0000'; // Red
                $marks['remark'] = 'FAIL';
                $marks['remarkColor'] = '#FF0000'; // Red
            }
        } elseif ($baseclassid == 'SS1' || $baseclassid == 'SS2' || $baseclassid == 'SS3') {
            // Grade and remark criteria for ss
            if ($total_marks >= 85) {
                $marks['grade'] = 'A1';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'ALPHA';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 80) {
                $marks['grade'] = 'B2';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'VERY GOOD';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 75) {
                $marks['grade'] = 'B3';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'GOOD';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 70) {
                $marks['grade'] = 'C4';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'CREDIT';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 65) {
                $marks['grade'] = 'C5';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'CREDIT';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 60) {
                $marks['grade'] = 'C6';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'CREDIT';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 55) {
                $marks['grade'] = 'D7';
                $marks['gradeColor'] = '#000000'; // Black
                $marks['remark'] = 'PASS';
                $marks['remarkColor'] = '#000000'; // Black
            } elseif ($total_marks >= 50) {
                $marks['grade'] = 'E8';
                $marks['gradeColor'] = '#000000'; // Black
                $marks['remark'] = 'PASS';
                $marks['remarkColor'] = '#000000'; // Black
            } else {
                $marks['totalColor'] = '#FF0000'; // Red
                $marks['grade'] = 'F9';
                $marks['gradeColor'] = '#FF0000'; // Red
                $marks['remark'] = 'FAIL';
                $marks['remarkColor'] = '#FF0000'; // Red
            }
        }

        if ($baseclassid == 'JSS1' || $baseclassid == 'JSS2' || $baseclassid == 'JSS3') {
            // Grade and remark criteria for jss
            if ($cum_avg >= 80) {
                $marks['cumgrade'] = 'A';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'DISTINCTION';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 70) {
                $marks['cumgrade'] = 'B';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'VERY GOOD';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 60) {
                $marks['cumgrade'] = 'C';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'CREDIT';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 50) {
                $marks['cumgrade'] = 'P';
                $marks['cumgradeColor'] = '#000000'; // Black
                $marks['cumremark'] = 'PASS';
                $marks['cumremarkColor'] = '#000000'; // Black
            } else {
                $marks['cumtotalColor'] = '#FF0000'; // Red
                $marks['cumgrade'] = 'F';
                $marks['cumgradeColor'] = '#FF0000'; // Red
                $marks['cumremark'] = 'FAIL';
                $marks['cumremarkColor'] = '#FF0000'; // Red
            }
        } elseif ($baseclassid == 'SS1' || $baseclassid == 'SS2' || $baseclassid == 'SS3') {
            // Grade and remark criteria for ss
            if ($cum_avg >= 85) {
                $marks['cumgrade'] = 'A1';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'ALPHA';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 80) {
                $marks['cumgrade'] = 'B2';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'VERY GOOD';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 75) {
                $marks['cumgrade'] = 'B3';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'GOOD';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 70) {
                $marks['cumgrade'] = 'C4';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'CREDIT';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 65) {
                $marks['cumgrade'] = 'C5';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'CREDIT';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 60) {
                $marks['cumgrade'] = 'C6';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'CREDIT';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 55) {
                $marks['cumgrade'] = 'D7';
                $marks['cumgradeColor'] = '#000000'; // Black
                $marks['cumremark'] = 'PASS';
                $marks['cumremarkColor'] = '#000000'; // Black
            } elseif ($cum_avg >= 50) {
                $marks['cumgrade'] = 'E8';
                $marks['cumgradeColor'] = '#000000'; // Black
                $marks['cumremark'] = 'PASS';
                $marks['cumremarkColor'] = '#000000'; // Black
            } else {
                $marks['cumtotalColor'] = '#FF0000'; // Red
                $marks['cumgrade'] = 'F9';
                $marks['cumgradeColor'] = '#FF0000'; // Red
                $marks['cumremark'] = 'FAIL';
                $marks['cumremarkColor'] = '#FF0000'; // Red
            }
        }

        echo '
        <tr>
            <td style="border: 1px solid black;">' . $marks['subject_name'] . '</td>
            <td style="border: 1px solid black;">' . $marks['CA'] . '</td>
            <td style="border: 1px solid black;">' . $marks['exam'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['totalColor'] . ';">' . $marks['total'] . '</td>
            <td style="border: 1px solid black;">' . $marks['classaverage'] . '</td>
            <td style="border: 1px solid black;">' . $marks['position'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['gradeColor'] . ';">' . $marks['grade'] . '</td>
            <td style="border: 1px solid black; border-right: 3px solid black; color: ' . $marks['remarkColor'] . ';">' . $marks['remark'] . '</td>
            <td style="border: 1px solid black;">' . $marks['firsttermtotal'] . '</td>
            <td style="border: 1px solid black;">' . $marks['secondtermtotal'] . '</td>
            <td style="border: 1px solid black;">' . $marks['thirdtermtotal'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['cumtotalColor'] . ';">' . $marks['cumtotal'] . '</td>
            <td style="border: 1px solid black;">' . $marks['cumavg'] . '</td>
            <td style="border: 1px solid black;">' . $marks['position'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['cumgradeColor'] . ';">' . $marks['cumgrade'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['cumremarkColor'] . ';">' . $marks['cumremark'] . '</td>
        </tr>';
    }

    

    echo '
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 50px;">
        <button onclick="generateClassPDF()" class="btn btn-primary hide-in-pdf">Download Result as PDF</button>
    </div>
    </div>';

    echo '
    <div id="armResult" style="background-color: #f2f2f2; padding: 50px; margin-top: 20px; font-size: 12px;">
        <img src="image/logo.png" alt="Dominican Logo" style="width: 100px; height: auto; display: block; margin: 0 auto;">
        <h3 style="text-align: center; font-size: 30px; font-weight: bolder;">DOMINICAN COLLEGE</h3>
        <p style="text-align: center; font-size: 18px;">36, Old Ewu Road, Mafoluku, Lagos</p>
        <p style="text-align: center; font-size: 15px;">Forming head, heart, and hand in the Dominican tradition</p>
        <p style="text-align: center; font-size: 18px; color: red;">TERMINAL REPORT</p>
        <table class="table table-bordered" style="border: 1px solid black; width: 100%;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid black;" colspan="2">NAME OF STUDENT: ' . $studentname . '</th>
                    <th style="border: 1px solid black;">ADMISSION NUMBER: ' . $sid . '</th>
                    <td style="border: 1px solid black;" colspan="2"><span style="font-weight: bold; color: blue;">CLASS:</span> ' . $class . '</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;" colspan="2"><span style="font-weight: bold; color: blue;">NUMBER IN CLASS:</span> ' . $noinclassarm . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">TERM:</span> ' . $termname . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">SESSION:</span> ' . $sessionvalue . '</td>
                </tr>
                <tr style="background-color: #f2f2f2;">
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">TOTAL MARKS OBTAINABLE:</span> ' . $total_marks_obtainable . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">TOTAL MARKS OBTAINED:</span> ' . $total_marks_obtained . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">AVERAGE:</span> ' . round($average, 2) . '</td>
                    <td style="border: 1px solid black;"><span style="font-weight: bold; color: blue;">POSITION:</span> ' . $suffixedClassArmPosition . '</td>
                </tr>
            </thead>
        </table>

        <table class="table table-bordered" style="border: 1px solid black;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid black; font-weight: bold; color: blue;"></th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue; text-align: center; border-right: 3px solid black;" colspan="7"> ' . $termname . ' Result</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue; text-align: center;" colspan="8">Cumulative Result</th>

                </tr>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">SUBJECTS</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CA</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">EXAM</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">TOTAL</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CLASS AVERAGE</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">POSITION</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">GRADE</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue; border-right: 3px solid black;">REMARK</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">1ST TERM</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">2ND TERM</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">3RD TERM</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CUM. TOTAL</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CUM. AVERAGE</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">POSITION</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CUM. GRADE</th>
                    <th style="border: 1px solid black; font-weight: bold; color: blue;">CUM. REMARK</th>

                </tr>
            </thead>    
            <tbody>
                <tr>
                    <td style="border: 1px solid black;">Marks Obtainable</td>
                    <td style="border: 1px solid black;">40</td>
                    <td style="border: 1px solid black;">60</td>
                    <td style="border: 1px solid black;">100</td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black; border-right: 3px solid black;"></td>
                    <td style="border: 1px solid black;">100</td>
                    <td style="border: 1px solid black;">100</td>
                    <td style="border: 1px solid black;">100</td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                </tr>';

    foreach ($ca_marks as $marks) {
        $total_marks = $marks['total'];
        $cum_avg = $marks['cumavg'];
        
        
        if ($baseclassid == 'JSS1' || $baseclassid == 'JSS2' || $baseclassid == 'JSS3') {
            // Grade and remark criteria for jss
            if ($total_marks >= 80) {
                $marks['grade'] = 'A';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'DISTINCTION';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 70) {
                $marks['grade'] = 'B';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'VERY GOOD';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 60) {
                $marks['grade'] = 'C';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'CREDIT';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 50) {
                $marks['grade'] = 'P';
                $marks['gradeColor'] = '#000000'; // Black
                $marks['remark'] = 'PASS';
                $marks['remarkColor'] = '#000000'; // Black
            } else {
                $marks['totalColor'] = '#FF0000'; // Red
                $marks['grade'] = 'F';
                $marks['gradeColor'] = '#FF0000'; // Red
                $marks['remark'] = 'FAIL';
                $marks['remarkColor'] = '#FF0000'; // Red
            }
        } elseif ($baseclassid == 'SS1' || $baseclassid == 'SS2' || $baseclassid == 'SS3') {
            // Grade and remark criteria for ss
            if ($total_marks >= 85) {
                $marks['grade'] = 'A1';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'ALPHA';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 80) {
                $marks['grade'] = 'B2';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'VERY GOOD';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 75) {
                $marks['grade'] = 'B3';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'GOOD';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 70) {
                $marks['grade'] = 'C4';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'CREDIT';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 65) {
                $marks['grade'] = 'C5';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'CREDIT';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 60) {
                $marks['grade'] = 'C6';
                $marks['gradeColor'] = '#008000'; // Green
                $marks['remark'] = 'CREDIT';
                $marks['remarkColor'] = '#008000'; // Green
            } elseif ($total_marks >= 55) {
                $marks['grade'] = 'D7';
                $marks['gradeColor'] = '#000000'; // Black
                $marks['remark'] = 'PASS';
                $marks['remarkColor'] = '#000000'; // Black
            } elseif ($total_marks >= 50) {
                $marks['grade'] = 'E8';
                $marks['gradeColor'] = '#000000'; // Black
                $marks['remark'] = 'PASS';
                $marks['remarkColor'] = '#000000'; // Black
            } else {
                $marks['totalColor'] = '#FF0000'; // Red
                $marks['grade'] = 'F9';
                $marks['gradeColor'] = '#FF0000'; // Red
                $marks['remark'] = 'FAIL';
                $marks['remarkColor'] = '#FF0000'; // Red
            }
        }

        if ($baseclassid == 'JSS1' || $baseclassid == 'JSS2' || $baseclassid == 'JSS3') {
            // Grade and remark criteria for jss
            if ($cum_avg >= 80) {
                $marks['cumgrade'] = 'A';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'DISTINCTION';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 70) {
                $marks['cumgrade'] = 'B';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'VERY GOOD';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 60) {
                $marks['cumgrade'] = 'C';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'CREDIT';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 50) {
                $marks['cumgrade'] = 'P';
                $marks['cumgradeColor'] = '#000000'; // Black
                $marks['cumremark'] = 'PASS';
                $marks['cumremarkColor'] = '#000000'; // Black
            } else {
                $marks['cumtotalColor'] = '#FF0000'; // Red
                $marks['cumgrade'] = 'F';
                $marks['cumgradeColor'] = '#FF0000'; // Red
                $marks['cumremark'] = 'FAIL';
                $marks['cumremarkColor'] = '#FF0000'; // Red
            }
        } elseif ($baseclassid == 'SS1' || $baseclassid == 'SS2' || $baseclassid == 'SS3') {
            // Grade and remark criteria for ss
            if ($cum_avg >= 85) {
                $marks['cumgrade'] = 'A1';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'ALPHA';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 80) {
                $marks['cumgrade'] = 'B2';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'VERY GOOD';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 75) {
                $marks['cumgrade'] = 'B3';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'GOOD';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 70) {
                $marks['cumgrade'] = 'C4';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'CREDIT';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 65) {
                $marks['cumgrade'] = 'C5';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'CREDIT';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 60) {
                $marks['cumgrade'] = 'C6';
                $marks['cumgradeColor'] = '#008000'; // Green
                $marks['cumremark'] = 'CREDIT';
                $marks['cumremarkColor'] = '#008000'; // Green
            } elseif ($cum_avg >= 55) {
                $marks['cumgrade'] = 'D7';
                $marks['cumgradeColor'] = '#000000'; // Black
                $marks['cumremark'] = 'PASS';
                $marks['cumremarkColor'] = '#000000'; // Black
            } elseif ($cum_avg >= 50) {
                $marks['cumgrade'] = 'E8';
                $marks['cumgradeColor'] = '#000000'; // Black
                $marks['cumremark'] = 'PASS';
                $marks['cumremarkColor'] = '#000000'; // Black
            } else {
                $marks['cumtotalColor'] = '#FF0000'; // Red
                $marks['cumgrade'] = 'F9';
                $marks['cumgradeColor'] = '#FF0000'; // Red
                $marks['cumremark'] = 'FAIL';
                $marks['cumremarkColor'] = '#FF0000'; // Red
            }
        }

        echo '
        <tr>
            <td style="border: 1px solid black;">' . $marks['subject_name'] . '</td>
            <td style="border: 1px solid black;">' . $marks['CA'] . '</td>
            <td style="border: 1px solid black;">' . $marks['exam'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['totalColor'] . ';">' . $marks['total'] . '</td>
            <td style="border: 1px solid black;">' . $marks['classaverage'] . '</td>
            <td style="border: 1px solid black;">' . $marks['armposition'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['gradeColor'] . ';">' . $marks['grade'] . '</td>
            <td style="border: 1px solid black; border-right: 3px solid black; color: ' . $marks['remarkColor'] . ';">' . $marks['remark'] . '</td>
            <td style="border: 1px solid black;">' . $marks['firsttermtotal'] . '</td>
            <td style="border: 1px solid black;">' . $marks['secondtermtotal'] . '</td>
            <td style="border: 1px solid black;">' . $marks['thirdtermtotal'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['cumtotalColor'] . ';">' . $marks['cumtotal'] . '</td>
            <td style="border: 1px solid black;">' . $marks['cumavg'] . '</td>
            <td style="border: 1px solid black;">' . $marks['armposition'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['cumgradeColor'] . ';">' . $marks['cumgrade'] . '</td>
            <td style="border: 1px solid black; color: ' . $marks['cumremarkColor'] . ';">' . $marks['cumremark'] . '</td>
        </tr>';
    }

    

    echo '
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 50px;">
        <button onclick="generateArmPDF()" class="btn btn-primary hide-in-pdf">Download Result as PDF</button>
    </div>
    </div>';
}
?>

<style>
    /* Hide button for printing and PDF */
    @media print {
        .hide-in-pdf {
            display: none !important;
        }
    }
</style>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script>
    function generateClassPDF() {
    const element = document.getElementById('classResult');
    var opt = {
        margin: [0, 0, 0, 0], // top, left, bottom, right
        filename: "<?php echo $studentname . '_' . $termname . '_result'; ?>.pdf",
        image: { type: 'jpeg', quality: 1 }, // set the image quality to the highest
        html2canvas: { scale: 3, logging: true, dpi: 192, letterRendering: true }, // increase the scale for a better image, enable logging for debugging
        jsPDF: { unit: 'in', format: 'a3', orientation: 'landscape' } // set to landscape mode
    };
    html2pdf().from(element).set(opt).save();
}

    function generateArmPDF() {
    const element = document.getElementById('armResult');
    var opt = {
        margin: [0, 0, 0, 0], // top, left, bottom, right
        filename: "<?php echo $studentname . '_' . $termname . '_result'; ?>.pdf",
        image: { type: 'jpeg', quality: 1 }, // set the image quality to the highest
        html2canvas: { scale: 3, logging: true, dpi: 192, letterRendering: true }, // increase the scale for a better image, enable logging for debugging
        jsPDF: { unit: 'in', format: 'a3', orientation: 'landscape' } // set to landscape mode
    };
    html2pdf().from(element).set(opt).save();
}
</script>

</div></div></div></div></div>
<!--Footer start-->
<div class="row footer">
<div class="col-md-4 box">
<a href="#" data-toggle="modal" data-target="#abtus">About Us</a>
</div>
<div class="col-md-4 box">
<a href="#" data-toggle="modal" data-target="#developers">Developers</a>
</div>
<!-- <div class="col-md-4 box">
<a href="feedback.php" target="_blank">Feedback</a>
</div> -->
</div>
<!-- Modal For Developers-->
<div class="modal fade title1" id="developers">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" style="font-family:'typo' "><span style="color:orange">Developer</span></h4>
      </div>
      <div class="modal-body">
        <p>
    <div class="row">
    <div class="col-md-4">
     <img src="" width=100 height=100 alt="Developer" title="" class="img-rounded">
     </div>
     <div class="col-md-5">
  <a href="#" target="_blank" style="color:#202020; font-family:'typo' ; font-size:18px" title="#">Vincent Okoli</a>
    <h4 style="color:#202020; font-family:'typo' ;font-size:16px" class="title1">+234 9036720921</h4>
    <h4 style="font-family:'typo' ">crazycoder44gmail.com</h4>
    <h4 style="font-family:'typo' ">Beginner Web Developer</h4></div></div>
    </p>
      </div>
      <div class="modal-body">
        <p>
    <div class="row">
    <div class="col-md-4">
     <img src="" width=100 height=100 alt="Developer" title="" class="img-rounded">
     </div>
     <div class="col-md-5">
  <a href="#" target="_blank" style="color:#202020; font-family:'typo' ; font-size:18px" title="#">Vincent Okoli</a>
    <h4 style="color:#202020; font-family:'typo' ;font-size:16px" class="title1">+234 8134424700</h4>
    <h4 style="font-family:'typo' ">crazycoder44gmail.com</h4>
    <h4 style="font-family:'typo' ">Beginner Web Developer</h4></div></div>
    </p>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Modal For About Us -->
<div class="modal fade title1" id="abtus">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h1 class="modal-title" style="font-family:'typo' "><span style="color:orange"><center><b>About Us</b></center></span></h1>
      </div>
      <div class="modal-body">
        <p>
    <div class="row">
    <div class="col-md-4">
     <img src="image/author.jpg" width=100 height=100 alt="About Us" title="About Us Interface" class="img-rounded">
     </div>

     <div class="col-md-5">
    <a style="color:orange; font-family:'typo' ; font-size:20px" title="Find on Facebook"><b>CBE Portal</b></a>
    <h4 style="color:#202020; font-family:'typo' ;font-size:16px" class="">CBE Portal Is a Programme Developed By Crazy Coder. 
      Its aim is to improve the teaching and learning of students and teachers respectively. 
      Teachers can get the experience of preparing Computer Based Exams and students can also get the experience of writing Computer Based Exams which is mostly used by both Local and International Certificate Examination bodies such as WAEC.
      With this software, the teachers and students can become better equipped with modern educational practices which gives them an edge in thier educational and career pursuits.</h4>
    <h4 style="font-family:'typo' ">Copyright &copy;<script>document.write(new Date().getFullYear());</script> - <a href="mailto:crazycoder44@gmail.com">crazycoder44@gmail.com</a>
    </p>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Modal for admin login-->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--footer end-->
</body></body>
</html>
